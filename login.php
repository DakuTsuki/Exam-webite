<?php
ob_start();
session_start();
// Include your database connection (using your existing file)
require 'db_connect.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = $_POST['matric'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM students WHERE matric_no = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $matric);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
    // Login successful (no password check)
    $user = $result->fetch_assoc();
    $_SESSION['matric_no'] = $matric;
    $_SESSION['name'] = $user['name'];
    $_SESSION['level'] = $user['level']; // Also set the level from database
    // Redirect to dashboard
    header("Location: dashboard.php");
     exit();
    } else {
        $error = "Invalid Matric Number.";
    }
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Unilag.css">
    <title>Student Portal Login</title>
</head>
<body>
    <div class="container">
        <div class="left">
            <img src="UNILAG-LOGO1.jpg" alt="University Logo">
        </div>
        <div class="right">
            <h2>LOG IN</h2>
            <?php if (!empty($error)): ?>
                <div style="color: red; text-align: center; margin-bottom: 15px; font-size: 14px;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="matric">Matric/Application Number</label>
                    <input type="text" id="matric" name="matric" placeholder="Enter your matric number" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password">
                </div>
                <button type="submit" class="btn">SIGN IN</button>
                <div class="forgot">
                    <a href="#">Forgot Password?</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
