<?php
session_start();

include 'db_connection.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

$sql = "SELECT * FROM students WHERE Email = '$email'";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
} else {
    header("Location: error.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $first_name = $_POST['fname'];
    $last_name = $_POST['lname'];
    $phone_number = $_POST['phone'];
    $address = $_POST['address'];

    $target_dir = "Images/";
    $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
    $image_url = $target_file; 
    move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file);

    $update_sql = "UPDATE students SET FirstName = '$first_name', LastName = '$last_name', PhoneNumber = '$phone_number', Address = '$address', Photo = '$image_url' WHERE Email = '$email'";
    if (mysqli_query($conn, $update_sql)) {
        $_SESSION['success_message'] = "Profile updated successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to update profile. Please try again.";
    }

    $new_password = $_POST['password'];
    if (!empty($new_password)) {
        $update_password_sql = "UPDATE students SET Password = '$new_password' WHERE Email = '$email'";
        if (mysqli_query($conn, $update_password_sql)) {
            $_SESSION['success_message'] = "Password updated successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to update password. Please try again.";
        }
    }

    header("Location: Profile.php");
    exit();
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="CSS/Profile.css" rel="stylesheet">
</head>
<body>

<div class="wrapper">
  <div class="profile">
    <div class="content">
      <center><h1>Edit Profile</h1></center>
      <?php if(isset($_SESSION['success_message'])): ?>
        <div class="success-message"><?php echo $_SESSION['success_message']; ?></div>
        <?php unset($_SESSION['success_message']); ?>
      <?php elseif(isset($_SESSION['error_message'])): ?>
        <div class="error-message"><?php echo $_SESSION['error_message']; ?></div>
        <?php unset($_SESSION['error_message']); ?>
      <?php endif; ?>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
       
        <fieldset>
          <div class="grid-35">
            <label for="avatar">Your Photo</label>
          </div>
          <div class="grid-65">
            <?php if(isset($row['Photo']) && !empty($row['Photo'])): ?>
              <img src="<?php echo $row['Photo']; ?>" alt="Profile Picture" width="100" height="100">
            <?php else: ?>
              <span class="photo" title="Upload your Avatar!"></span>
            <?php endif; ?>
            <input type="file" name="profile_image" class="btn" />
          </div>
        </fieldset>
        <fieldset>
          <div class="grid-35">
            <label for="fname">First Name</label>
          </div>
          <div class="grid-65">
            <input type="text" id="fname" name="fname" value="<?php echo isset($row['FirstName']) ? $row['FirstName'] : ''; ?>" tabindex="1" />
          </div>
        </fieldset>
        <fieldset>
          <div class="grid-35">
            <label for="lname">Last Name</label>
          </div>
          <div class="grid-65">
            <input type="text" id="lname" name="lname" value="<?php echo isset($row['LastName']) ? $row['LastName'] : ''; ?>" tabindex="2" />
          </div>
        </fieldset>
        <fieldset>
          <div class="grid-35">
            <label for="email">Email</label>
          </div>
          <div class="grid-65">
            <input type="email" id="email" name="email" value="<?php echo $email; ?>" disabled />
          </div>
        </fieldset>
        <fieldset>
          <div class="grid-35">
            <label for="phone">Phone Number</label>
          </div>
          <div class="grid-65">
            <input type="text" id="phone" name="phone" value="<?php echo isset($row['PhoneNumber']) ? $row['PhoneNumber'] : ''; ?>" tabindex="3" />
          </div>
        </fieldset>
        <fieldset>
          <div class="grid-35">
            <label for="address">Address</label>
          </div>
          <div class="grid-65">
            <input type="text" id="address" name="address" value="<?php echo isset($row['Address']) ? $row['Address'] : ''; ?>" tabindex="4" />
          </div>
        </fieldset>
        <fieldset>
          <div class="grid-35">
            <label for="password">Password</label>
          </div>
          <div class="grid-65">
            <input type="password" id="password" name="password" placeholder="Enter new password" tabindex="5" />
          </div>
        </fieldset>
       
        

        <fieldset>
          <input type="button" class="Btn cancel" value="Cancel" onclick="window.location.href='stdDashboard.php'" />
          <input type="submit" class="Btn" value="Save Changes"  />
        </fieldset>
      </form>
    </div>
  </div>
</div>
</body>
</html>
