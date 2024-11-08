<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "college_admission";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);

    $sql = "SELECT * FROM students WHERE Email='$email' AND Password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $_SESSION['loggedin'] = true;
        $_SESSION['email'] = $email;
        header("Location: StdDashboard.php"); 
        exit();
    } else {
        echo "Invalid email or password.";  
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="Styles.css" rel="stylesheet">
</head>

<body>
    <div class="form-box">
        <?php if (isset($error_message)) echo "<div class='header-text'>$error_message</div>"; ?>
        <form method="post">
            <div class="header-text">
                Student Login
            </div>
            <input name="email" placeholder="Your Email Address" type="text">
            <input name="password" placeholder="Your Password" type="password">
            <input id="terms" type="checkbox"> <label for="terms"></label><span>Remember me</span>
            <a href="#" class="forgot-password">Forgot Password?</a>
            <button type="submit">Login</button>
            <p>Need an account? <a href="/college/StdRegister.php" class="register">Register</a></p>
        </form>
    </div>
</body>

</html>
