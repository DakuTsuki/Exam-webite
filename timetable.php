<?php
session_start();
include "db_connect.php";

if (!isset($_SESSION['matric_no'])) {
    header("Location: login.php");
    exit();
}

$matric_no = $_SESSION['matric_no'];
$level = $_SESSION['level'];

// Get semester from GET
$semester = $_GET['semester'] ?? '1';

// Convert numeric semester (1 or 2) into database format (1st or 2nd)
if ($semester == "1") {
    $semester = "1st";
} elseif ($semester == "2") {
    $semester = "2nd";
}

// Debugging (optional – you can remove later)
echo "Matric from session: " . $matric_no . "<br>";
echo "Semester after conversion: " . $semester . "<br>";

// Query exam timetable
$sql = "SELECT * FROM exam_timetable WHERE matric_no='$matric_no' AND semester='$semester'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Exam Timetable</title>
</head>
<body>
    <h2>Exam Timetable - Level <?php echo $level; ?> - Semester <?php echo $semester; ?></h2>

    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>Course Code</th>
            <th>Title</th>
            <th>Date</th>
            <th>Time</th>
            <th>Venue</th>
        </tr>
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>".$row['course_code']."</td>
                        <td>".$row['course_title']."</td>
                        <td>".$row['exam_date']."</td>
                        <td>".$row['exam_time']."</td>
                        <td>".$row['venue']."</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No exams scheduled.</td></tr>";
        }
        ?>
    </table>
</body>
</html>
