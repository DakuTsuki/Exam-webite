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

// Get student's courses
$sql_courses = "SELECT DISTINCT code FROM courses 
                WHERE level = $level 
                AND (matric_no IS NULL OR matric_no = '$matric')";
$result_courses = $conn->query($sql_courses);
$student_courses = $result_courses->fetch_all(MYSQLI_ASSOC);

// Get exam timetable for student's courses with invigilators
$course_codes = array_column($student_courses, 'code');
$placeholders = implode(',', array_fill(0, count($course_codes), '?'));
$sql_exams = "SELECT e.*, i.title as invig_title, i.name as invig_name 
              FROM exams e 
              LEFT JOIN invigilators i ON e.invigilator_id = i.invigilator_id 
              WHERE e.level = ? AND e.course_code IN ($placeholders) 
              ORDER BY e.exam_date, e.exam_time";
$stmt = $conn->prepare($sql_exams);
$stmt->bind_param(str_repeat('s', count($course_codes) + 1), $level, ...$course_codes);
$stmt->execute();
$exams = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/frontpage.css">
    <title>Exam Timetable</title>
    <style>
        .exam-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .exam-table th, .exam-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .exam-table th {
            background: #0072ce;
            color: white;
            font-weight: bold;
        }
        .exam-table tr:hover {
            background: #f5f9ff;
        }
        .exam-date {
            font-weight: bold;
            color: #006400;
        }
        .exam-hall {
            font-weight: bold;
            color: #8B0000;
        }
        .invigilator {
            font-weight: bold;
            color: #5D3954;
        }
        .semester-header {
            background: linear-gradient(to right, #6cb3ff, #7ed6ff);
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0 10px 0;
            font-size: 18px;
            font-weight: bold;
        }
        .print-btn {
            background: #3cb4e7;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 20px 0;
            transition: 0.2s;
        }
        .print-btn:hover {
            background: #2a9fd6;
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
            <li><a href="courses.php">Courses</a></li>
            <li class="active"><a href="exams.php">Exam</a></li>
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
                <span class="dept">Level <?php echo $level; ?> Exam Timetable</span>
            </div>

            <div class="student-stats">
                <div><strong><?php echo $level; ?></strong><br>Year</div>
                <div><strong><?php echo count($exams); ?></strong><br>Exams</div>
                <div><strong><?php echo count($student_courses); ?></strong><br>Courses</div>
                <div><strong>—</strong><br>Status</div>
            </div>
        </div>

        <!-- Exam Timetable -->
        <h3 style="margin: 20px 0; color: #0072ce;"> Exam Timetable</h3>
        
        <table class="exam-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Course</th>
                    <th>Course Title</th>
                    <th>Exam Hall</th>
                    <th>Invigilator</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($exams) > 0): ?>
                    <?php foreach ($exams as $exam): ?>
                    <tr>
                        <td class="exam-date"><?php echo date('M j, Y', strtotime($exam['exam_date'])); ?></td>
                        <td><?php echo date('g:i A', strtotime($exam['exam_time'])); ?></td>
                        <td><strong><?php echo $exam['course_code']; ?></strong></td>
                        <td><?php echo $exam['course_title']; ?></td>
                        <td class="exam-hall"><?php echo $exam['exam_hall']; ?></td>
                        <td class="invigilator">
                            <?php echo !empty($exam['invig_title']) ? $exam['invig_title'] . ' ' . $exam['invig_name'] : 'TBA'; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px;">
                            No exam schedule available yet. Please check back later.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Print Button -->
        <div style="text-align: center; margin: 30px 0;">
            <button onclick="window.print()" class="print-btn">
                🖨️ Print Timetable
            </button>
        </div>
    </div>

    <script>
        // Add print styles
        const style = document.createElement('style');
        style.textContent = `
            @media print {
                .sidebar, .navbar, .print-btn {
                    display: none !important;
                }
                .main {
                    margin-left: 0 !important;
                    width: 100% !important;
                }
                body {
                    background: white !important;
                }
                .exam-table {
                    box-shadow: none !important;
                    border: 1px solid #000 !important;
                }
                .header-card {
                    background: #f0f0f0 !important;
                    color: black !important;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
