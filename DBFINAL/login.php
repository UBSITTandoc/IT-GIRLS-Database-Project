<?php 
include "db.php"; 

$error = "";

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['user']);
    $password = $_POST['pass'];
    
    $query = "SELECT * FROM admin WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    
    if($result && mysqli_num_rows($result) == 1) {
        $admin = mysqli_fetch_assoc($result);
        
        // For demo: Simple password check (you should use password_hash in production)
        if($password === "PBTS2024!" || password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['adminID'];
            $_SESSION['admin_name'] = $admin['fullName'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid password";
        }
    } else {
        $error = "Username not found";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login - KAMUKHA!</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background: linear-gradient(135deg, #E8FFF1 0%, #B8E6D5 100%);
      color: #003846;
      min-height: 100vh;
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
    nav a {
      color: white;
      margin-left: 20px;
      text-decoration: none;
      font-weight: bold;
      transition: color 0.3s;
    }
    nav a:hover {
      color: #00A86B;
    }
    .login-container {
      width: 400px;
      margin: 80px auto;
      background: white;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    h2 {
      color: #003846;
      margin-bottom: 25px;
      text-align: center;
    }
    .input-group {
      margin-bottom: 20px;
    }
    label {
      display: block;
      margin-bottom: 8px;
      color: #003846;
      font-weight: 600;
    }
    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 12px;
      border: 2px solid #e0e0e0;
      border-radius: 6px;
      font-size: 14px;
      transition: border-color 0.3s;
    }
    input[type="text"]:focus,
    input[type="password"]:focus {
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
      width: 100%;
      font-size: 16px;
      font-weight: bold;
      transition: background 0.3s;
    }
    .btn:hover {
      background: #008f5a;
    }
    .error {
      background: #ffe6e6;
      color: #d32f2f;
      padding: 12px;
      border-radius: 6px;
      margin-bottom: 20px;
      text-align: center;
      border: 1px solid #ffcccb;
    }
    .info-box {
      background: #e3f2fd;
      border: 1px solid #90caf9;
      padding: 15px;
      border-radius: 6px;
      margin-top: 20px;
      font-size: 13px;
    }
    .info-box strong {
      display: block;
      margin-bottom: 5px;
      color: #1976d2;
    }
  </style>
</head>
<body>

<nav>
  <strong>KAMUKHA!</strong>
  <a href="index.php">Home</a>
</nav>

<div class="login-container">
  <h2>Admin Login</h2>
  
  <?php if($error): ?>
    <div class="error" id="errorMsg"><?php echo $error; ?></div>
  <?php endif; ?>

  <form method="POST" action="" onsubmit="return validateForm()">
    <div class="input-group">
      <label>Username:</label>
      <input type="text" name="user" id="username" required>
    </div>

    <div class="input-group">
      <label>Password:</label>
      <input type="password" name="pass" id="password" required>
    </div>

    <button class="btn" type="submit">Sign In</button>
  </form>

  <div class="info-box">
    <strong>Demo Admin Account:</strong>
    Username: <code>admin</code><br>
    Password: <code>PBTS2024!</code>
  </div>
</div>

<script>
  function validateForm() {
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;
    
    if(username.length < 3) {
      showError('Username must be at least 3 characters');
      return false;
    }
    
    if(password.length < 6) {
      showError('Password must be at least 6 characters');
      return false;
    }
    
    return true;
  }
  
  function showError(message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error';
    errorDiv.id = 'errorMsg';
    errorDiv.textContent = message;
    
    const form = document.querySelector('form');
    const existingError = document.getElementById('errorMsg');
    
    if(existingError) {
      existingError.remove();
    }
    
    form.parentNode.insertBefore(errorDiv, form);
    
    setTimeout(() => {
      errorDiv.remove();
    }, 3000);
  }
  
  // Auto-hide error message after 5 seconds
  window.onload = function() {
    const errorMsg = document.getElementById('errorMsg');
    if(errorMsg) {
      setTimeout(() => {
        errorMsg.style.opacity = '0';
        errorMsg.style.transition = 'opacity 0.5s';
        setTimeout(() => errorMsg.remove(), 500);
      }, 5000);
    }
  }
</script>

</body>
</html>