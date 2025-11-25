<?php 
include "db.php"; 
checkLogin();

$message = "";
$messageType = "";

// Get departments for dropdown
$departments = mysqli_query($conn, "SELECT * FROM department");

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $studentID = mysqli_real_escape_string($conn, $_POST['studentID']);
    $departmentID = mysqli_real_escape_string($conn, $_POST['departmentID']);
    $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
    $middleName = mysqli_real_escape_string($conn, $_POST['middleName']);
    $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $sex = mysqli_real_escape_string($conn, $_POST['sex']);
    $yearLevel = mysqli_real_escape_string($conn, $_POST['yearLevel']);
    $schoolID = mysqli_real_escape_string($conn, $_POST['schoolID']);
    
    // Check if student ID already exists
    $check = mysqli_query($conn, "SELECT * FROM student WHERE studentID = '$studentID'");
    if(mysqli_num_rows($check) > 0) {
        $message = "Student ID already exists!";
        $messageType = "error";
    } else {
        $query = "INSERT INTO student (studentID, departmentID, firstName, middleName, lastName, dob, sex, yearLevel, schoolID) 
                  VALUES ('$studentID', '$departmentID', '$firstName', '$middleName', '$lastName', '$dob', '$sex', '$yearLevel', '$schoolID')";
        
        if(mysqli_query($conn, $query)) {
            $message = "Student added successfully!";
            $messageType = "success";
        } else {
            $message = "Error: " . mysqli_error($conn);
            $messageType = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Add Student - KAMUKHA!</title>
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
    input[type="text"],
    input[type="date"],
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
    <a href="student_list.php">Students</a>
  </div>
</nav>

<div class="container">
  <h2>Add New Student</h2>

  <?php if($message): ?>
    <div class="message <?php echo $messageType; ?>" id="message"><?php echo $message; ?></div>
  <?php endif; ?>

  <form method="POST" action="" onsubmit="return validateForm()">
    
    <div class="form-group">
      <label>Student ID: <span class="required">*</span></label>
      <input type="number" name="studentID" id="studentID" required>
    </div>

    <div class="form-group">
      <label>Department: <span class="required">*</span></label>
      <select name="departmentID" id="departmentID" required>
        <option value="">Select Department</option>
        <?php while($dept = mysqli_fetch_assoc($departments)): ?>
          <option value="<?php echo $dept['departmentID']; ?>"><?php echo $dept['departmentName']; ?></option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="form-group">
      <label>First Name: <span class="required">*</span></label>
      <input type="text" name="firstName" id="firstName" required>
    </div>

    <div class="form-group">
      <label>Middle Name:</label>
      <input type="text" name="middleName" id="middleName">
    </div>

    <div class="form-group">
      <label>Last Name: <span class="required">*</span></label>
      <input type="text" name="lastName" id="lastName" required>
    </div>

    <div class="form-group">
      <label>Date of Birth: <span class="required">*</span></label>
      <input type="date" name="dob" id="dob" required>
    </div>

    <div class="form-group">
      <label>Sex: <span class="required">*</span></label>
      <select name="sex" id="sex" required>
        <option value="">Select Sex</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
      </select>
    </div>

    <div class="form-group">
      <label>Year Level: <span class="required">*</span></label>
      <select name="yearLevel" id="yearLevel" required>
        <option value="">Select Year</option>
        <option value="1">1st Year</option>
        <option value="2">2nd Year</option>
        <option value="3">3rd Year</option>
        <option value="4">4th Year</option>
      </select>
    </div>

    <div class="form-group">
      <label>School ID: <span class="required">*</span></label>
      <input type="number" name="schoolID" id="schoolID" required>
    </div>

    <button class="btn" type="submit">Save Student</button>
  </form>

</div>

<script>
  function validateForm() {
    const studentID = document.getElementById('studentID').value;
    const firstName = document.getElementById('firstName').value.trim();
    const lastName = document.getElementById('lastName').value.trim();
    const dob = document.getElementById('dob').value;
    
    if(studentID.length < 4) {
      showMessage('Student ID must be at least 4 digits', 'error');
      return false;
    }
    
    if(firstName.length < 2) {
      showMessage('First name must be at least 2 characters', 'error');
      return false;
    }
    
    if(lastName.length < 2) {
      showMessage('Last name must be at least 2 characters', 'error');
      return false;
    }
    
    // Validate age (must be at least 15 years old)
    const birthDate = new Date(dob);
    const today = new Date();
    const age = today.getFullYear() - birthDate.getFullYear();
    
    if(age < 15) {
      showMessage('Student must be at least 15 years old', 'error');
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
  }
</script>

</body>
</html>