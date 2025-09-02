<?php
session_start();
if (!isset($_SESSION['matric_no'])) {
    header("Location: login.php");
    exit();
}

require 'db_connect.php';
$matric = $_SESSION['matric_no'];
$student_name = $_SESSION['name'];
$level = $_SESSION['level'];

// Get basic student data from database
$sql = "SELECT matric_no, name, level FROM students WHERE matric_no = '$matric'";
$result = $conn->query($sql);
$student = $result->fetch_assoc();

// If no data found or partial data, use default values
if (!$student) {
    $student = [
        'matric_no' => $matric,
        'name' => $student_name,
        'level' => $level
    ];
}

// Set default values for missing fields
$defaults = [
    'email' => strtolower(str_replace(' ', '.', $student_name)) . '@student.unilag.edu.ng',
    'phone' => '+234 80' . rand(10000000, 99999999),
    'dob' => '2000-' . rand(1, 12) . '-' . rand(1, 28),
    'state_of_origin' => 'Lagos',
    'nationality' => 'Nigerian',
    'faculty' => 'Science',
    'department' => 'Computer Science',
    'program' => 'B.Sc. Computer Science' ];

// Merge with defaults (defaults only used if field doesn't exist)
$student = array_merge($defaults, $student);
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/frontpage.css">
    <title>Student Bio-Data</title>
    <style>
        .bio-container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .bio-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #0072ce;
            padding-bottom: 20px;
        }
        
        .bio-header h2 {
            color: #0072ce;
            margin: 0;
            font-size: 28px;
        }
        
        .bio-header p {
            color: #666;
            margin: 5px 0;
        }
        
        .bio-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 4px solid #0072ce;
            margin: 0 auto 20px;
            background: linear-gradient(45deg, #6cb3ff, #7ed6ff);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
            font-weight: bold;
        }
        
        .bio-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .bio-section {
            background: #f8fbff;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #0072ce;
        }
        
        .bio-section h3 {
            color: #0072ce;
            margin-top: 0;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        
        .bio-item {
            display: flex;
            justify-content: between;
            margin-bottom: 12px;
        }
        
        .bio-label {
            font-weight: bold;
            color: #333;
            min-width: 120px;
        }
        
        .bio-value {
            color: #666;
            flex: 1;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }
        
        .btn-primary {
            background: #0072ce;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: 0.3s;
        }
        
        .btn-primary:hover {
            background: #005baa;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: 0.3s;
        }
        
        .btn-secondary:hover {
            background: #545b62;
            transform: translateY(-2px);
        }
        
        @media print {
            .action-buttons {
                display: none;
            }
            .bio-container {
                box-shadow: none;
                border: 1px solid #000;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>UNILAG</h2>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li class="active"><a href="student_bio.php">Bio Data</a></li>
            <li><a href="courses.php">Courses</a></li>
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

        <div class="bio-container">
            <!-- Header -->
            <div class="bio-header">
                <div class="bio-photo">
                    <?php echo substr($student_name, 0, 1); ?>
                </div>
                <h2>STUDENT BIO-DATA</h2>
                <p>UNIVERSITY OF LAGOS • FACULTY OF SCIENCE</p>
            </div>

            <!-- Personal Information -->
            <div class="bio-grid">
                <div class="bio-section">
                    <h3>Personal Information</h3>
                    <div class="bio-item">
                        <span class="bio-label">Full Name:</span>
                        <span class="bio-value"><?php echo $student['name']; ?></span>
                    </div>
                    <div class="bio-item">
                        <span class="bio-label">Matric No:</span>
                        <span class="bio-value"><?php echo $student['matric_no']; ?></span>
                    </div>
                    <div class="bio-item">
                        <span class="bio-label">Email:</span>
                        <span class="bio-value"><?php echo $student['email']; ?></span>
                    </div>
                    <div class="bio-item">
                        <span class="bio-label">Phone:</span>
                        <span class="bio-value"><?php echo $student['phone']; ?></span>
                    </div>
                    <div class="bio-item">
                        <span class="bio-label">Date of Birth:</span>
                        <span class="bio-value"><?php echo $student['dob']; ?></span>
                    </div>
                </div>

                <div class="bio-section">
                    <h3>Academic Information</h3>
                    <div class="bio-item">
                        <span class="bio-label">Level:</span>
                        <span class="bio-value"><?php echo $student['level']; ?> Level</span>
                    </div>
                    <div class="bio-item">
                        <span class="bio-label">Faculty:</span>
                        <span class="bio-value"><?php echo $student['faculty']; ?></span>
                    </div>
                    <div class="bio-item">
                        <span class="bio-label">Department:</span>
                        <span class="bio-value"><?php echo $student['department']; ?></span>
                    </div>
                    <div class="bio-item">
                        <span class="bio-label">Program:</span>
                        <span class="bio-value"><?php echo $student['program']; ?></span>
                    </div>
                    <div class="bio-item">
                        <span class="bio-label">Status:</span>
                        <span class="bio-value">Active Student</span>
                    </div>
                </div>

                <div class="bio-section">
                    <h3>Background Information</h3>
                    <div class="bio-item">
                        <span class="bio-label">Nationality:</span>
                        <span class="bio-value"><?php echo $student['nationality']; ?></span>
                    </div>
                    <div class="bio-item">
                        <span class="bio-label">State of Origin:</span>
                        <span class="bio-value"><?php echo $student['state_of_origin']; ?></span>
                    </div>
                    <div class="bio-item">
                        <span class="bio-label">LGA:</span>
                        <span class="bio-value">Lagos Island</span>
                    </div>
                    <div class="bio-item">
                        <span class="bio-label">Home Address:</span>
                        <span class="bio-value">24, Marina Road, Lagos Island, Lagos</span>
                    </div>
                    <div class="bio-item">
                        <span class="bio-label">Blood Group:</span>
                        <span class="bio-value">O+</span>
                    </div>
                </div>

                <div class="bio-section">
                    <h3>Emergency Contact</h3>
                    <div class="bio-item">
                        <span class="bio-label">Contact Person:</span>
                        <span class="bio-value">Mr. Adeyemi Johnson</span>
                    </div>
                    <div class="bio-item">
                        <span class="bio-label">Relationship:</span>
                        <span class="bio-value">Father</span>
                    </div>
                    <div class="bio-item">
                        <span class="bio-label">Phone:</span>
                        <span class="bio-value">+234 803 456 7890</span>
                    </div>
                    <div class="bio-item">
                        <span class="bio-label">Email:</span>
                        <span class="bio-value">adeyemi.johnson@email.com</span>
                    </div>
                    <div class="bio-item">
                        <span class="bio-label">Address:</span>
                        <span class="bio-value">Same as Home Address</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button onclick="window.print()" class="btn-primary">
                    🖨️ Print Bio-Data
                </button>
                <a href="dashboard.php" class="btn-secondary">
                    ← Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</body>
</html>
