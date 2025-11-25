<?php
include "db.php";

header('Content-Type: application/json');

if(isset($_GET['id'])) {
    $studentID = mysqli_real_escape_string($conn, $_GET['id']);
    
    $query = "SELECT s.*, d.departmentName 
              FROM student s 
              LEFT JOIN department d ON s.departmentID = d.departmentID 
              WHERE s.studentID = '$studentID'";
    
    $result = mysqli_query($conn, $query);
    
    if($result && mysqli_num_rows($result) == 1) {
        $student = mysqli_fetch_assoc($result);
        echo json_encode([
            'found' => true,
            'student' => $student
        ]);
    } else {
        echo json_encode([
            'found' => false
        ]);
    }
} else {
    echo json_encode([
        'error' => 'No ID provided'
    ]);
}
?>