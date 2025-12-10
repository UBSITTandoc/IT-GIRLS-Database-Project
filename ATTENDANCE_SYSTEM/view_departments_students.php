<?php
require_once 'db_connect.php';

// Fetch all departments
$sql_departments = "SELECT DepartmentID, DepartmentName FROM department ORDER BY DepartmentName ASC";
$departments_result = $conn->query($sql_departments);
$departments = [];
if ($departments_result->num_rows > 0) {
    while ($row = $departments_result->fetch_assoc()) {
        $departments[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Departments and Enrolled Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-4">
        <h2 class="mb-4 text-center text-primary">🏢 Departments and Enrolled Students</h2>
        <p class="text-center text-muted">A complete breakdown of students registered under each department.</p>
        <hr>

        <?php if (empty($departments)): ?>
            <div class="alert alert-warning text-center">No departments have been registered yet.</div>
        <?php else: ?>
            
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
                ?>
                
                <div class="card mb-4 shadow-sm border-primary">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><?php echo $dept_name; ?></h4>
                        <span class="badge bg-light text-primary fs-6"><?php echo $student_count; ?> Students Enrolled</span>
                    </div>
                    <div class="card-body">
                        <?php if ($student_count > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width: 15%;">Student No.</th>
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
                            <div class="alert alert-info mb-0">No students are currently enrolled in the <?php echo $dept_name; ?> department.</div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php endif; ?>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>