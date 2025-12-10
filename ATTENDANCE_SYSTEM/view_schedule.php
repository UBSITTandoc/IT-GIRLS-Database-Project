<?php
// Include the database connection
require_once 'db_connect.php'; 

// Function to safely redirect
function redirect_to($url) {
    header("Location: $url");
    exit();
}

$success_message = "";
$error_message = "";

// PHP 5.x compatible check for student ID selection
$selected_student_id = isset($_POST['student_id']) ? $_POST['student_id'] : (isset($_GET['student_id']) ? $_GET['student_id'] : null);
$weekly_schedule = [];
$student_name = '';
$full_schedule_list = []; 

// The days of the week in order
$days_of_week = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

// --- 1. Handle Delete Request (Must be before data fetching) ---
if (isset($_POST['delete_schedule_id'])) {
    $id = $conn->real_escape_string($_POST['delete_schedule_id']);
    // Capture the student ID for redirection
    $student_id_for_redirect = $conn->real_escape_string($_POST['student_id_hidden']);
    
    // Delete from the student_subject_schedule table.
    $sql = "DELETE FROM student_subject_schedule WHERE StudentScheduleID = '$id'";
    
    if ($conn->query($sql)) {
        // Redirect back, preserving the selected student ID
        redirect_to("view_schedule.php?status=deleted&student_id=" . urlencode($student_id_for_redirect));
    } else {
        redirect_to("view_schedule.php?status=error&message=" . urlencode("Error deleting schedule: " . $conn->error) . "&student_id=" . urlencode($student_id_for_redirect));
    }
}


// --- 2. Fetch all students (NOW INCLUDING StudentNumber) ---
$sql_students = "SELECT ID, FirstName, LastName, StudentNumber FROM student ORDER BY LastName, FirstName";
$students_result = $conn->query($sql_students);
$students = [];
if ($students_result) {
    while ($row = $students_result->fetch_assoc()) {
        $students[] = $row;
    }
}

// --- 3. Handle Status Messages after Redirect ---
if (isset($_GET['status'])) {
    if ($_GET['status'] == 'deleted') {
        $success_message = "Schedule entry successfully deleted.";
    } elseif (isset($_GET['message']) && $_GET['status'] == 'error') {
        $error_message = $_GET['message'];
    }
}


// --- 4. Handle Schedule Data Fetching ---
if ($selected_student_id) {
    // A. Fetch the student's name for the header
    // NOTE: This query doesn't need to change as the header only uses name.
    $sql_name = "SELECT FirstName, LastName FROM student WHERE ID = ?";
    $stmt_name = $conn->prepare($sql_name);
    if ($stmt_name) {
        $stmt_name->bind_param("i", $selected_student_id);
        $stmt_name->execute();
        $name_result = $stmt_name->get_result()->fetch_assoc();
        $student_name = $name_result ? htmlspecialchars($name_result['LastName'] . ', ' . $name_result['FirstName']) : 'Unknown Student';
        $stmt_name->close();
    }

    // B. Fetch the student's entire weekly schedule (for both grid and list)
    $sql_schedule = "
        SELECT
            sss.StudentScheduleID,
            sss.DayOfWeek, sss.StartTime, sss.EndTime,
            s.SubjectName,
            r.RoomName
        FROM student_subject_schedule sss
        JOIN subject s ON sss.SubjectID = s.SubjectID
        JOIN room r ON sss.RoomID = r.RoomID
        WHERE sss.StudentID = ?
        ORDER BY FIELD(sss.DayOfWeek, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), sss.StartTime ASC";
    
    $stmt_schedule = $conn->prepare($sql_schedule);
    
    if ($stmt_schedule) {
        $stmt_schedule->bind_param("i", $selected_student_id);
        $stmt_schedule->execute();
        $result = $stmt_schedule->get_result();

        // Organize the schedule by day for easy grid rendering AND save to list array
        while ($row = $result->fetch_assoc()) {
            $weekly_schedule[$row['DayOfWeek']][] = $row;
            $full_schedule_list[] = $row; // Store for the list below
        }
        $stmt_schedule->close();
    }
}

// Helper function to format 24-hour time to 12-hour AM/PM for display
function formatTime12h($time24h) {
    return date("h:i A", strtotime($time24h));
}

// Helper function to generate a color based on the subject name (simple hash)
function getColorBySubject($subjectName) {
    $hash = crc32($subjectName);
    $colors = [
        '#6c757d', '#007bff', '#28a745', '#dc3545', '#ffc107',
        '#17a2b8', '#fd7e14', '#e83e8c', '#6f42c1', '#20c997'
    ];
    return $colors[abs($hash) % count($colors)];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Student Weekly Schedule</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container { max-width: 1200px; margin: auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        .schedule-table { width: 100%; border-collapse: collapse; margin-top: 20px; table-layout: fixed; }
        .schedule-table th, .schedule-table td { border: 1px solid #ddd; padding: 10px; text-align: center; height: 100px; vertical-align: top; }
        .schedule-table th { background-color: #007bff; color: white; font-weight: bold; }
        .schedule-cell { margin-bottom: 5px; padding: 5px; border-radius: 4px; color: white; font-size: 0.85em; line-height: 1.3; overflow: hidden; text-overflow: ellipsis; }
        .no-class { color: #aaa; font-style: italic; }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
<div class="container mt-4">
    <h2 class="text-center text-primary">📅 Student Weekly Schedule Viewer</h2>
    <hr>

    <?php
    // Display messages
    if ($success_message) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'><strong>Success!</strong> $success_message<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    }
    if ($error_message) {
        echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'><strong>Error!</strong> $error_message<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    }
    ?>

    <form method="POST" class="row g-3 align-items-center justify-content-center mb-5">
        <div class="col-md-6">
            <label for="student_id" class="form-label visually-hidden">Select Student:</label>
            <select id="student_id" name="student_id" class="form-select form-select-lg" onchange="this.form.submit()" required>
                <option value="">-- Select Student to View Schedule --</option>
                <?php foreach ($students as $student): ?>
                    <option value="<?php echo $student['ID']; ?>" <?php echo ($selected_student_id == $student['ID']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($student['LastName'] . ', ' . $student['FirstName']); ?> (No: <?php echo $student['StudentNumber']; ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>
    
    <?php if ($selected_student_id): ?>
        
        <div class="card shadow-sm mb-5">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0">Weekly Grid for: <?php echo $student_name; ?></h4>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($weekly_schedule)): ?>
                    <div class="table-responsive">
                        <table class="schedule-table">
                            <thead>
                                <tr>
                                    <?php foreach ($days_of_week as $day): ?>
                                        <th><?php echo $day; ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php foreach ($days_of_week as $day): ?>
                                        <td>
                                            <?php if (isset($weekly_schedule[$day])): ?>
                                                <?php foreach ($weekly_schedule[$day] as $schedule): ?>
                                                    <div class="schedule-cell" style="background-color: <?php echo getColorBySubject($schedule['SubjectName']); ?>;">
                                                        <strong><?php echo htmlspecialchars($schedule['SubjectName']); ?></strong><br>
                                                        <small><?php echo formatTime12h($schedule['StartTime']); ?> - <?php echo formatTime12h($schedule['EndTime']); ?></small><br>
                                                        <small>Room: <?php echo htmlspecialchars($schedule['RoomName']); ?></small>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="no-class">No Class</div>
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning m-3 text-center">No scheduled classes found for this student.</div>
                <?php endif; ?>
            </div>
        </div>

        <h3 class="mb-3 text-secondary">Student Schedule List (with Actions)</h3>

        <div class="table-responsive">
            <table class="table table-striped table-hover shadow-sm">
                <thead class="table-secondary">
                    <tr>
                        <th>Subject</th> 
                        <th>Day of Week</th>
                        <th>Time Slot</th>
                        <th>Room</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($full_schedule_list)): ?>
                        <?php foreach($full_schedule_list as $schedule): ?>
                            <tr>
                                <td><span class="badge bg-primary"><?php echo htmlspecialchars($schedule['SubjectName']); ?></span></td>
                                <td><?php echo htmlspecialchars($schedule['DayOfWeek']); ?></td>
                                <td><?php echo formatTime12h($schedule['StartTime']) . ' - ' . formatTime12h($schedule['EndTime']); ?></td>
                                <td><?php echo htmlspecialchars($schedule['RoomName']); ?></td>
                                <td>
                                    <form method='POST' onsubmit="return confirm('Are you sure you want to delete the schedule entry for <?php echo htmlspecialchars($schedule['SubjectName']); ?> on <?php echo htmlspecialchars($schedule['DayOfWeek']); ?>?');">
                                        <input type='hidden' name='delete_schedule_id' value='<?php echo $schedule['StudentScheduleID']; ?>'>
                                        <input type='hidden' name='student_id_hidden' value='<?php echo htmlspecialchars($selected_student_id); ?>'>
                                        <button type='submit' class='btn btn-sm btn-danger'>Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                         <tr><td colspan='5' class='text-center'><div class='alert alert-info m-0'>No scheduled classes found for this student.</div></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    <?php else: ?>
        <p class="text-center text-muted">Please select a student from the dropdown above to view their weekly schedule.</p>
    <?php endif; ?>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>