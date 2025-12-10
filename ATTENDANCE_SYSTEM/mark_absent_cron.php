<?php 
require_once 'db_connect.php'; 

// Set Timezone to Asia/Manila for accurate comparison against EndTime
date_default_timezone_set('Asia/Manila');

$today = date('Y-m-d');
$time_now = date('H:i:s');
$results = [
    'date' => $today,
    'current_time' => $time_now,
    'absent_students_marked' => 0,
    'errors' => []
];

// 1. Find all student schedules for today that have already ended
// This query selects schedules for today's day of the week AND whose EndTime is less than or equal to the current time.
$sql_ended_schedules = "
    SELECT 
        sss.StudentScheduleID, 
        sss.StudentID, 
        sss.SubjectID, 
        sss.EndTime 
    FROM student_subject_schedule sss
    WHERE sss.DayOfWeek = DATE_FORMAT(NOW(), '%W') 
      AND sss.EndTime <= '{$time_now}'
";

$ended_schedules_result = $conn->query($sql_ended_schedules);

if ($ended_schedules_result === FALSE) {
    $results['errors'][] = "SQL Error during schedule query: " . $conn->error;
} elseif ($ended_schedules_result->num_rows > 0) {
    
    while ($schedule = $ended_schedules_result->fetch_assoc()) {
        $sched_id = $schedule['StudentScheduleID'];
        $student_id = $schedule['StudentID'];
        $subject_id = $schedule['SubjectID'];

        // 2. Check if the student already has an attendance record for this schedule today
        // This includes Present and Late records, or records already marked Absent
        $check_attendance_sql = "
            SELECT AttendanceID 
            FROM attendance 
            WHERE StudentID = '{$student_id}' 
              AND StudentScheduleID = '{$sched_id}' 
              AND Date = '{$today}'
        ";
        
        $check_result = $conn->query($check_attendance_sql);

        // 3. If NO record exists, mark them Absent
        if ($check_result->num_rows == 0) {
            $insert_absent_sql = "
                INSERT INTO attendance (StudentScheduleID, StudentID, SubjectID, Date, TimeIn, Status) 
                VALUES ('{$sched_id}', '{$student_id}', '{$subject_id}', '{$today}', NULL, 'Absent')
            ";
            
            if ($conn->query($insert_absent_sql)) {
                $results['absent_students_marked']++;
            } else {
                $results['errors'][] = "Error marking student {$student_id} (Schedule {$sched_id}) absent: " . $conn->error;
            }
        }
    }
} else {
    $results['message'] = "No schedules have ended yet today at {$time_now} or no schedules found for " . date('l') . ".";
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Absent Script</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Attendance Automation Status</h2>
        
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                Execution Details
            </div>
            <div class="card-body">
                <p><strong>Date:</strong> <?php echo htmlspecialchars($results['date']); ?></p>
                <p><strong>Time of Execution:</strong> <?php echo htmlspecialchars($results['current_time']); ?></p>
                <hr>
                <?php if (!empty($results['errors'])): ?>
                    <div class="alert alert-danger">
                        <h4>Errors Encountered:</h4>
                        <ul>
                            <?php foreach ($results['errors'] as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ($results['absent_students_marked'] > 0): ?>
                    <div class="alert alert-success">
                        <h4 class="alert-heading">Success!</h4>
                        <p>Total students marked as **Absent**: <strong><?php echo $results['absent_students_marked']; ?></strong></p>
                        <p>These were students who missed an already finished class today.</p>
                    </div>
                <?php elseif (isset($results['message'])): ?>
                    <div class="alert alert-info">
                        <?php echo htmlspecialchars($results['message']); ?>
                    </div>
                <?php else: ?>
                     <div class="alert alert-success">
                        <h4 class="alert-heading">Check Complete!</h4>
                        <p>No new students were marked absent. All finished schedules either had a present record or were already marked absent.</p>
                    </div>
                <?php endif; ?>

                <a href="view_attendance.php" class="btn btn-secondary mt-3">View Full Attendance Records</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>