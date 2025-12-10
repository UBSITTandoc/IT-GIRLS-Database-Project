<?php 
require_once 'db_connect.php'; 

// *** FIX: Set Timezone to Asia/Manila (PST) to correct time discrepancy ***
date_default_timezone_set('Asia/Manila');

// --- Data Fetching for Dashboard and Collapsible Sections ---

// 1. Fetch all students for the Total Students list
$sql_all_students = "SELECT s.StudentNumber, s.FirstName, s.LastName, d.DepartmentName 
                     FROM student s 
                     LEFT JOIN department d ON s.DepartmentID = d.DepartmentID 
                     ORDER BY s.LastName, s.FirstName ASC";
$all_students_result = $conn->query($sql_all_students);
$all_students = $all_students_result->fetch_all(MYSQLI_ASSOC);
$total_students_count = count($all_students);

// 2. Fetch all departments for the Departments list
$sql_departments = "SELECT DepartmentID, DepartmentName FROM department ORDER BY DepartmentName ASC";
$departments_result = $conn->query($sql_departments);
$departments = [];
if ($departments_result && $departments_result->num_rows > 0) {
    while ($row = $departments_result->fetch_assoc()) {
        $departments[] = $row;
    }
}

// 3. Fetch all subjects for the Subjects list
$sql_all_subjects = "SELECT SubjectID, SubjectName FROM subject ORDER BY SubjectName ASC";
$all_subjects_result = $conn->query($sql_all_subjects);
$all_subjects = $all_subjects_result->fetch_all(MYSQLI_ASSOC);
$total_subjects_count = count($all_subjects);

// 4. Fetch today's unique present students grouped by department
$today = date('Y-m-d');
$sql_present = "
    SELECT DISTINCT
        d.DepartmentName,
        s.StudentNumber,
        s.FirstName,
        s.LastName
    FROM attendance a
    JOIN student s ON a.StudentID = s.ID
    LEFT JOIN department d ON s.DepartmentID = d.DepartmentID
    WHERE a.Date = '{$today}' AND a.Status IN ('Present', 'Late')
    ORDER BY d.DepartmentName ASC, s.LastName ASC, s.FirstName ASC
";
$present_result = $conn->query($sql_present);

$present_students_by_dept = [];
$total_present_count = 0;

if ($present_result) {
    while ($row = $present_result->fetch_assoc()) {
        $dept_name = $row['DepartmentName'] ? $row['DepartmentName'] : 'Unassigned Department';
        if (!isset($present_students_by_dept[$dept_name])) {
            $present_students_by_dept[$dept_name] = [];
        }
        $present_students_by_dept[$dept_name][] = $row;
        $total_present_count++;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Attendance System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Ensures the dashboard cards/links visually look like a card */
        .dashboard-card-trigger {
            display: block;
            text-decoration: none;
            color: inherit;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-4">
        <h2 class="mb-4 text-center text-primary">📊 Dashboard & Attendance Check</h2>
        <hr>

        <?php
        $success_message = "";
        $error_message = "";
        $warning_message = "";
        
        // --- Attendance Marking Logic ---
        if (isset($_GET['search']) || isset($_POST['mark_attendance'])) {
            $search = $conn->real_escape_string($_GET['search'] ?? $_POST['search']);
            
            // 1. Find Student by StudentNumber OR LastName
            $student_sql = "SELECT s.*, d.DepartmentName 
                            FROM student s 
                            LEFT JOIN department d ON s.DepartmentID = d.DepartmentID 
                            WHERE s.StudentNumber = '$search' OR s.LastName LIKE '%$search%' LIMIT 1";
            $student_res = $conn->query($student_sql);
            $student = $student_res->fetch_assoc();

            if ($student) {
                // 2. Handle Attendance Submission
                if (isset($_POST['mark_attendance'])) {
                    $sched_id = $conn->real_escape_string($_POST['schedule_id']);
                    $sid = $conn->real_escape_string($student['ID']);
                    $sub_id = $conn->real_escape_string($_POST['subject_id']);
                    $today = date('Y-m-d');
                    
                    // Uses the configured 'Asia/Manila' time zone
                    $time_now_str = date('H:i:s'); 
                    $time_now_timestamp = time(); 

                    // Fetch schedule times for automatic status calculation
                    $times_sql = "SELECT StartTime, EndTime FROM student_subject_schedule WHERE StudentScheduleID = '$sched_id'";
                    $times_res = $conn->query($times_sql)->fetch_assoc();

                    if ($times_res) {
                        // Use the current date with schedule time to create a comparable timestamp
                        $start_time_timestamp = strtotime($times_res['StartTime']);
                        $end_time_timestamp = strtotime($times_res['EndTime']);
                        
                        // --- AUTOMATIC STATUS CALCULATION LOGIC ---
                        $grace_period_seconds = 10 * 60; // 10 minutes grace period
                        $late_cutoff_time = $start_time_timestamp + $grace_period_seconds;

                        if ($time_now_timestamp <= $late_cutoff_time) {
                            $status = 'Present';
                        } else {
                            // Status is Late if past the cutoff.
                            $status = 'Late';
                            
                            if ($time_now_timestamp > $end_time_timestamp) {
                                $warning_message = "Attendance was marked after the scheduled class end time (" . date('h:i A', $end_time_timestamp) . "). Status recorded as Late.";
                            }
                        }
                        
                        $status_db = $conn->real_escape_string($status);
                        
                        // ABSENT NOTE: 'Absent' status is handled by the mark_absent_cron.php script
                        // running nightly.

                        $check_sql = "SELECT AttendanceID FROM attendance WHERE StudentID = '$sid' AND StudentScheduleID = '$sched_id' AND Date = '$today'";
                        $check_res = $conn->query($check_sql);

                        if ($check_res->num_rows > 0) {
                            // If record exists, update time and status
                            $upd = "UPDATE attendance SET Status = '$status_db', TimeIn = '$time_now_str' WHERE StudentID = '$sid' AND StudentScheduleID = '$sched_id' AND Date = '$today'";
                        } else {
                            // If no record, insert new
                            $upd = "INSERT INTO attendance (StudentScheduleID, StudentID, SubjectID, Date, TimeIn, Status) 
                                     VALUES ('$sched_id', '$sid', '$sub_id', '$today', '$time_now_str', '$status_db')";
                        }
                        
                        if($conn->query($upd)) {
                            $success_message = "Attendance automatically marked as {$status} for {$student['LastName']}, {$student['FirstName']} at " . date('h:i:s A', $time_now_timestamp) . "!";
                        } else {
                            $error_message = "Error marking attendance: " . $conn->error;
                        }
                    } else {
                         $error_message = "Error: Schedule details not found for ID $sched_id.";
                    }
                }
                
                // Display messages
                if ($success_message) {
                    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'><strong>Success!</strong> $success_message<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
                }
                if ($warning_message) {
                    echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'><strong>Warning!</strong> $warning_message<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
                }
                if ($error_message) {
                    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'><strong>Error!</strong> $error_message<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
                }
                
                ?>
                <div class="card mb-4 shadow border-success">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">✅ Student Found: <?php echo htmlspecialchars($student['FirstName'] . ' ' . $student['LastName']); ?> 
                            <small class="badge bg-light text-success ms-2">#<?php echo $student['StudentNumber'] ?? 'N/A'; ?></small>
                        </h4>
                        <small>Department: <?php echo htmlspecialchars($student['DepartmentName'] ?? 'N/A'); ?></small>
                    </div>
                    <div class="card-body">
                        <h5>Select Class to Mark Attendance Today:</h5>
                        <p class="text-muted small">Status will be automatically set to Present (within 10 mins of start) or Late (after 10 mins).</p>
                        <?php
                        // 3. Show Student's Schedule
                        $sql_sched = "SELECT sss.StudentScheduleID, sss.SubjectID, sub.SubjectName, sss.DayOfWeek, sss.StartTime, sss.EndTime, r.RoomName
                                        FROM student_subject_schedule sss
                                        JOIN subject sub ON sss.SubjectID = sub.SubjectID
                                        JOIN room r ON sss.RoomID = r.RoomID
                                        WHERE sss.StudentID = {$student['ID']}
                                        ORDER BY FIELD(sss.DayOfWeek, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), sss.StartTime ASC";
                        
                        $schedules = $conn->query($sql_sched);
                        
                        if ($schedules->num_rows > 0) {
                            echo "<div class='table-responsive'>";
                            echo "<table class='table table-bordered table-sm'>";
                            echo "<thead class='table-secondary'><tr><th>Subject</th><th>Room</th><th>Day/Time</th><th>Action</th></tr></thead>";
                            echo "<tbody>";
                            while ($row = $schedules->fetch_assoc()) {
                                $time_in_display = date("h:i A", strtotime($row['StartTime']));
                                $time_out_display = date("h:i A", strtotime($row['EndTime']));
                                
                                echo "<tr>";
                                echo "<td>{$row['SubjectName']}</td>";
                                echo "<td>{$row['RoomName']}</td>";
                                echo "<td>{$row['DayOfWeek']} ({$time_in_display} - {$time_out_display})</td>";
                                echo "<td>
                                            <form method='POST' class='d-flex align-items-center'>
                                                <input type='hidden' name='schedule_id' value='{$row['StudentScheduleID']}'>
                                                <input type='hidden' name='subject_id' value='{$row['SubjectID']}'>
                                                <input type='hidden' name='search' value='{$student['StudentNumber']}'>
                                                <button type='submit' name='mark_attendance' class='btn btn-sm btn-primary text-nowrap'>Mark Now (Auto Status)</button>
                                            </form>
                                            </td>";
                                echo "</tr>";
                            }
                            echo "</tbody></table>";
                            echo "</div>";
                        } else {
                            echo "<div class='alert alert-warning'>No schedule found for this student.</div>";
                        }
                        ?>
                    </div>
                </div>
                <?php
            } elseif (isset($_GET['search']) || isset($_POST['mark_attendance'])) {
                echo "<div class='alert alert-danger'>Student with number/name '<strong>" . htmlspecialchars($search) . "</strong>' not found. Please check the Student Number or last name.</div>";
            }
        }
        ?>

        <div class="card shadow-sm mb-5 border-info">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0">🔍 Attendance Check-In</h4>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-center">
                    <div class="col-md-9">
                        <input type="text" class="form-control form-control-lg" name="search" placeholder="Enter Student Number or Last Name (e.g., 20232551 or kimberly)" required value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-info btn-lg w-100 text-white">Search/Check Student</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            
            <div class="col-md-3">
                <a class="dashboard-card-trigger" data-bs-toggle="collapse" href="#totalStudentList" role="button" aria-expanded="false" aria-controls="totalStudentList">
                    <div class="card text-white bg-primary mb-3 shadow">
                        <div class="card-header">Total Students</div>
                        <div class="card-body">
                            <h1 class='card-title'><?php echo $total_students_count; ?></h1>
                            <p class="card-text">Click to view Master List</p>
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-md-3">
                <a class="dashboard-card-trigger" data-bs-toggle="collapse" href="#departmentsStudentList" role="button" aria-expanded="false" aria-controls="departmentsStudentList">
                    <div class="card text-white bg-secondary mb-3 shadow"> 
                        <div class="card-header">Departments</div>
                        <div class="card-body">
                            <?php 
                            $count_departments = $conn->query("SELECT COUNT(*) as total FROM department")->fetch_assoc();
                            echo "<h1 class='card-title'>{$count_departments['total']}</h1>";
                            ?>
                            <p class="card-text">Click to view students by department</p>
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-md-3">
                <a class="dashboard-card-trigger" data-bs-toggle="collapse" href="#totalSubjectList" role="button" aria-expanded="false" aria-controls="totalSubjectList">
                    <div class="card text-white bg-primary mb-3 shadow">
                        <div class="card-header">Subjects</div>
                        <div class="card-body">
                            <h1 class='card-title'><?php echo $total_subjects_count; ?></h1>
                            <p class="card-text">Click to view all subjects</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a class="dashboard-card-trigger" data-bs-toggle="collapse" href="#todayPresentList" role="button" aria-expanded="false" aria-controls="todayPresentList">
                    <div class="card text-white bg-success mb-3 shadow">
                        <div class="card-header">Today's Present Students</div>
                        <div class="card-body">
                            <h1 class='card-title'><?php echo $total_present_count; ?></h1>
                            <p class="card-text">Click to view list by Department</p>
                        </div>
                    </div>
                </a>
            </div>

        </div> <hr class="mt-0">
        
        <div class="collapse mt-4" id="todayPresentList">
            <div class="card card-body p-0 border-0">
                <h3 class="mb-4 text-success">Students Marked Present/Late Today (<?php echo date('F j, Y'); ?>)</h3>
                
                <?php if ($total_present_count == 0): ?>
                    <div class="alert alert-warning text-center">No students have been marked as Present or Late today.</div>
                <?php else: ?>
                    
                    <div class="accordion" id="presentDeptAccordion">
                    <?php 
                    $i = 0;
                    foreach ($present_students_by_dept as $dept_name => $students): 
                        $accordion_id = 'present_dept_collapse_' . $i;
                        $i++;
                    ?>
                        <div class="accordion-item shadow-sm border-success mb-3">
                            <h2 class="accordion-header" id="present_heading_<?php echo $i; ?>">
                                <button class="accordion-button collapsed bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo $accordion_id; ?>" aria-expanded="false" aria-controls="<?php echo $accordion_id; ?>">
                                    ✅ <?php echo htmlspecialchars($dept_name); ?> <span class="badge bg-success ms-2"><?php echo count($students); ?> Present/Late Today</span>
                                </button>
                            </h2>
                            <div id="<?php echo $accordion_id; ?>" class="accordion-collapse collapse" aria-labelledby="present_heading_<?php echo $i; ?>" data-bs-parent="#presentDeptAccordion">
                                <div class="accordion-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-sm mb-0">
                                            <thead>
                                                <tr>
                                                    <th style="width: 20%;">Student No.</th>
                                                    <th>Student Name</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($students as $student): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($student['StudentNumber']); ?></td>
                                                        <td><?php echo htmlspecialchars($student['LastName'] . ', ' . $student['FirstName']); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </div> <?php endif; ?>
            </div>
        </div>
        <div class="collapse mt-4" id="totalStudentList">
            <div class="card card-body p-0 border-0">
                <h3 class="mb-3 text-primary">Master Student List (Total: <?php echo $total_students_count; ?>)</h3>
                
                <?php if ($total_students_count == 0): ?>
                    <div class="alert alert-warning text-center">No students have been registered in the system yet.</div>
                <?php else: ?>
                    <div class="table-responsive shadow-sm rounded">
                        <table class="table table-striped table-hover table-sm mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 15%;">Student No.</th>
                                    <th>Student Name</th>
                                    <th>Department</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($all_students as $student): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($student['StudentNumber']); ?></td>
                                        <td><?php echo htmlspecialchars($student['LastName'] . ', ' . $student['FirstName']); ?></td>
                                        <td><?php echo htmlspecialchars($student['DepartmentName'] ?? 'N/A'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="collapse mt-4" id="totalSubjectList">
            <div class="card card-body p-0 border-0">
                <h3 class="mb-3 text-primary">Master Subject List (Total: <?php echo $total_subjects_count; ?>)</h3>
                
                <?php if ($total_subjects_count == 0): ?>
                    <div class="alert alert-warning text-center">No subjects have been registered in the system yet.</div>
                <?php else: ?>
                    <div class="table-responsive shadow-sm rounded">
                        <table class="table table-striped table-hover table-sm mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 15%;">Subject ID</th>
                                    <th>Subject Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($all_subjects as $subject): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($subject['SubjectID']); ?></td>
                                        <td><?php echo htmlspecialchars($subject['SubjectName']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="collapse mt-4" id="departmentsStudentList">
            <div class="card card-body p-0 border-0">
                <h3 class="mb-4 text-secondary">Students Grouped by Department</h3>
                
                <?php if (empty($departments)): ?>
                    <div class="alert alert-warning text-center">No departments have been registered yet.</div>
                <?php else: ?>
                    
                    <div class="accordion" id="departmentAccordion">
                    <?php foreach ($departments as $department): ?>
                        <?php
                        $dept_id = $department['DepartmentID'];
                        $dept_name = htmlspecialchars($department['DepartmentName']);
                        
                        // Fetch students for the current department
                        $sql_students = "SELECT StudentNumber, FirstName, LastName 
                                         FROM student 
                                         WHERE DepartmentID = '{$dept_id}' 
                                         ORDER BY LastName, FirstName ASC";
                        $students_result = $conn->query($sql_students);
                        $student_count = $students_result->num_rows;
                        
                        $accordion_id = 'dept_collapse_' . $dept_id;
                        ?>
                        
                        <div class="accordion-item shadow-sm border-secondary mb-3">
                            <h2 class="accordion-header" id="heading_<?php echo $dept_id; ?>">
                                <button class="accordion-button collapsed bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo $accordion_id; ?>" aria-expanded="false" aria-controls="<?php echo $accordion_id; ?>">
                                    🏢 <?php echo $dept_name; ?> <span class="badge bg-secondary ms-2"><?php echo $student_count; ?> Students Enrolled</span>
                                </button>
                            </h2>
                            <div id="<?php echo $accordion_id; ?>" class="accordion-collapse collapse" aria-labelledby="heading_<?php echo $dept_id; ?>" data-bs-parent="#departmentAccordion">
                                <div class="accordion-body p-0">
                                    <?php if ($student_count > 0): ?>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover table-sm mb-0">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 20%;">Student No.</th>
                                                        <th>Student Name</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php while ($student = $students_result->fetch_assoc()): ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($student['StudentNumber']); ?></td>
                                                            <td><?php echo htmlspecialchars($student['LastName'] . ', ' . $student['FirstName']); ?></td>
                                                        </tr>
                                                    <?php endwhile; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-info mb-0 m-3">No students are currently enrolled in this department.</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </div> <?php endif; ?>
            </div>
        </div>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>