<?php 
require_once 'db_connect.php'; 
date_default_timezone_set('Asia/Manila'); // Set timezone for consistency

// --- 1. INITIALIZE VARIABLES FOR FILTERING ---
$filter_date = $_GET['date'] ?? '';
$filter_dept = $_GET['department'] ?? '';
$filter_subject = $_GET['subject'] ?? '';
$filter_room = $_GET['room'] ?? '';

$where_conditions = [];

// --- 2. FETCH ALL OPTIONS FOR DROPDOWNS ---

// Fetch all Departments
$departments_query = $conn->query("SELECT DepartmentID, DepartmentName FROM department ORDER BY DepartmentName");
$departments = $departments_query->fetch_all(MYSQLI_ASSOC);

// Fetch all Subjects
$subjects_query = $conn->query("SELECT SubjectID, SubjectName FROM subject ORDER BY SubjectName");
$subjects = $subjects_query->fetch_all(MYSQLI_ASSOC);

// Fetch all Rooms
$rooms_query = $conn->query("SELECT RoomID, RoomName FROM room ORDER BY RoomName");
$rooms = $rooms_query->fetch_all(MYSQLI_ASSOC);


// --- 3. BUILD THE WHERE CLAUSE ---

// Date Filter
if (!empty($filter_date)) {
    $where_conditions[] = "a.Date = '" . $conn->real_escape_string($filter_date) . "'";
}

// Department Filter
if (!empty($filter_dept)) {
    $where_conditions[] = "s.DepartmentID = '" . $conn->real_escape_string($filter_dept) . "'";
}

// Subject Filter
if (!empty($filter_subject)) {
    // Note: Filtering by SubjectID requires joining sss table (already done)
    $where_conditions[] = "sss.SubjectID = '" . $conn->real_escape_string($filter_subject) . "'";
}

// Room Filter
if (!empty($filter_room)) {
    // Note: Filtering by RoomID requires joining sss table (already done)
    $where_conditions[] = "sss.RoomID = '" . $conn->real_escape_string($filter_room) . "'";
}

// Combine all conditions
$where_clause = '';
if (!empty($where_conditions)) {
    $where_clause = "WHERE " . implode(' AND ', $where_conditions);
}

// *** UPDATED SQL QUERY ***
$sql = "
    SELECT 
        a.AttendanceID,
        s.StudentNumber,
        s.FirstName,
        s.LastName,
        d.DepartmentName,
        sub.SubjectName,
        r.RoomName,
        a.Date,
        a.TimeIn,
        a.Status
    FROM attendance a
    JOIN student s ON a.StudentID = s.ID
    JOIN department d ON s.DepartmentID = d.DepartmentID
    -- We join student_subject_schedule (sss) to get SubjectName and RoomName
    JOIN student_subject_schedule sss ON a.StudentScheduleID = sss.StudentScheduleID
    JOIN subject sub ON sss.SubjectID = sub.SubjectID
    JOIN room r ON sss.RoomID = r.RoomID
    {$where_clause}
    ORDER BY a.Date DESC, a.TimeIn DESC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendance | Attendance System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-4">
        <h2 class="mb-4 text-center text-secondary">📋 Detailed Attendance Records</h2>
        <hr>

        <div class="card mb-4 shadow-sm border-info">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-center">
                    
                    <div class="col-md-3">
                        <label for="dateFilter" class="form-label">Date:</label>
                        <input type="date" class="form-control" id="dateFilter" name="date" value="<?php echo htmlspecialchars($filter_date); ?>">
                    </div>

                    <div class="col-md-3">
                        <label for="departmentFilter" class="form-label">Department:</label>
                        <select class="form-select" id="departmentFilter" name="department">
                            <option value="">All Departments</option>
                            <?php foreach ($departments as $dept): ?>
                                <option 
                                    value="<?php echo htmlspecialchars($dept['DepartmentID']); ?>"
                                    <?php echo ($filter_dept == $dept['DepartmentID']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($dept['DepartmentName']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="subjectFilter" class="form-label">Subject:</label>
                        <select class="form-select" id="subjectFilter" name="subject">
                            <option value="">All Subjects</option>
                            <?php foreach ($subjects as $sub): ?>
                                <option 
                                    value="<?php echo htmlspecialchars($sub['SubjectID']); ?>"
                                    <?php echo ($filter_subject == $sub['SubjectID']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($sub['SubjectName']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="roomFilter" class="form-label">Room:</label>
                        <select class="form-select" id="roomFilter" name="room">
                            <option value="">All Rooms</option>
                            <?php foreach ($rooms as $room): ?>
                                <option 
                                    value="<?php echo htmlspecialchars($room['RoomID']); ?>"
                                    <?php echo ($filter_room == $room['RoomID']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($room['RoomName']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-12 pt-2">
                        <button type="submit" class="btn btn-info text-white me-2">Apply Filters</button>
                        <a href="view_attendance.php" class="btn btn-secondary">Clear Filters</a>
                    </div>
                </form>
            </div>
        </div>

        <?php if ($result && $result->num_rows > 0): ?>
            <div class="table-responsive shadow-lg rounded">
                <table class="table table-striped table-hover table-bordered mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Student No.</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Subject</th>
                            <th>Room</th>
                            <th>Time In</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): 
                            // Determine the class for the status badge
                            $status_class = match ($row['Status']) {
                                'Present' => 'badge bg-success',
                                'Late' => 'badge bg-warning text-dark',
                                'Absent' => 'badge bg-danger',
                                default => 'badge bg-secondary',
                            };
                            
                            // Format TimeIn
                            $time_in_display = $row['TimeIn'] ? date('h:i:s A', strtotime($row['TimeIn'])) : 'N/A';
                            
                            // Get RoomName from the JOIN (added for display)
                            $room_name_display = htmlspecialchars($row['RoomName'] ?? 'N/A');
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['AttendanceID']); ?></td>
                                <td><?php echo htmlspecialchars($row['Date']); ?></td>
                                <td><?php echo htmlspecialchars($row['StudentNumber']); ?></td>
                                <td><?php echo htmlspecialchars($row['LastName'] . ', ' . $row['FirstName']); ?></td>
                                <td><?php echo htmlspecialchars($row['DepartmentName'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($row['SubjectName']); ?></td>
                                <td><?php echo $room_name_display; ?></td>
                                <td><?php echo $time_in_display; ?></td>
                                <td>
                                    <?php echo "<span class='{$status_class}'>" . htmlspecialchars($row['Status']) . "</span>"; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-warning text-center mt-5">
                No attendance records found <?php echo !empty($where_conditions) ? " matching the selected filters." : "in the system."; ?>
            </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>