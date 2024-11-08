<?php
session_start();


include_once 'db_connection.php';


$email = $_SESSION['email'] ?? ''; // Using null coalescing operator to handle undefined index

// Fetch student's data
$sql = "SELECT FirstName, IFNULL(Photo, 'Resources/no_pp.png') AS Photo FROM students WHERE Email='$email'";
$result = $conn->query($sql);

$studentName = "Student";
$studentImageURL = "Resources/no_pp.png"; // Default values

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $studentName = $row['FirstName'];
    $studentImageURL = $row['Photo'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Admission Form</title>
    <link href="Form.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .error {
            color: red;
        }

        .sidebar {
            height: 100vh;
            overflow-y: auto;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            padding-top: 15px;
        }

        .dropdown {
            position: absolute;
            bottom: 20px;
            left: 0;
            margin-left: 20px;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            
            <div class="col-2 bg-light sidebar">
                <div class="d-flex flex-column flex-shrink-0 p-3">
                    <a href="#" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
                        <svg class="bi pe-none me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
                        <span class="fs-4">Dashboard</span>
                    </a>
                    <hr>
                    <ul class="nav nav-pills flex-column mb-auto">
                        <li class="nav-item">
                            <a href="StdDashboard.php" class="nav-link active" aria-current="page">
                                <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"></use></svg>
                                Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="AdmissionStatus.php" class="nav-link ">
                                <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#speedometer2"></use></svg>
                                Admission Status
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="message.php" class="nav-link ">
                                <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#speedometer2"></use></svg>
                                Messages
                            </a>
                        </li>
                    </ul>
                    <hr>
                </div>
                <!-- Dropdown moved here -->
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?php echo $studentImageURL; ?>" alt="" width="32" height="32" class="rounded-circle me-2">
                        <strong><?php echo $studentName; ?></strong>
                    </a>
                    <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser">
                        <li><a class="dropdown-item" href="Profile.php">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" id="signOut">Sign out</a></li> 
                    </ul>
                </div>
            </div>
            <div class="col-10 main-content">

            <h2>Admission Already Submitted</h2>
        <p>Your admission form has already been submitted. You cannot submit it again.</p>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script>
        document.getElementById('signOut').addEventListener('click', function() {
            
            window.location.href = 'logout.php';
        });
    </script>
</body>
</html>