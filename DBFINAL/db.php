<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "kamukha_db";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if(!$conn){
    die("Database Connection Failed: " . mysqli_connect_error());
}

// Check if user is logged in (for protected pages)
function checkLogin() {
    if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header("Location: login.php");
        exit();
    }
}
?>