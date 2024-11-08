<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "college_admission";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve student's name and image from the database using session data
$email = $_SESSION['email'];
$sql_student = "SELECT StudentID, FirstName, IFNULL(Photo, 'Resources/no_pp.png') AS Photo FROM students WHERE Email='$email'";
$result_student = $conn->query($sql_student);

if ($result_student->num_rows > 0) {
    $row_student = $result_student->fetch_assoc();
    $studentID = $row_student['StudentID'];
    $studentName = $row_student['FirstName'];
    $studentImageURL = $row_student['Photo'];

    // Retrieve admission status and course name based on student ID
    $sql_admission = "SELECT AdmissionStatus, CourseID FROM admissions WHERE StudentID='$studentID'";
    $result_admission = $conn->query($sql_admission);

    $admissionDetails = array();

    if ($result_admission->num_rows > 0) {
        // Fetch all admission details
        while ($row_admission = $result_admission->fetch_assoc()) {
            $admissionStatus = $row_admission['AdmissionStatus'];
            $courseID = $row_admission['CourseID'];

            // Retrieve course name based on course ID
            $sql_course = "SELECT CourseName FROM courses WHERE CourseID='$courseID'";
            $result_course = $conn->query($sql_course);

            if ($result_course->num_rows > 0) {
                $row_course = $result_course->fetch_assoc();
                $courseName = $row_course['CourseName'];
            } else {
                $courseName = "Not available";
            }

            // Store admission details in an array
            $admissionDetails[] = array(
                'admissionStatus' => $admissionStatus,
                'courseName' => $courseName
            );
        }
    }
} else {
    // Handle the case where student data is not found
    $studentName = "Student";
    $studentImageURL = "Resources/no_pp.png"; // Default image URL
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <!-- Bootstrap CSS -->
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

        .content {
            margin-left: 250px;
            padding: 20px;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-2 bg-light sidebar">
                <div class="d-flex flex-column flex-shrink-0 p-3">
                    <a href="#" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
                        <svg class="bi pe-none me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
                        <span class="fs-4">Dashboard</span>
                    </a>
                    <hr>
                    <ul class="nav nav-pills flex-column mb-auto">
                        <li class="nav-item">
                            <a href="StdDashboard.php" class="nav-link " aria-current="page">
                                <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"></use></svg>
                                Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="AdmissionStatus.php" class="nav-link active">
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
                        <li><a class="dropdown-item" href="#" id="signOut">Sign out</a></li> <!-- Added id attribute for JavaScript -->
                    </ul>
                </div>
            </div>

            <!-- Page Content -->
            <div class="col-10 content">
                <div class="container mt-5">
                    <div class="row">
                        <?php foreach ($admissionDetails as $admission): ?>
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Admission Details</h5>
                                    <p class="card-text">Admission Status: <?php echo $admission['admissionStatus']; ?></p>
                                    <p class="card-text">Course Name: <?php echo $admission['courseName']; ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
   
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
   <script>
       document.getElementById('signOut').addEventListener('click', function () {
           // Trigger PHP script to destroy the session
           window.location.href = 'logout.php';
       });
       // Add your additional JavaScript code here
   </script>
</body>

</html>

