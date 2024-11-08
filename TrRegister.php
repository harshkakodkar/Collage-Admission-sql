<?php
include 'db_connection.php';

$firstName = $lastName = $phoneNo = $email = $address = $password = $confirmPassword = "";
$firstNameErr = $lastNameErr = $phoneNoErr = $emailErr = $addressErr = $passwordErr = $confirmPasswordErr = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    if(empty(trim($_POST["firstName"]))){
        $firstNameErr = "Please enter your first name.";
    } else{
        $firstName = trim($_POST["firstName"]);
    }
    
    if(empty(trim($_POST["lastName"]))){
        $lastNameErr = "Please enter your last name.";
    } else{
        $lastName = trim($_POST["lastName"]);
    }
    
    if(empty(trim($_POST["phoneNo"]))){
        $phoneNoErr = "Please enter your phone number.";
    } else{
        $phoneNo = trim($_POST["phoneNo"]);
    }
    
    if(empty(trim($_POST["email"]))){
        $emailErr = "Please enter your email.";
    } else{
        $email = trim($_POST["email"]);
    }
    
    if(empty(trim($_POST["address"]))){
        $addressErr = "Please enter your address.";
    } else{
        $address = trim($_POST["address"]);
    }
    
    if(empty(trim($_POST["password"]))){
        $passwordErr = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $passwordErr = "Password must have at least 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    if(empty(trim($_POST["confirmPassword"]))){
        $confirmPasswordErr = "Please confirm password.";     
    } else{
        $confirmPassword = trim($_POST["confirmPassword"]);
        if(empty($passwordErr) && ($password != $confirmPassword)){
            $confirmPasswordErr = "Password did not match.";
        }
    }
    
    if(empty($firstNameErr) && empty($lastNameErr) && empty($PhoneNumberErr) && empty($emailErr) && empty($addressErr) && empty($passwordErr) && empty($confirmPasswordErr)){
        
        $sql = "INSERT INTO Teachers (FirstName, LastName, PhoneNumber, Email, Address, Password) VALUES (?, ?, ?, ?, ?, ?)";
         
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("ssssss", $paramFirstName, $paramLastName, $paramPhoneNumber, $paramEmail, $paramAddress, $paramPassword);
            
            $paramFirstName = $firstName;
            $paramLastName = $lastName;
            $paramPhoneNumber = $phoneNo;
            $paramEmail = $email;
            $paramAddress = $address;
            $paramPassword = $password; // Storing password in plain text
            
            if($stmt->execute()){
                header("location: TrLogin.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        $stmt->close();
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Registration</title>
    <link href="Registers.css" rel="stylesheet">
</head>
<body>
    <div class="form-box">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="header-text">
                Teachers Registration
            </div>
            
            <input placeholder="Your First name" type="text" name="firstName" value="<?php echo $firstName; ?>">
            <span class="error"><?php echo $firstNameErr; ?></span>

            <input placeholder="Your last name" type="text" name="lastName" value="<?php echo $lastName; ?>">
            <span class="error"><?php echo $lastNameErr; ?></span>

            <input placeholder="Your Phone no" type="text" name="phoneNo" value="<?php echo $phoneNo; ?>">
            <span class="error"><?php echo $phoneNoErr; ?></span>

            <input placeholder="Your Email" type="text" name="email" value="<?php echo $email; ?>">
            <span class="error"><?php echo $emailErr; ?></span>

            <input placeholder="Your Address" type="text" name="address" value="<?php echo $address; ?>">
            <span class="error"><?php echo $addressErr; ?></span>

            <input placeholder="Create Password" type="password" name="password" value="<?php echo $password; ?>">
            <span class="error"><?php echo $passwordErr; ?></span>

            <input placeholder="Confirm Password" type="password" name="confirmPassword" value="<?php echo $confirmPassword; ?>">
            <span class="error"><?php echo $confirmPasswordErr; ?></span>
            
            <a href="#" class="forgot-password">Forgot Password?</a>
            <button type="submit">Register</button>
            <p>Already have an account? <a href="/college/TrLogin.php">Login here</a>.</p>
        </form>
    </div>
</body>
</html>
