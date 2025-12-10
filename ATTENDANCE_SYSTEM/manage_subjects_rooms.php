<?php require_once 'db_connect.php'; ?>
<!DOCTYPE html>
<html>
<head><title>Manage Subjects & Rooms</title></head>
<body style="font-family: Arial, sans-serif; padding: 20px;">
    <?php include 'navbar.php'; ?>
    
    <div style="display: flex; gap: 50px;">
        <div>
            <h2>Add Subject</h2>
            <?php
            if (isset($_POST['add_subject'])) {
                $sub = $_POST['subject_name'];
                $conn->query("INSERT INTO subject (SubjectName) VALUES ('$sub')");
                echo "<p style='color:green;'>Subject added!</p>";
            }
            ?>
            <form method="POST">
                <input type="text" name="subject_name" placeholder="Subject Name" required>
                <button type="submit" name="add_subject">Add Subject</button>
            </form>
        </div>

        <div>
            <h2>Add Room</h2>
            <?php
            if (isset($_POST['add_room'])) {
                $room = $_POST['room_name'];
                $conn->query("INSERT INTO room (RoomName) VALUES ('$room')");
                echo "<p style='color:green;'>Room added!</p>";
            }
            ?>
            <form method="POST">
                <input type="text" name="room_name" placeholder="Room Name (e.g. F212)" required>
                <button type="submit" name="add_room">Add Room</button>
            </form>
        </div>
    </div>
</body>
</html>