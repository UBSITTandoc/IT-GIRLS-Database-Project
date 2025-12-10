<?php require_once 'db_connect.php'; 

// Function to safely redirect
function redirect_to($url) {
    header("Location: $url");
    exit();
}

$success_message = "";
$error_message = "";

$student_id = isset($_GET['id']) ? $_GET['id'] : null;

// --- 1. Handle Delete Request (via POST to this page) ---
if (isset($_POST['delete_student_id'])) {
    $id = $conn->real_escape_string($_POST['delete_student_id']);
    
    // Deleting the student record. Due to CASCADE ON DELETE constraints:
    // This action will also automatically delete all associated records in:
    // - student_subject_schedule
    // - enrollment
    // - attendance
    $sql = "DELETE FROM student WHERE ID = '$id'";
    
    if ($conn->query($sql)) {
        // Redirect to the master list page after successful deletion
        redirect_to("view_students.php?status=deleted");
    } else {
        // Redirect with error message
        redirect_to("view_students.php?status=error&message=" . urlencode("Error deleting student: " . $conn->error));
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-4">
        
        <?php

        if (!$student_id) {
            echo "<div class='alert alert-danger'>No Student ID provided.</div>";
        } else {
            $sql = "SELECT s.*, d.DepartmentName 
                    FROM student s 
                    LEFT JOIN department d ON s.DepartmentID = d.DepartmentID 
                    WHERE s.ID = " . $conn->real_escape_string($student_id);
            
            $result = $conn->query($sql);
            $student = $result->fetch_assoc();

            if (!$student) {
                echo "<div class='alert alert-danger'>Student not found.</div>";
            } else {
                ?>
                <h2 class="mb-4 text-success">👤 Student Full Profile</h2>
                <div class="card shadow-lg">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0"><?php echo htmlspecialchars($student['LastName'] . ', ' . $student['FirstName'] . ' ' . $student['MiddleName'] . ' ' . $student['Suffix']); ?></h4>
                            <small>Student Number: <?php echo htmlspecialchars($student['StudentNumber']); ?></small>
                        </div>
                        
                        <form method='POST' onsubmit="return confirm('WARNING: Deleting this student will PERMANENTLY remove the student and ALL their associated schedules, enrollments, and attendance records. Are you absolutely sure?');">
                            <input type='hidden' name='delete_student_id' value='<?php echo $student['ID']; ?>'>
                            <button type='submit' class='btn btn-danger'>Delete Student</button>
                        </form>
                    </div>
                    <div class="card-body">
                        
                        <a href="view_students.php" class="btn btn-outline-secondary mb-4">← Back to Master List</a>

                        <div class="row">
                            <div class="col-md-6 border-end">
                                <h5 class="text-primary mb-3">Academic & Personal Information</h5>
                                <table class="table table-sm table-borderless">
                                    <tr><th>Department:</th><td><?php echo htmlspecialchars(isset($student['DepartmentName']) ? $student['DepartmentName'] : 'N/A'); ?></td></tr>
                                    <tr><th>Year Level:</th><td><?php echo htmlspecialchars(isset($student['YearLevel']) ? $student['YearLevel'] : 'N/A'); ?></td></tr>
                                    <tr><th>Date of Birth:</th><td><?php echo htmlspecialchars(isset($student['DateOfBirth']) ? $student['DateOfBirth'] : 'N/A'); ?></td></tr>
                                    <tr><th>Sex:</th><td><?php echo htmlspecialchars(isset($student['Sex']) ? $student['Sex'] : 'N/A'); ?></td></tr>
                                    <tr><th>Internal ID:</th><td><?php echo htmlspecialchars($student['ID']); ?></td></tr>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <h5 class="text-primary mb-3">Permanent Address</h5>
                                <table class="table table-sm table-borderless">
                                    <tr><th>House No./Street:</th><td><?php echo htmlspecialchars(isset($student['HouseNo']) ? $student['HouseNo'] : '') . ' ' . htmlspecialchars(isset($student['Street']) ? $student['Street'] : ''); ?></td></tr>
                                    <tr><th>Barangay:</th><td><?php echo htmlspecialchars(isset($student['Barangay']) ? $student['Barangay'] : 'N/A'); ?></td></tr>
                                    <tr><th>City/Municipality:</th><td><?php echo htmlspecialchars(isset($student['Municipality']) ? $student['Municipality'] : 'N/A'); ?></td></tr>
                                    <tr><th>Province:</th><td><?php echo htmlspecialchars(isset($student['Province']) ? $student['Province'] : 'N/A'); ?></td></tr>
                                    <tr><th>Country:</th><td><?php echo htmlspecialchars(isset($student['Country']) ? $student['Country'] : 'N/A'); ?></td></tr>
                                    <tr><th>ZIP Code:</th><td><?php echo htmlspecialchars(isset($student['ZIPCode']) ? $student['ZIPCode'] : 'N/A'); ?></td></tr>
                                </table>
                            </div>
                        </div>

                        <h5 class="text-primary mt-4 mb-3">Current Schedule</h5>
                        <?php 
                        // Fetch schedules for this student
                        $sched_sql = "SELECT sub.SubjectName, r.RoomName, sss.DayOfWeek, sss.StartTime, sss.EndTime 
                                      FROM student_subject_schedule sss 
                                      JOIN subject sub ON sss.SubjectID = sub.SubjectID 
                                      JOIN room r ON sss.RoomID = r.RoomID 
                                      WHERE sss.StudentID = " . $conn->real_escape_string($student['ID']) . "
                                      ORDER BY FIELD(sss.DayOfWeek, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), sss.StartTime ASC";
                        $scheds = $conn->query($sched_sql);
                        
                        if ($scheds->num_rows > 0) {
                            echo '<table class="table table-striped table-sm">';
                            echo '<thead><tr><th>Subject</th><th>Day</th><th>Time</th><th>Room</th></tr></thead>';
                            echo '<tbody>';
                            while($sch = $scheds->fetch_assoc()) {
                                $time_display = date("h:i A", strtotime($sch['StartTime'])) . " - " . date("h:i A", strtotime($sch['EndTime']));
                                echo "<tr><td>{$sch['SubjectName']}</td><td>{$sch['DayOfWeek']}</td><td>{$time_display}</td><td>{$sch['RoomName']}</td></tr>";
                            }
                            echo '</tbody></table>';
                        } else {
                            echo "<div class='alert alert-info'>This student has no scheduled classes.</div>";
                        }
                        ?>

                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>