<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="Registers.css" rel="stylesheet">
</head>
<body>
    <div class="form-box">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            include_once "db_connection.php";

            $fname = $_POST["fname"];
            $lname = $_POST["lname"];
            $phone = $_POST["phone"];
            $email = $_POST["email"];
            $address = $_POST["address"];
            $password = $_POST["password"];

            $sql = "INSERT INTO Students (FirstName, LastName, Email, PhoneNumber, Address, Password) 
                    VALUES ('$fname', '$lname', '$email', '$phone', '$address', '$password')";

            if (mysqli_query($conn, $sql)) {
                echo "Registration successful";
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }

            mysqli_close($conn);
        }
        ?>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="header-text">
                Students Registration
            </div>
            <input placeholder="Your First Name" type="text" name="fname">
            <input placeholder="Your Last Name" type="text" name="lname">
            <input placeholder="Your Phone no" type="text" name="phone">
            <input placeholder="Your Email" type="text" name="email">
            <input placeholder="Your Address" type="text" name="address">
            <input placeholder="Create Password" type="password" name="password">
            <input placeholder="Confirm Password" type="password" name="confirm_password">
           
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="/college/StdLogin.php" class="register">Login</a></p>
    </div>
</body>
</html>
