<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "college_admission";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_SESSION['email'];
$sql = "SELECT FirstName, IFNULL(Photo, 'Resources/no_pp.png') AS Photo FROM teachers WHERE Email='$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $teacherName = $row['FirstName'];
    $teacherImageURL = $row['Photo'];
} else {
    $teacherName = "Teacher";
    $teacherImageURL = "Resources/no_pp.png"; 
}

$sql_pending_admissions = "SELECT COUNT(*) AS PendingAdmissions FROM admissions WHERE AdmissionStatus = 'Pending'";
$result_pending_admissions = $conn->query($sql_pending_admissions);

if ($result_pending_admissions->num_rows > 0) {
    $row_pending_admissions = $result_pending_admissions->fetch_assoc();
    $totalPendingAdmissions = $row_pending_admissions['PendingAdmissions'];
} else {
    $totalPendingAdmissions = 0;
}

$sql_approved_admissions = "SELECT COUNT(*) AS ApprovedAdmissions FROM admissions WHERE AdmissionStatus = 'Approved'";
$result_approved_admissions = $conn->query($sql_approved_admissions);

if ($result_approved_admissions->num_rows > 0) {
    $row_approved_admissions = $result_approved_admissions->fetch_assoc();
    $totalApprovedAdmissions = $row_approved_admissions['ApprovedAdmissions'];
} else {
    $totalApprovedAdmissions = 0;
}

$sql_rejected_admissions = "SELECT COUNT(*) AS RejectedAdmissions FROM admissions WHERE AdmissionStatus = 'Rejected'";
$result_rejected_admissions = $conn->query($sql_rejected_admissions);

if ($result_rejected_admissions->num_rows > 0) {
    $row_rejected_admissions = $result_rejected_admissions->fetch_assoc();
    $totalRejectedAdmissions = $row_rejected_admissions['RejectedAdmissions'];
} else {
    $totalRejectedAdmissions = 0;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .sidebar {
            height: 100vh; 
            overflow-y: auto; 
            position: relative;
        }

        .dropdown {
            position: absolute; 
            bottom: 20px; 
            left: 0px; 
            margin-left: 20px;
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
                            <a href="#" class="nav-link active" aria-current="page">
                                <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"></use></svg>
                                Home
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="StudentDetails.php" class="nav-link " aria-current="page">
                                <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"></use></svg>
                                Student Details
                            </a>
                        </li>


                        <li class="nav-item ">
    <a class="nav-link dropdown-toggle" href="#" id="dropdownUser" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"></use></svg>
        Admissions
    </a>
    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href="Admissions/Science.php">Science</a>
        <a class="dropdown-item" href="Admissions/Commerce.php">Commerce</a>
        <a class="dropdown-item" href="Admissions/Arts.php">Arts</a>
    </div>
</li>



                    </ul>
                    <hr>
                </div>
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?php echo $teacherImageURL; ?>" alt="" width="32" height="32" class="rounded-circle me-2">
                        <strong><?php echo $teacherName; ?></strong>
                    </a>
                    <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser">
                        <li><a class="dropdown-item" href="TrProfile.php">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-10">
                <nav class="navbar navbar-dark bg-dark py-1">
                    <a href="#" id="navBtn">
                        <span id="changeIcon" class="fa fa-bars text-light"></span>
                    </a>
                    <div class="d-flex">
                        <a class="nav-link text-light px-2" href="#"><i class="fas fa-search"></i></a>
                        <a class="nav-link text-light px-2" href="#"><i class="fas fa-bell"></i></a>
                        <a class="nav-link text-light px-2" href="#"><i class="fas fa-sign-out-alt"></i></a>
                    </div>
                </nav>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-4 p-2">
                            <div class="card border-primary rounded-0">
                                <div class="card-header bg-primary rounded-0">
                                    <h5 class="card-title text-white mb-1">Pending Admissions</h5>
                                </div>
                                <div class="card-body">
                                    <h1 class="display-4 font-weight-bold text-primary text-center"><?php echo $totalPendingAdmissions; ?></h1>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 p-2">
                            <div class="card border-success rounded-0">
                                <div class="card-header bg-success rounded-0">
                                    <h5 class="card-title text-white mb-1">Approved Admissions</h5>
                                </div>
                                <div class="card-body">
                                    <h1 class="display-4 font-weight-bold text-success text-center"><?php echo $totalApprovedAdmissions; ?></h1>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 p-2">
                            <div class="card border-danger rounded-0">
                                <div class="card-header bg-danger rounded-0">
                                    <h5 class="card-title text-white mb-1">Rejected Admissions</h5>
                                </div>
                                <div class="card-body">
                                    <h1 class="display-4 font-weight-bold text-danger text-center"><?php echo $totalRejectedAdmissions; ?></h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

</script>


</body>
</html>
