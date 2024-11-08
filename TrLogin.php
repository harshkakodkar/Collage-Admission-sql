<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: TrDashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "college_admission";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM Teachers WHERE Email='$email' AND Password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['loggedin'] = true;
        $_SESSION['email'] = $email;
        header("Location: TrDashboard.php");
        exit();
    } else {
        $error_message = "Invalid email or password. Please try again.";
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
                Teachers Login
            </div>
            <input name="email" placeholder="Your Email Address" type="text">
            <input name="password" placeholder="Your Password" type="password">
            <input id="terms" type="checkbox"> <label for="terms"></label><span>Remember me</span>
            <a href="#" class="forgot-password">Forgot Password?</a>
            <button type="submit">Login</button>
            <p>Need an account? <a href="/college/TrRegister.php" class="register">Register</a></p>
        </form>
    </div>
</body>

</html>
