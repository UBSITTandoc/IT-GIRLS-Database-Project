<?php 
include "db.php"; 
checkLogin();

// Get statistics
$totalStudents = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM student"))['count'];
$totalDepartments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM department"))['count'];
$todayAttendance = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM attendance WHERE date = CURDATE()"))['count'];
$presentToday = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM attendance WHERE date = CURDATE() AND status = 'Present'"))['count'];
?>
<!DOCTYPE html>
<html>
<head>
  <title>Dashboard - KAMUKHA!</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background: #E8FFF1;
      color: #003846;
    }
    nav {
      background: #003846;
      padding: 15px 40px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      color: white;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    nav strong {
      font-size: 24px;
      letter-spacing: 1px;
    }
    nav div a {
      color: white;
      margin-left: 20px;
      text-decoration: none;
      font-weight: bold;
      transition: all 0.3s;
      padding: 8px 15px;
      border-radius: 5px;
    }
    nav div a:hover {
      background: #00A86B;
    }
    .user-info {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    .logout-btn {
      background: #d32f2f;
      padding: 8px 20px;
      border: none;
      border-radius: 5px;
      color: white;
      cursor: pointer;
      font-weight: bold;
      transition: background 0.3s;
    }
    .logout-btn:hover {
      background: #b71c1c;
    }
    .container {
      padding: 40px;
      max-width: 1200px;
      margin: 0 auto;
    }
    h1 {
      margin-bottom: 30px;
      color: #003846;
    }
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      margin-bottom: 40px;
    }
    .stat-card {
      background: white;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 3px 15px rgba(0,0,0,0.1);
      transition: transform 0.3s, box-shadow 0.3s;
      cursor: pointer;
    }
    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 25px rgba(0,0,0,0.15);
    }
    .stat-number {
      font-size: 48px;
      font-weight: bold;
      color: #00A86B;
      margin-bottom: 10px;
    }
    .stat-label {
      color: #666;
      font-size: 14px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    .quick-actions {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 15px;
    }
    .action-btn {
      background: linear-gradient(135deg, #00A86B, #008f5a);
      color: white;
      padding: 20px;
      border: none;
      border-radius: 10px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s;
      text-decoration: none;
      display: block;
      text-align: center;
    }
    .action-btn:hover {
      transform: scale(1.05);
      box-shadow: 0 5px 20px rgba(0,168,107,0.3);
    }
    h2 {
      margin: 30px 0 20px 0;
      color: #003846;
    }
  </style>
</head>
<body>

<nav>
  <strong>KAMUKHA!</strong>
  <div>
    <a href="dashboard.php">Dashboard</a>
    <a href="attendance_list.php">Attendance</a>
    <a href="add_student.php">Add Student</a>
    <a href="student_list.php">Students</a>
  </div>
  <div class="user-info">
    <span>Welcome, <?php echo $_SESSION['admin_name']; ?></span>
    <button class="logout-btn" onclick="logout()">Logout</button>
  </div>
</nav>

<div class="container">
  <h1>Dashboard Overview</h1>

  <div class="stats-grid">
    <div class="stat-card" onclick="location.href='student_list.php'">
      <div class="stat-number"><?php echo $totalStudents; ?></div>
      <div class="stat-label">Total Students</div>
    </div>
    
    <div class="stat-card">
      <div class="stat-number"><?php echo $totalDepartments; ?></div>
      <div class="stat-label">Departments</div>
    </div>
    
    <div class="stat-card" onclick="location.href='attendance_list.php'">
      <div class="stat-number"><?php echo $todayAttendance; ?></div>
      <div class="stat-label">Today's Records</div>
    </div>
    
    <div class="stat-card">
      <div class="stat-number"><?php echo $presentToday; ?></div>
      <div class="stat-label">Present Today</div>
    </div>
  </div>

  <h2>Quick Actions</h2>
  <div class="quick-actions">
    <a href="add_student.php" class="action-btn">➕ Add Student</a>
    <a href="mark_attendance.php" class="action-btn">✓ Mark Attendance</a>
    <a href="attendance_list.php" class="action-btn">📊 View Records</a>
    <a href="student_list.php" class="action-btn">👥 Manage Students</a>
  </div>
</div>

<script>
  function logout() {
    if(confirm('Are you sure you want to logout?')) {
      window.location.href = 'logout.php';
    }
  }
  
  // Add animation on load
  window.onload = function() {
    const cards = document.querySelectorAll('.stat-card');
    cards.forEach((card, index) => {
      card.style.opacity = '0';
      card.style.transform = 'translateY(20px)';
      setTimeout(() => {
        card.style.transition = 'all 0.5s ease';
        card.style.opacity = '1';
        card.style.transform = 'translateY(0)';
      }, index * 100);
    });
  }
</script>

</body>
</html>