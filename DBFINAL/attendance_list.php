<?php
include "db.php";
$result = mysqli_query($conn, "SELECT * FROM attendance");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Attendance Records - KAMÜKHA!</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<nav>
  <strong>KAMÜKHA!</strong>
  <div>
    <a href="dashboard.php">Dashboard</a>
    <a href="add_student.php">Add Student</a>
  </div>
</nav>

<div style="padding:30px;">
  <h2>Attendance Records</h2>

  <table border="1" cellpadding="10" style="border-collapse:collapse; margin-top:20px;">
    <tr>
      <th>Student ID</th>
      <th>Date</th>
      <th>Time In</th>
      <th>Time Out</th>
      <th>Status</th>
    </tr>

    <?php while($row = mysqli_fetch_assoc($result)) { ?>
      <tr>
        <td><?php echo $row['studentID']; ?></td>
        <td><?php echo $row['date']; ?></td>
        <td><?php echo $row['timeIn']; ?></td>
        <td><?php echo $row['timeOut']; ?></td>
        <td><?php echo $row['status']; ?></td>
      </tr>
    <?php } ?>

  </table>
</div>

</body>
</html>
