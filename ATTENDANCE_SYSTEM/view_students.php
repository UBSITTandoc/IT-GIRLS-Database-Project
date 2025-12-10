<?php require_once 'db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Master List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-4">
        <h2 class="mb-4 text-primary">👥 Student Master List & Search</h2>

        <div class="card mb-4 shadow-sm border-info">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-center">
                    <div class="col-md-9">
                        <input type="text" class="form-control form-control-lg" name="search_query" placeholder="Global Search: Enter Student Number, Name, or Address detail..." required value="<?php echo htmlspecialchars(isset($_GET['search_query']) ? $_GET['search_query'] : ''); ?>">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-info btn-lg w-100 text-white">Search Students</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-center">
                    <div class="col-auto">
                        <label for="dept_filter" class="col-form-label">Filter by Department:</label>
                    </div>
                    <div class="col-auto">
                        <select name="dept_filter" id="dept_filter" class="form-select">
                            <option value="">All Departments</option>
                            <?php 
                            $d = $conn->query("SELECT * FROM department ORDER BY DepartmentName");
                            while($row = $d->fetch_assoc()) {
                                $selected = (isset($_GET['dept_filter']) && $_GET['dept_filter'] == $row['DepartmentID']) ? 'selected' : '';
                                echo "<option value='{$row['DepartmentID']}' {$selected}>{$row['DepartmentName']}</option>"; 
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-secondary">Filter List</button>
                    </div>
                    <div class="col-auto">
                        <a href="view_students.php" class="btn btn-outline-secondary">Clear Filters</a>
                    </div>
                </form>
            </div>
        </div>

        <?php
        $where_clauses = [];
        $search_active = false;

        // 1. Department Filter Logic
        if(isset($_GET['dept_filter']) && $_GET['dept_filter'] != "") {
            $dept_id = $conn->real_escape_string($_GET['dept_filter']);
            $where_clauses[] = "s.DepartmentID = '{$dept_id}'";
        }
        
        // 2. Global Search Logic
        // This is the section that was likely causing the error if the variable wasn't set.
        if(isset($_GET['search_query']) && !empty($_GET['search_query'])) {
            $search = $conn->real_escape_string($_GET['search_query']);
            $search_active = true;
            $search_term = " LIKE '%{$search}%'";
            
            $search_fields = [
                's.StudentNumber', 's.FirstName', 's.MiddleName', 's.LastName', 's.Suffix', 
                's.HouseNo', 's.Street', 's.Barangay', 's.Municipality', 's.Province', 's.Country'
            ];
            
            $search_conditions = [];
            foreach($search_fields as $field) {
                $search_conditions[] = "{$field} {$search_term}";
            }
            $where_clauses[] = "(" . implode(" OR ", $search_conditions) . ")";
        }

        $filter_clause = '';
        if (!empty($where_clauses)) {
            $filter_clause = " WHERE " . implode(" AND ", $where_clauses);
        }

        // Selecting StudentNumber and other required fields
        $sql = "SELECT s.ID, s.StudentNumber, s.FirstName, s.LastName, d.DepartmentName 
                FROM student s 
                LEFT JOIN department d ON s.DepartmentID = d.DepartmentID 
                {$filter_clause}
                ORDER BY s.LastName, s.FirstName";
        
        $result = $conn->query($sql);

        if ($search_active) {
            echo "<div class='alert alert-info'>Displaying results for search query: <strong>" . htmlspecialchars($_GET['search_query']) . "</strong></div>";
        }
        ?>

        <div class="table-responsive">
            <table class="table table-striped table-hover shadow-sm">
                <thead class="table-dark">
                    <tr>
                        <th>Student Number</th> 
                        <th>Name</th>
                        <th>Department</th>
                        <th>Enrolled Subjects</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows == 0):
                        echo "<tr><td colspan='5' class='text-center'><div class='alert alert-warning m-2'>No students found matching the criteria.</div></td></tr>";
                    endif;

                    while($row = $result->fetch_assoc()) {
                        // Fetch schedules for this student
                        $sched_sql = "SELECT sub.SubjectName, r.RoomName, sss.DayOfWeek, sss.StartTime 
                                      FROM student_subject_schedule sss 
                                      JOIN subject sub ON sss.SubjectID = sub.SubjectID 
                                      JOIN room r ON sss.RoomID = r.RoomID 
                                      WHERE sss.StudentID = " . $row['ID'] . "
                                      ORDER BY sss.StartTime ASC LIMIT 2"; // Limit to 2 for brevity in the list
                        $scheds = $conn->query($sched_sql);
                        
                        $sched_str = "<ul class='list-unstyled mb-0 small'>";
                        if ($scheds->num_rows > 0) {
                             while($sch = $scheds->fetch_assoc()) {
                                $time_display = date("h:i A", strtotime($sch['StartTime']));
                                $sched_str .= "<li><span class='badge bg-info'>{$sch['DayOfWeek']}</span> {$sch['SubjectName']} ({$time_display})</li>";
                            }
                            if ($scheds->num_rows > 2) {
                                $sched_str .= "<li>... and more</li>";
                            }
                        } else {
                            $sched_str .= "<li><span class='text-muted'>No schedule set.</span></li>";
                        }
                        $sched_str .= "</ul>";

                        // FIX: Replaced $row['DepartmentName'] ?? 'N/A' with ternary check
                        $department_name = isset($row['DepartmentName']) ? $row['DepartmentName'] : 'N/A';
                        
                        echo "<tr>
                                <td><span class='badge bg-dark'>{$row['StudentNumber']}</span></td>
                                <td>{$row['LastName']}, {$row['FirstName']}</td>
                                <td><span class='badge bg-secondary'>{$department_name}</span></td>
                                <td>$sched_str</td>
                                <td>
                                    <a href='view_student_details.php?id={$row['ID']}' class='btn btn-sm btn-outline-primary'>View Details</a>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>