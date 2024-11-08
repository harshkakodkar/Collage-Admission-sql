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
$sql = "SELECT FirstName, IFNULL(Photo, 'Resources/no_pp.png') AS Photo FROM students WHERE Email='$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $studentName = $row['FirstName'];
    $studentImageURL = $row['Photo'];
} else {
    $studentName = "Student";
    $studentImageURL = "Resources/no_pp.png"; 
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
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
                    <a href="#"
                        class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
                        <svg class="bi pe-none me-2" width="40" height="32">
                            <use xlink:href="#bootstrap"></use>
                        </svg>
                        <span class="fs-4">Dashboard</span>
                    </a>
                    <hr>
                    <ul class="nav nav-pills flex-column mb-auto">
                        <li class="nav-item">
                            <a href="#" class="nav-link active" aria-current="page">
                                <svg class="bi pe-none me-2" width="16" height="16">
                                    <use xlink:href="#home"></use>
                                </svg>
                                Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="AdmissionStatus.php" class="nav-link ">
                                <svg class="bi pe-none me-2" width="16" height="16">
                                    <use xlink:href="#speedometer2"></use>
                                </svg>
                                Admission Status
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="message.php" class="nav-link ">
                                <svg class="bi pe-none me-2" width="16" height="16">
                                    <use xlink:href="#speedometer2"></use>
                                </svg>
                                Messages
                            </a>
                        </li>
                    </ul>
                    <hr>
                </div>
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle"
                        id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?php echo $studentImageURL; ?>" alt="" width="32" height="32"
                            class="rounded-circle me-2">
                        <strong>
                            <?php echo $studentName; ?>
                        </strong>
                    </a>
                    <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser">
                        <li><a class="dropdown-item" href="Profile.php">Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#" id="signOut">Sign out</a></li>
                    </ul>
                </div>
            </div>
            <div class="col main-content">
                <h1>Welcome,
                    <?php echo $studentName; ?>
                </h1>
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="card border border-dark mt-5" style="width: 18rem;">
                                <div class="card-body">
                                    <h5 class="card-title">Admission For Science</h5>
                                    <p class="card-text">The science program offers a comprehensive curriculum designed
                                        to provide students with a strong foundation in scientific principles and
                                        practices. Students have the opportunity to explore various branches of science,
                                        including biology, chemistry, physics, environmental science, and more.</p>
                                    <a href="/college/ScienceForm.php" class="btn btn-primary">Apply Now</a>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card border border-dark mt-5" style="width: 18rem;">
                                <div class="card-body">
                                    <h5 class="card-title">Admission For Commerce</h5>
                                    <p class="card-text">The commerce program offers a comprehensive education in
                                        business and economics, providing students with the knowledge and skills
                                        necessary for success in the dynamic world of commerce. Students gain a solid
                                        foundation in core business disciplines such as accounting, finance, marketing,
                                        management, and economics.</p>
                                    <a href="CommerceForm.php" class="btn btn-primary">Apply Now</a>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card border border-dark mt-5" style="width: 18rem;">
                                <div class="card-body">
                                    <h5 class="card-title">Admission For Arts</h5>
                                    <p class="card-text">The arts program offers a diverse and enriching educational
                                        experience, fostering creativity, critical thinking, and cultural appreciation.
                                        Students explore various disciplines within the arts, including visual arts,
                                        performing arts, literature, and humanities, allowing them to develop their
                                        talents and pursue their passions.</p>
                                    <a href="ArtsForm.php" class="btn btn-primary">Apply Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>
    <script>
        document.getElementById('signOut').addEventListener('click', function () {
            window.location.href = 'logout.php';
        });
    </script>
</body>

</html>