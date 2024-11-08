<?php
session_start();

include 'db_connection.php';

$teacherName = "Teacher"; 
$teacherImageURL = "Resources/no_pp.png"; 

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $sql = "SELECT FirstName, IFNULL(Photo, 'Resources/no_pp.png') AS Photo FROM teachers WHERE Email='$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $teacherName = $row['FirstName'];
        $teacherImageURL = $row['Photo'];
    }
}

$sql = "SELECT * FROM StudentInformation";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

<body class="h-100">
    <div class="container-fluid h-100">
        <div class="row h-100">
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
                            <a href="TrDashboard.php" class="nav-link " aria-current="page">
                                <svg class="bi pe-none me-2" width="16" height="16">
                                    <use xlink:href="#home"></use>
                                </svg>
                                Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="StudentDetails.php" class="nav-link active" aria-current="page">
                                <svg class="bi pe-none me-2" width="16" height="16">
                                    <use xlink:href="#home"></use>
                                </svg>
                                Student Details
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <svg class="bi pe-none me-2" width="16" height="16">
                                    <use xlink:href="#home"></use>
                                </svg>
                                Admissions
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="Admissions/Science.php">Science</a></li>
                                <li><a class="dropdown-item" href="Admissions/Commerce.php">Commerce</a></li>
                                <li><a class="dropdown-item" href="Admissions/Arts.php">Arts</a></li>
                            </ul>
                        </li>
                    </ul>
                    <hr>
                </div>
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle"
                        id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?php echo $teacherImageURL; ?>" alt="" width="32" height="32"
                            class="rounded-circle me-2">
                        <strong>
                            <?php echo $teacherName; ?>
                        </strong>
                    </a>
                    <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser">
                        <li><a class="dropdown-item" href="TrProfile.php">Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
                    </ul>
                </div>
            </div>

            <div class="col">
                <h2 class="text-center">Student Information</h2>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Address</th>
                                <th>Photo</th>
                                <th>Course Name</th>
                                <th>Admission Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["StudentFirstName"] . "</td>";
                                    echo "<td>" . $row["StudentLastName"] . "</td>";
                                    echo "<td>" . $row["StudentEmail"] . "</td>";
                                    echo "<td>" . $row["StudentPhoneNumber"] . "</td>";
                                    echo "<td>" . $row["StudentAddress"] . "</td>";
                                    echo "<td>" . $row["StudentPhoto"] . "</td>";
                                    echo "<td>" . $row["CourseName"] . "</td>";
                                    echo "<td>" . $row["AdmissionStatus"] . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8'>No records found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

     <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>
