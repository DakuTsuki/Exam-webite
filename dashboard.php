<?php
session_start();

if (!isset($_SESSION['matric_no'])) {
    header("Location: login.php");
    exit();
}


// TODO: Add database connection to fetch real student data
// require 'db_connect.php';
// $matric = $_SESSION['matric_no'];
// $sql = "SELECT * FROM students WHERE matric_no = '$matric'";
// $result = $conn->query($sql);
// $student = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/frontpage.css">
    <title>Student Dashboard</title>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>UNILAG</h2>
        <ul>
            <li class="active"><a href="dashboard.php">Dashboard</a></li>
            <li><a href="student.html">Student Data</a></li>
            <li><a href="courses.php">Courses</a></li>
            <li><a href="exams.php">Exam</a></li>
            <li>Results</li>
            <li><a href="student_bio.php">Bio Data</a></li>
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
                <span class="profile-name"><?php echo $_SESSION['name']; ?></span>
            </div>
        </div>
        <!-- Header -->
        <div class="header-card">
            <div class="student-info">
                <h2><?php echo $_SESSION['name']; ?></h2>
                <p class="program">Bachelor of Science in Computer Science</p>
                <span class="dept">Computer</span>
            </div>

            <div class="student-stats">
                <div><strong>4</strong><br>Year</div>
                <div><strong>Second</strong><br>Semester</div>
                <div><strong>6</strong><br>Courses</div>
                <div><strong>16</strong><br>Units</div>
            </div>
        </div>
        <!-- Dashboard Cards -->
        <div class="cards">
            <div class="card">
                <h4>Courses</h4>
                <div class="progress-bar">
                    <div class="progress" id="courseProgress"></div>
                </div>
                <p id="courseText">128/128 Completed</p>
            </div>

            <div class="card" onclick="refreshPayment()">
                <h4>Refresh Payment Status</h4>
            </div>

            <div class="card">
                <h4>Payments</h4>
            </div>

            <div class="card">
                <h4>Applications</h4>
            </div>
        </div>

        <!-- Right Notices -->
        <div class="right-panel">
            <div class="notice">Complete your course registration (0 Days left)</div>
            <div class="notice">Complete your payment (0 Days left)</div>
            <div class="notice">Edit your course registration (0 Days left)</div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            function updateProgress(completed, total, progressId, textId) {
                let percent = (completed / total) * 100;
                const progressEl = document.getElementById(progressId);
                progressEl.style.transition = "width 1s ease-in-out";
                progressEl.style.width = percent + "%";
                document.getElementById(textId).innerText = '128/128 Completed'
            }
            updateProgress(128, 128, "courseProgress", "courseText");
            
            window.refreshPayment = function () {
                alert("✅ Payment status refreshed successfully!");
            };
        });
    </script>
</body>
</html>
