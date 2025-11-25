<?php
include "db.php";
checkLogin();

$message = "";
$messageType = "";

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $studentID = mysqli_real_escape_string($conn, $_POST['studentID']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $timeIn = mysqli_real_escape_string($conn, $_POST['timeIn']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    // Check if student exists
    $checkStudent = mysqli_query($conn, "SELECT * FROM student WHERE studentID = '$studentID'");
    if(mysqli_num_rows($checkStudent) == 0) {
        $message = "Student ID not found!";
        $messageType = "error";
    } else {
        // Check if attendance already exists for this student on this date
        $checkAttendance = mysqli_query($conn, "SELECT * FROM attendance WHERE studentID = '$studentID' AND date = '$date'");
        if(mysqli_num_rows($checkAttendance) > 0) {
            $message = "Attendance already marked for this student today!";
            $messageType = "error";
        } else {
            $query = "INSERT INTO attendance (studentID, date, timeIn, status) 
                      VALUES ('$studentID', '$date', '$timeIn', '$status')";
            
            if(mysqli_query($conn, $query)) {
                $message = "Attendance marked successfully!";
                $messageType = "success";
            } else {
                $message = "Error: " . mysqli_error($conn);
                $messageType = "error";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Mark Attendance - KAMUKHA!</title>
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
    .container {
      width: 600px;
      margin: 40px auto;
      background: white;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    h2 {
      margin-bottom: 30px;
      color: #003846;
      text-align: center;
    }
    .form-group {
      margin-bottom: 20px;
    }
    label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: #003846;
    }
    input[type="number"],
    input[type="date"],
    input[type="time"],
    select {
      width: 100%;
      padding: 12px;
      border: 2px solid #e0e0e0;
      border-radius: 6px;
      font-size: 14px;
      transition: border-color 0.3s;
    }
    input:focus, select:focus {
      outline: none;
      border-color: #00A86B;
    }
    .btn {
      background: #00A86B;
      padding: 12px 25px;
      border: none;
      border-radius: 6px;
      color: white;
      cursor: pointer;
      font-size: 16px;
      font-weight: bold;
      width: 100%;
      transition: background 0.3s;
    }
    .btn:hover {
      background: #008f5a;
    }
    .message {
      padding: 12px;
      border-radius: 6px;
      margin-bottom: 20px;
      text-align: center;
      font-weight: 600;
    }
    .success {
      background: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }
    .error {
      background: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }
    .student-info {
      background: #e3f2fd;
      padding: 15px;
      border-radius: 6px;
      margin-bottom: 20px;
      display: none;
    }
    .student-info h3 {
      margin-bottom: 10px;
      color: #1976d2;
    }
    .info-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
    }
    .info-item {
      font-size: 14px;
    }
    .info-label {
      font-weight: 600;
      color: #555;
    }
    .required {
      color: #d32f2f;
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
</nav>

<div class="container">
  <h2>Mark Attendance</h2>

  <?php if($message): ?>
    <div class="message <?php echo $messageType; ?>" id="message"><?php echo $message; ?></div>
  <?php endif; ?>

  <form method="POST" action="" onsubmit="return validateForm()">
    
    <div class="form-group">
      <label>Student ID: <span class="required">*</span></label>
      <input type="number" name="studentID" id="studentID" required onblur="checkStudent()">
    </div>

    <div id="studentInfo" class="student-info"></div>

    <div class="form-group">
      <label>Date: <span class="required">*</span></label>
      <input type="date" name="date" id="date" value="<?php echo date('Y-m-d'); ?>" required>
    </div>

    <div class="form-group">
      <label>Time In: <span class="required">*</span></label>
      <input type="time" name="timeIn" id="timeIn" value="<?php echo date('H:i'); ?>" required>
    </div>

    <div class="form-group">
      <label>Status: <span class="required">*</span></label>
      <select name="status" id="status" required>
        <option value="">Select Status</option>
        <option value="Present">Present</option>
        <option value="Late">Late</option>
        <option value="Absent">Absent</option>
      </select>
    </div>

    <button class="btn" type="submit">Mark Attendance</button>
  </form>

</div>

<script>
  function checkStudent() {
    const studentID = document.getElementById('studentID').value;
    
    if(!studentID) return;
    
    // Use fetch API to check if student exists
    fetch('check_student.php?id=' + studentID)
      .then(response => response.json())
      .then(data => {
        if(data.found) {
          showStudentInfo(data.student);
        } else {
          hideStudentInfo();
        }
      })
      .catch(error => {
        console.error('Error:', error);
      });
  }
  
  function showStudentInfo(student) {
    const infoDiv = document.getElementById('studentInfo');
    infoDiv.style.display = 'block';
    infoDiv.innerHTML = `
      <h3>Student Found</h3>
      <div class="info-grid">
        <div class="info-item">
          <span class="info-label">Name:</span> ${student.firstName} ${student.lastName}
        </div>
        <div class="info-item">
          <span class="info-label">Department:</span> ${student.departmentName || 'N/A'}
        </div>
        <div class="info-item">
          <span class="info-label">Year Level:</span> ${student.yearLevel}
        </div>
        <div class="info-item">
          <span class="info-label">Sex:</span> ${student.sex}
        </div>
      </div>
    `;
    
    // Auto-fill status based on time
    autoFillStatus();
  }
  
  function hideStudentInfo() {
    document.getElementById('studentInfo').style.display = 'none';
  }
  
  function autoFillStatus() {
    const timeIn = document.getElementById('timeIn').value;
    const statusSelect = document.getElementById('status');
    
    if(timeIn) {
      const time = timeIn.split(':');
      const hour = parseInt(time[0]);
      const minute = parseInt(time[1]);
      
      // Assume classes start at 8:00 AM
      // Present: Before 8:00
      // Late: 8:00 - 8:30
      // Absent: After 8:30
      
      if(hour < 8) {
        statusSelect.value = 'Present';
      } else if(hour == 8 && minute <= 30) {
        statusSelect.value = 'Late';
      } else {
        statusSelect.value = 'Absent';
      }
    }
  }
  
  function validateForm() {
    const studentID = document.getElementById('studentID').value;
    const date = document.getElementById('date').value;
    const timeIn = document.getElementById('timeIn').value;
    const status = document.getElementById('status').value;
    
    if(!studentID || !date || !timeIn || !status) {
      showMessage('Please fill in all required fields', 'error');
      return false;
    }
    
    return true;
  }
  
  function showMessage(text, type) {
    const messageDiv = document.createElement('div');
    messageDiv.className = 'message ' + type;
    messageDiv.textContent = text;
    
    const form = document.querySelector('form');
    const existingMsg = document.getElementById('message');
    
    if(existingMsg) {
      existingMsg.remove();
    }
    
    form.parentNode.insertBefore(messageDiv, form);
    
    setTimeout(() => {
      messageDiv.style.opacity = '0';
      messageDiv.style.transition = 'opacity 0.5s';
      setTimeout(() => messageDiv.remove(), 500);
    }, 3000);
  }
  
  // Auto-hide message
  window.onload = function() {
    const message = document.getElementById('message');
    if(message) {
      setTimeout(() => {
        message.style.opacity = '0';
        message.style.transition = 'opacity 0.5s';
        setTimeout(() => message.remove(), 500);
      }, 3000);
    }
    
    // Auto-fill status when time changes
    document.getElementById('timeIn').addEventListener('change', autoFillStatus);
  }
</script>

</body>
</html>