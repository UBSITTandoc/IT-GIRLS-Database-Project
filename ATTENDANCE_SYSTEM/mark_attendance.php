<?php 
require_once 'db_connect.php'; 
// Set timezone for consistency, typically handled by the global include/dashboard, but good practice here.
date_default_timezone_set('Asia/Manila');

// Initialize variables
$search_term = $_GET['search'] ?? '';
$selected_student_id = $_GET['student_id'] ?? '';
$success_message = "";
$error_message = "";
$warning_message = "";

// --- 1. CORE LOGIC: Find Student(s) ---
$student = null;
$student_results = null;

if (!empty($selected_student_id)) {
    // A specific student has been selected, fetch their details for the detailed view
    $sid = $conn->real_escape_string($selected_student_id);
    $student_sql = "SELECT s.*, d.DepartmentName 
                    FROM student s 
                    LEFT JOIN department d ON s.DepartmentID = d.DepartmentID 
                    WHERE s.ID = '$sid'";
    $student_res = $conn->query($student_sql);
    $student = $student_res->fetch_assoc();

} elseif (!empty($search_term)) {
    // Search term entered, perform fuzzy search for multiple results
    $search = $conn->real_escape_string($search_term);
    $search_pattern = "%{$search}%";

    // *** MODIFIED SQL QUERY ***
    // Searches by partial StudentNumber, FirstName, OR LastName, and returns ALL matches.
    $student_sql = "SELECT s.*, d.DepartmentName 
                    FROM student s 
                    LEFT JOIN department d ON s.DepartmentID = d.DepartmentID 
                    WHERE s.StudentNumber LIKE '$search_pattern' 
                    OR s.LastName LIKE '$search_pattern' 
                    OR s.FirstName LIKE '$search_pattern' 
                    ORDER BY s.LastName, s.FirstName";
    
    $student_results = $conn->query($student_sql);

    // If search returns exactly one result, automatically jump to detailed view
    if ($student_results && $student_results->num_rows === 1) {
        $student = $student_results->fetch_assoc();
        // Clear multiple results to proceed to single student logic
        $student_results = null; 
    }
}


// --- 2. ATTENDANCE SUBMISSION LOGIC (Only runs if a single student is selected) ---
if ($student && isset($_POST['mark_attendance'])) {
    
    // ... (Attendance submission logic from the dashboard file - kept for completeness) ...
    $sched_id = $conn->real_escape_string($_POST['schedule_id']);
    $sid = $conn->real_escape_string($student['ID']);
    $sub_id = $conn->real_escape_string($_POST['subject_id']);
    $today = date('Y-m-d');
    
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
            $status = 'Late';
            
            if ($time_now_timestamp > $end_time_timestamp) {
                $warning_message = "Attendance was marked after the scheduled class end time (" . date('h:i A', $end_time_timestamp) . "). Status recorded as Late.";
            }
        }
        
        $status_db = $conn->real_escape_string($status);
        
        $check_sql = "SELECT AttendanceID FROM attendance WHERE StudentID = '$sid' AND StudentScheduleID = '$sched_id' AND Date = '$today'";
        $check_res = $conn->query($check_sql);

        if ($check_res->num_rows > 0) {
            $upd = "UPDATE attendance SET Status = '$status_db', TimeIn = '$time_now_str' WHERE StudentID = '$sid' AND StudentScheduleID = '$sched_id' AND Date = '$today'";
        } else {
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-4">
        <h2 class="mb-4 text-center text-success">✅ Check and Mark Attendance</h2>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h4 class="mb-0">🔍 Search Student</h4>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-center">
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="search" placeholder="Enter Student Number, Last Name, or First Name (e.g., l)" required value="<?php echo htmlspecialchars($search_term); ?>">
                        <input type="hidden" name="student_id" value=""> 
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-success w-100">Search Student</button>
                    </div>
                </form>
            </div>
        </div>
        
        <?php
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

        <?php 
        // --- 3. DISPLAY SEARCH RESULTS (Multiple Students) ---
        if ($student_results && $student_results->num_rows > 1): 
        ?>
            <div class="alert alert-info">Multiple students matched '<?php echo htmlspecialchars($search_term); ?>'. Please select the correct student below.</div>
            <div class="table-responsive shadow-sm rounded mb-4">
                <table class="table table-striped table-hover table-sm mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Student No.</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $student_results->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['StudentNumber']); ?></td>
                                <td><?php echo htmlspecialchars($row['LastName'] . ', ' . $row['FirstName']); ?></td>
                                <td><?php echo htmlspecialchars($row['DepartmentName'] ?? 'N/A'); ?></td>
                                <td>
                                    <form method="GET" style="display:inline;">
                                        <input type="hidden" name="search" value="<?php echo htmlspecialchars($search_term); ?>">
                                        <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($row['ID']); ?>">
                                        <button type="submit" class="btn btn-sm btn-info">Select</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        <?php 
        // --- 4. DISPLAY DETAILED STUDENT VIEW (Single Student) ---
        elseif ($student): 
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
                    // Show Student's Schedule
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
        // --- 5. NO RESULTS FOUND ---
        elseif (!empty($search_term)):
        ?>
            <div class='alert alert-danger'>Student with number/name '<strong><?php echo htmlspecialchars($search_term); ?></strong>' not found. Please check the Student Number or name.</div>
        <?php endif; ?>
        
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>