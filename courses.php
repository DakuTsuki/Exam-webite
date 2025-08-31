<?php
session_start();
if (!isset($_SESSION['matric_no'])) {
    header("Location: login.php");
    exit();
}

require 'db_connect.php';
$matric = $_SESSION['matric_no'];
$level = $_SESSION['level'];
$student_name = $_SESSION['name'];

// Get courses for this student's le
$sql = "SELECT DISTINCT code, title, units, status, semester 
        FROM courses 
        WHERE level = $level 
        AND (matric_no IS NULL OR matric_no = '$matric')
        ORDER BY semester, code";
$result = $conn->query($sql);
$courses = $result->fetch_all(MYSQLI_ASSOC);

// Group courses by semester
$first_semester = array_filter($courses, function($course) {
    return $course['semester'] == '1st';
});
$second_semester = array_filter($courses, function($course) {
    return $course['semester'] == '2nd';
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/frontpage.css">
    <title>My Courses</title>
    <style>
        .course-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .course-table th, .course-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .course-table th {
            background: #0072ce;
            color: white;
            font-weight: bold;
        }
        .course-table tr:hover {
            background: #f5f9ff;
        }
        .compulsory { color: #006400; font-weight: bold; }
        .elective { color: #8B0000; font-weight: bold; }
        .semester-header {
            background: linear-gradient(to right, #6cb3ff, #7ed6ff);
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0 10px 0;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>UNILAG</h2>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="student.html">Student Data</a></li>
            <li class="active"><a href="courses.php">Courses</a></li>
            <li><a href="exams.php">Exam</a></li>
            <li>Results</li>
            <li>Student Applications</li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main">
        <div class="navbar">
            <div class="logo-section">
                <img src="UNILAG-LOGO1.jpg" alt="UNILAG Logo" class="logo">
                <span class="university-name">UNIVERSITY OF LAGOS</span>
            </div>
            <div class="profile-section">
                <img src="G2.jpg" alt="Profile" class="profile-pic">
                <span class="profile-name"><?php echo $student_name; ?></span>
            </div>
        </div>
        
        <!-- Header -->
        <div class="header-card">
            <div class="student-info">
                <h2><?php echo $student_name; ?></h2>
                <p class="program">Bachelor of Science in Computer Science</p>
                <span class="dept">Level <?php echo $level; ?> Student</span>
            </div>

            <div class="student-stats">
                <div><strong><?php echo $level; ?></strong><br>Year</div>
                <div><strong>Second</strong><br>Semester</div>
                <div><strong><?php echo count($courses); ?></strong><br>Courses</div>
                <div><strong><?php echo array_sum(array_column($courses, 'units')); ?></strong><br>Units</div>
            </div>
        </div>

        <!-- Courses Section -->
        <div class="semester-header">1st Semester Courses</div>
        <table class="course-table">
            <thead>
                <tr>
                    <th>Course Code</th>
                    <th>Course Title</th>
                    <th>Units</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($first_semester as $course): ?>
                <tr>
                    <td><?php echo $course['code']; ?></td>
                    <td><?php echo $course['title']; ?></td>
                    <td><?php echo $course['units']; ?></td>
                    <td class="<?php echo strtolower($course['status']); ?>">
                        <?php echo $course['status']; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="semester-header">2nd Semester Courses</div>
        <table class="course-table">
            <thead>
                <tr>
                    <th>Course Code</th>
                    <th>Course Title</th>
                    <th>Units</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($second_semester as $course): ?>
                <tr>
                    <td><?php echo $course['code']; ?></td>
                    <td><?php echo $course['title']; ?></td>
                    <td><?php echo $course['units']; ?></td>
                    <td class="<?php echo strtolower($course['status']); ?>">
                        <?php echo $course['status']; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
