<?php include "db.php"; ?>
<!DOCTYPE html>
<html>
<head>
  <title>KAMUKHA! - Automated Attendance System</title>
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
      font-size: 28px;
      letter-spacing: 2px;
    }
    nav a {
      color: white;
      margin-left: 20px;
      text-decoration: none;
      font-weight: bold;
      transition: all 0.3s;
      padding: 8px 15px;
      border-radius: 5px;
    }
    nav a:hover {
      background: #00A86B;
    }
    .hero {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 80px 60px;
      max-width: 1200px;
      margin: 0 auto;
      gap: 60px;
    }
    .hero-content {
      flex: 1;
      max-width: 600px;
    }
    h1 {
      font-size: 48px;
      margin-bottom: 20px;
      color: #003846;
      line-height: 1.2;
    }
    p {
      font-size: 18px;
      line-height: 1.6;
      color: #555;
      margin-bottom: 30px;
    }
    .btn {
      background: linear-gradient(135deg, #00A86B, #008f5a);
      padding: 15px 40px;
      border: none;
      border-radius: 8px;
      color: white;
      cursor: pointer;
      font-size: 18px;
      font-weight: bold;
      text-decoration: none;
      display: inline-block;
      transition: all 0.3s;
      box-shadow: 0 4px 15px rgba(0,168,107,0.3);
    }
    .btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 20px rgba(0,168,107,0.4);
    }
    .hero-image {
      flex: 1;
      text-align: center;
    }
    .logo-placeholder {
      width: 400px;
      height: 400px;
      background: white;
      border-radius: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 10px 40px rgba(0,0,0,0.1);
      font-size: 24px;
      color: #003846;
      font-weight: bold;
    }
    .features {
      background: white;
      padding: 60px;
      margin-top: 40px;
    }
    .features-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 30px;
      max-width: 1200px;
      margin: 0 auto;
    }
    .feature-card {
      background: #E8FFF1;
      padding: 30px;
      border-radius: 12px;
      text-align: center;
      transition: transform 0.3s;
    }
    .feature-card:hover {
      transform: translateY(-5px);
    }
    .feature-icon {
      font-size: 48px;
      margin-bottom: 15px;
    }
    .feature-card h3 {
      margin-bottom: 10px;
      color: #003846;
    }
    .feature-card p {
      font-size: 14px;
      color: #666;
    }
  </style>
</head>
<body>

<nav>
  <strong>KAMUKHA!</strong>
  <div>
    <a href="index.php">Home</a>
    <a href="login.php">Login</a>
  </div>
</nav>

<div class="hero">
  <div class="hero-content">
    <h1>Automated Attendance, Done Right.</h1>
    <p>KAMUKHA! uses facial recognition technology to make student attendance faster, more accurate, and error-free. Built specifically for Philippine Baptist Theological Seminary.</p>
    <a href="login.php" class="btn">Get Started →</a>
  </div>
  <div class="hero-image">
    <div class="logo-placeholder">
      KAMUKHA!<br>Logo
    </div>
  </div>
</div>

<div class="features">
  <div class="features-grid">
    <div class="feature-card">
      <div class="feature-icon">⚡</div>
      <h3>Fast & Efficient</h3>
      <p>Mark attendance in seconds with automated facial recognition</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon">✓</div>
      <h3>Accurate Tracking</h3>
      <p>Eliminate manual errors with automated data collection</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon">📊</div>
      <h3>Real-time Reports</h3>
      <p>Access attendance records and statistics instantly</p>
    </div>
  </div>
</div>

<script>
  // Add smooth scroll animation
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      document.querySelector(this.getAttribute('href')).scrollIntoView({
        behavior: 'smooth'
      });
    });
  });
  
  // Add fade-in animation on load
  window.onload = function() {
    const hero = document.querySelector('.hero');
    hero.style.opacity = '0';
    hero.style.transform = 'translateY(20px)';
    
    setTimeout(() => {
      hero.style.transition = 'all 1s ease';
      hero.style.opacity = '1';
      hero.style.transform = 'translateY(0)';
    }, 100);
  }
</script>

</body>
</html>