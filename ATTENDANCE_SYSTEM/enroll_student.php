<?php require_once 'db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enroll Student Schedule</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-4">
        <h2 class="mb-4 text-primary">📚 Schedule Management</h2>

        <?php
        $success_message = "";
        $error_message = "";
        $active_tab = 'schedule-tab'; // Default tab

        // --- HANDLE POST SUBMISSIONS ---
        
        // 1. Handle Schedule Enrollment
        if (isset($_POST['add_schedule'])) {
            $student_id = $conn->real_escape_string($_POST['student_id']);
            $subject_id = $conn->real_escape_string($_POST['subject_id']);
            $room_id = $conn->real_escape_string($_POST['room_id']);
            $day = $conn->real_escape_string($_POST['day']);
            $start = $conn->real_escape_string($_POST['start_time']);
            $end = $conn->real_escape_string($_POST['end_time']);

            $sql = "INSERT INTO student_subject_schedule (StudentID, SubjectID, RoomID, DayOfWeek, StartTime, EndTime) 
                    VALUES ('$student_id', '$subject_id', '$room_id', '$day', '$start', '$end')";
            
            if ($conn->query($sql)) {
                $success_message = "Schedule Added Successfully!";
            } else {
                $error_message = "Error adding schedule (Check for duplicate entry for the same student/day/subject): " . $conn->error;
            }
        }
        
        // 2. Handle Add Department, Subject, Room (and set active tab)
        if (isset($_POST['add_department'])) {
            $dept = $conn->real_escape_string($_POST['department_name']);
            $sql = "INSERT INTO department (DepartmentName) VALUES ('$dept')";
            if ($conn->query($sql)) {
                $success_message = "Department '{$dept}' added successfully!";
            } else {
                $error_message = "Error adding department: " . $conn->error;
            }
            $active_tab = 'manage-tab'; // <--- SET ACTIVE TAB HERE
        }

        if (isset($_POST['add_subject'])) {
            $sub = $conn->real_escape_string($_POST['subject_name']);
            $sql = "INSERT INTO subject (SubjectName) VALUES ('$sub')";
            if ($conn->query($sql)) {
                $success_message = "Subject '{$sub}' added successfully!";
            } else {
                $error_message = "Error adding subject: " . $conn->error;
            }
            $active_tab = 'manage-tab'; // <--- SET ACTIVE TAB HERE
        }
        
        if (isset($_POST['add_room'])) {
            $room = $conn->real_escape_string($_POST['room_name']);
            $sql = "INSERT INTO room (RoomName) VALUES ('$room')";
            if ($conn->query($sql)) {
                $success_message = "Room '{$room}' added successfully!";
            } else {
                $error_message = "Error adding room: " . $conn->error;
            }
            $active_tab = 'manage-tab'; // <--- SET ACTIVE TAB HERE
        }

        // 3. Handle Delete Department, Subject, Room (and set active tab)
        if (isset($_POST['delete_department'])) {
            $dept_id = $conn->real_escape_string($_POST['department_id']);
            $sql = "DELETE FROM department WHERE DepartmentID = '$dept_id'";
            if ($conn->query($sql)) {
                $success_message = "Department (ID: {$dept_id}) deleted successfully! (Related student records may be affected)";
            } else {
                $error_message = "Error deleting department: " . $conn->error;
            }
            $active_tab = 'manage-tab'; // <--- SET ACTIVE TAB HERE
        }
        
        if (isset($_POST['delete_subject'])) {
            $sub_id = $conn->real_escape_string($_POST['subject_id']);
            $sql = "DELETE FROM subject WHERE SubjectID = '$sub_id'";
            if ($conn->query($sql)) {
                $success_message = "Subject (ID: {$sub_id}) deleted successfully! (All related schedules/attendance records were also deleted)";
            } else {
                $error_message = "Error deleting subject: " . $conn->error;
            }
            $active_tab = 'manage-tab'; // <--- SET ACTIVE TAB HERE
        }
        
        if (isset($_POST['delete_room'])) {
            $room_id = $conn->real_escape_string($_POST['room_id']);
            $sql = "DELETE FROM room WHERE RoomID = '$room_id'";
            if ($conn->query($sql)) {
                $success_message = "Room (ID: {$room_id}) deleted successfully! (All related schedules were also deleted)";
            } else {
                $error_message = "Error deleting room: " . $conn->error;
            }
            $active_tab = 'manage-tab'; // <--- SET ACTIVE TAB HERE
        }

        // Display messages
        if ($success_message) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>Success! $success_message<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
        }
        if ($error_message) {
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>Error! $error_message<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
        }

        // Fetch lists 
        $students = $conn->query("SELECT ID, FirstName, LastName FROM student ORDER BY LastName");
        $subjects = $conn->query("SELECT * FROM subject ORDER BY SubjectName");
        $rooms = $conn->query("SELECT * FROM room ORDER BY RoomName");
        $departments = $conn->query("SELECT * FROM department ORDER BY DepartmentName");
        ?>
        
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link <?php echo ($active_tab == 'schedule-tab' ? 'active' : ''); ?>" id="schedule-tab" data-bs-toggle="tab" data-bs-target="#schedule-content" type="button" role="tab" aria-controls="schedule-content" aria-selected="true">Set Student Schedule</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link <?php echo ($active_tab == 'manage-tab' ? 'active' : ''); ?>" id="manage-tab" data-bs-toggle="tab" data-bs-target="#manage-content" type="button" role="tab" aria-controls="manage-content" aria-selected="false">Manage Subjects & Rooms</button>
            </li>
        </ul>
        
        <div class="tab-content border border-top-0 p-3 bg-white shadow-sm" id="myTabContent">
            <div class="tab-pane fade <?php echo ($active_tab == 'schedule-tab' ? 'show active' : ''); ?>" id="schedule-content" role="tabpanel" aria-labelledby="schedule-tab">
                <h3 class="mb-3">Set Student Schedule</h3>
                <form method="POST">
                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label for="student_id" class="form-label">Student:</label>
                            <select class="form-select" name="student_id" id="student_id" required>
                                <option value="">Select Student</option>
                                <?php while($row = $students->fetch_assoc()) { 
                                    echo "<option value='{$row['ID']}'>{$row['LastName']}, {$row['FirstName']}</option>";
                                } ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="subject_id" class="form-label">Subject:</label>
                            <select class="form-select" name="subject_id" id="subject_id" required>
                                <option value="">Select Subject</option>
                                <?php 
                                $subjects->data_seek(0); 
                                while($row = $subjects->fetch_assoc()) {
                                    echo "<option value='{$row['SubjectID']}'>{$row['SubjectName']}</option>";
                                } ?>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="room_id" class="form-label">Room:</label>
                            <select class="form-select" name="room_id" id="room_id" required>
                                <option value="">Select Room</option>
                                <?php 
                                $rooms->data_seek(0);
                                while($row = $rooms->fetch_assoc()) {
                                    echo "<option value='{$row['RoomID']}'>{$row['RoomName']}</option>";
                                } ?>
                            </select>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="day" class="form-label">Day of Week:</label>
                            <select class="form-select" name="day" id="day">
                                <option>Monday</option><option>Tuesday</option><option>Wednesday</option>
                                <option>Thursday</option><option>Friday</option><option>Saturday</option><option>Sunday</option>
                            </select>
                        </div>
                        
                        <div class="col-md-2 mb-3">
                            <label for="start_time" class="form-label">Start Time:</label>
                            <input type="time" class="form-control" name="start_time" id="start_time" required>
                        </div>
                        
                        <div class="col-md-2 mb-3">
                            <label for="end_time" class="form-label">End Time:</label>
                            <input type="time" class="form-control" name="end_time" id="end_time" required>
                        </div>

                        <div class="col-12 mt-3">
                            <button type="submit" name="add_schedule" class="btn btn-primary">Add to Schedule</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="tab-pane fade <?php echo ($active_tab == 'manage-tab' ? 'show active' : ''); ?>" id="manage-content" role="tabpanel" aria-labelledby="manage-tab">
                
                <h4 class="mb-3 text-secondary">Quick Add New Records</h4>
                <div class="row g-4">
                    
                    <div class="col-md-4">
                        <div class="card bg-light h-100 border-info">
                            <div class="card-header bg-info text-white">
                                <h4>Quick Add Department 🏢</h4>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="department_name" placeholder="Department Name (e.g., IT)" required>
                                        <button class="btn btn-info" type="submit" name="add_department">Add Department</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card bg-light h-100 border-success">
                            <div class="card-header bg-success text-white">
                                <h4>Quick Add Subject 📚</h4>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="subject_name" placeholder="Subject Name (e.g., MATH)" required>
                                        <button class="btn btn-success" type="submit" name="add_subject">Add Subject</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card bg-light h-100 border-danger">
                            <div class="card-header bg-danger text-white">
                                <h4>Quick Add Room 🚪</h4>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="room_name" placeholder="Room Name (e.g., F212)" required>
                                        <button class="btn btn-danger" type="submit" name="add_room">Add Room</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="mt-5">
                
                <h4 class="mt-5 mb-3 text-secondary">Existing Records List</h4>
                <div class="row g-4">
                    
                    <div class="col-md-4">
                        <div class="card border-info">
                            <div class="card-header bg-info text-white">Existing Departments</div>
                            <ul class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
                                <?php 
                                $departments->data_seek(0);
                                if ($departments->num_rows > 0) {
                                    while($row = $departments->fetch_assoc()) {
                                        echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                                                {$row['DepartmentName']} 
                                                <form method='POST' style='display:inline;'>
                                                    <input type='hidden' name='department_id' value='{$row['DepartmentID']}'>
                                                    <button type='submit' name='delete_department' class='btn btn-sm btn-danger' onclick=\"return confirm('WARNING: Deleting this department may set the DepartmentID to NULL for associated students. Are you sure?');\">Delete</button>
                                                </form>
                                            </li>";
                                    }
                                } else {
                                    echo "<li class='list-group-item text-muted'>No departments added yet.</li>";
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card border-success">
                            <div class="card-header bg-success text-white">Existing Subjects</div>
                            <ul class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
                                <?php 
                                $subjects->data_seek(0);
                                if ($subjects->num_rows > 0) {
                                    while($row = $subjects->fetch_assoc()) {
                                        echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                                                {$row['SubjectName']} 
                                                <form method='POST' style='display:inline;'>
                                                    <input type='hidden' name='subject_id' value='{$row['SubjectID']}'>
                                                    <button type='submit' name='delete_subject' class='btn btn-sm btn-danger' onclick=\"return confirm('WARNING: Deleting this subject will also delete all associated schedules and attendance records. Are you sure?');\">Delete</button>
                                                </form>
                                            </li>";
                                    }
                                } else {
                                    echo "<li class='list-group-item text-muted'>No subjects added yet.</li>";
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card border-danger">
                            <div class="card-header bg-danger text-white">Existing Rooms</div>
                            <ul class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
                                <?php 
                                $rooms->data_seek(0);
                                if ($rooms->num_rows > 0) {
                                    while($row = $rooms->fetch_assoc()) {
                                        echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                                                {$row['RoomName']} 
                                                <form method='POST' style='display:inline;'>
                                                    <input type='hidden' name='room_id' value='{$row['RoomID']}'>
                                                    <button type='submit' name='delete_room' class='btn btn-sm btn-danger' onclick=\"return confirm('WARNING: Deleting this room will also delete all associated student schedules. Are you sure?');\">Delete</button>
                                                </form>
                                            </li>";
                                    }
                                } else {
                                    echo "<li class='list-group-item text-muted'>No rooms added yet.</li>";
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>