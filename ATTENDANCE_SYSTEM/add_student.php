<?php require_once 'db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-4 mb-5">
        <div class="card shadow-lg">
            <div class="card-header bg-success text-white">
                <h2 class="mb-0">📝 Register New Student (Complete Profile)</h2>
            </div>
            <div class="card-body">
                <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    // --- 1. Sanitize and capture all 15 variables ---
                    $student_num = $conn->real_escape_string($_POST['student_num']);
                    $dept_id = $conn->real_escape_string($_POST['dept_id']);
                    $fname = $conn->real_escape_string($_POST['fname']);
                    $mname = $conn->real_escape_string($_POST['mname']); // New
                    $lname = $conn->real_escape_string($_POST['lname']);
                    $suffix = $conn->real_escape_string($_POST['suffix']); // New
                    $dob = $conn->real_escape_string($_POST['dob']); // New
                    $sex = $conn->real_escape_string($_POST['sex']);
                    $year_level = $conn->real_escape_string($_POST['year_level']); // New
                    $house_no = $conn->real_escape_string($_POST['house_no']); // New
                    $street = $conn->real_escape_string($_POST['street']); // New
                    $barangay = $conn->real_escape_string($_POST['barangay']); // New
                    $municipality = $conn->real_escape_string($_POST['municipality']); // New
                    $province = $conn->real_escape_string($_POST['province']); // New
                    $country = $conn->real_escape_string($_POST['country']); // New
                    $zip_code = $conn->real_escape_string($_POST['zip_code']); // New
                    
                    // --- 2. Build the INSERT query ---
                    $sql = "INSERT INTO student (
                                StudentNumber, DepartmentID, FirstName, MiddleName, LastName, Suffix, 
                                DateOfBirth, Sex, YearLevel, HouseNo, Street, Barangay, 
                                Municipality, Province, Country, ZIPCode
                            ) VALUES (
                                '$student_num', '$dept_id', '$fname', '$mname', '$lname', '$suffix', 
                                '$dob', '$sex', '$year_level', '$house_no', '$street', '$barangay', 
                                '$municipality', '$province', '$country', '$zip_code'
                            )";
                            
                    if($conn->query($sql)) {
                        echo "<div class='alert alert-success'>Student <strong>{$student_num} - {$lname}, {$fname}</strong> Registered Successfully!</div>";
                    } else {
                        echo "<div class='alert alert-danger'><strong>Error registering student:</strong> " . $conn->error . "</div>";
                        echo "<div class='alert alert-info'>Please ensure all columns listed in the schema are present in your <code>student</code> table.</div>";
                    }
                }
                
                $depts = $conn->query("SELECT * FROM department ORDER BY DepartmentName");
                ?>

                <form method="POST">
                    <h5 class="mt-3 text-primary">Academic & Personal Information</h5>
                    <hr class="mt-0">
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label for="student_num" class="form-label">Student Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="student_num" name="student_num" required placeholder="e.g., 20232551">
                        </div>
                        <div class="col-md-5">
                            <label for="dept_id" class="form-label">Department <span class="text-danger">*</span></label>
                            <select class="form-select" id="dept_id" name="dept_id" required>
                                <option value="">Select Department</option>
                                <?php while($r = $depts->fetch_assoc()) { 
                                    echo "<option value='{$r['DepartmentID']}'>{$r['DepartmentName']}</option>"; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="year_level" class="form-label">Year Level</label>
                            <input type="number" class="form-control" id="year_level" name="year_level" min="1" max="6">
                        </div>
                        
                        <div class="col-md-3">
                            <label for="lname" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="lname" name="lname" required>
                        </div>
                        <div class="col-md-3">
                            <label for="fname" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="fname" name="fname" required>
                        </div>
                        <div class="col-md-3">
                            <label for="mname" class="form-label">Middle Name</label>
                            <input type="text" class="form-control" id="mname" name="mname">
                        </div>
                        <div class="col-md-3">
                            <label for="suffix" class="form-label">Suffix (Sr., Jr., III, etc.)</label>
                            <input type="text" class="form-control" id="suffix" name="suffix">
                        </div>

                        <div class="col-md-3">
                            <label for="sex" class="form-label">Sex</label>
                            <select class="form-select" id="sex" name="sex">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="dob" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="dob" name="dob">
                        </div>
                    </div>

                    <h5 class="mt-3 text-primary">Permanent Address</h5>
                    <hr class="mt-0">
                    <div class="row g-3 mb-4">
                        <div class="col-md-2">
                            <label for="house_no" class="form-label">House No.</label>
                            <input type="text" class="form-control" id="house_no" name="house_no">
                        </div>
                        <div class="col-md-3">
                            <label for="street" class="form-label">Street</label>
                            <input type="text" class="form-control" id="street" name="street">
                        </div>
                        <div class="col-md-3">
                            <label for="barangay" class="form-label">Barangay</label>
                            <input type="text" class="form-control" id="barangay" name="barangay">
                        </div>
                        <div class="col-md-4">
                            <label for="municipality" class="form-label">Municipality / City</label>
                            <input type="text" class="form-control" id="municipality" name="municipality">
                        </div>
                        <div class="col-md-4">
                            <label for="province" class="form-label">Province</label>
                            <input type="text" class="form-control" id="province" name="province">
                        </div>
                        <div class="col-md-4">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" class="form-control" id="country" name="country">
                        </div>
                        <div class="col-md-4">
                            <label for="zip_code" class="form-label">ZIP Code</label>
                            <input type="text" class="form-control" id="zip_code" name="zip_code">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success btn-lg mt-3 w-100">Register Complete Student Profile</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>