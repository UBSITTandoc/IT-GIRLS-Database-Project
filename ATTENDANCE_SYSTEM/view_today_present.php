<?php
require_once 'db_connect.php';

$today = date('Y-m-d');

// Fetch all unique students marked as 'Present' today, including department info
$sql_present = "
    SELECT DISTINCT
        d.DepartmentName,
        s.StudentNumber,
        s.FirstName,
        s.LastName
    FROM attendance a
    JOIN student s ON a.StudentID = s.ID
    LEFT JOIN department d ON s.DepartmentID = d.DepartmentID
    WHERE a.Date = '{$today}' AND a.Status = 'Present'
    ORDER BY d.DepartmentName ASC, s.LastName ASC, s.FirstName ASC
";
$result = $conn->query($sql_present);

$present_students_by_dept = [];
$total_present_count = 0;

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Handle students without a department
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
    <title>Today's Present Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-4">
        <h2 class="mb-4 text-center text-success">✅ Today's Present Students Report</h2>
        <p class="text-center text-muted">Date: <?php echo date('F j, Y', strtotime($today)); ?></p>
        <hr>
        
        <div class="alert alert-success text-center">
            <h4 class="mb-0">Total Present Students: <?php echo $total_present_count; ?></h4>
        </div>

        <?php if (empty($present_students_by_dept)): ?>
            <div class="alert alert-warning text-center">No students have been marked as Present today.</div>
        <?php else: ?>
            
            <?php foreach ($present_students_by_dept as $dept_name => $students): ?>
                
                <div class="card mb-4 shadow-sm border-success">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><?php echo htmlspecialchars($dept_name); ?></h4>
                        <span class="badge bg-light text-success fs-6"><?php echo count($students); ?> Present</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 15%;">Student No.</th>
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
            <?php endforeach; ?>

        <?php endif; ?>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>