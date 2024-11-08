<?php
// Start the session at the very beginning of the file
session_start();

// Include the database connection file
include 'db_connection.php';

// Retrieve teacher's name and image from the database using session data
$teacherName = "Teacher"; // Default value
$teacherImageURL = "http://localhost/college/Resources/no_pp.png"; // Default value

// Check if email is set in the session and retrieve teacher data if available
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $sql = "SELECT FirstName, IFNULL(Photo, 'http://localhost/college/Resources/no_pp.png') AS Photo FROM teachers WHERE Email='$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $teacherName = $row['FirstName'];
        $teacherImageURL = "http://localhost/college/" . $row['Photo'];
    }
}

// Perform the query and fetch data
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Admission Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar {
            height: 100vh;
            /* Set sidebar height to 100% of viewport height */
            overflow-y: auto;
            /* Add vertical scrollbar if content exceeds sidebar height */
            position: relative;
            /* Set position relative for absolute positioning of dropdown */
        }

        .dropdown {
            position: absolute;
            /* Set position absolute for dropdown */
            bottom: 20px;
            /* Adjust bottom distance as needed */
            margin-left: 20px;
            /* Align dropdown to the left side */
        }
        

        
    </style>
</head>
<body>
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
                        <a href="http://localhost/college/TrDashboard.php" class="nav-link " aria-current="page">
                            <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"></use></svg>
                            Home
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="http://localhost/college/StudentDetails.php" class="nav-link " aria-current="page">
                            <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"></use></svg>
                            Student Details
                        </a>
                    </li>

                    <li class="nav-item ">
                        <a class="nav-link active dropdown-toggle" href="#" id="dropdownUser" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"></use></svg>
                            Admissions
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="Science.php">Science</a>
                            <a class="dropdown-item" href="Commerce.php">Commerce</a>
                            <a class="dropdown-item" href="Arts.php">Arts</a>
                        </div>
                    </li>
                    <!-- Add other menu items as needed -->
                </ul>
                <hr>
            </div>
            <!-- Dropdown moved here -->
            <div class="dropdown">
                <!-- Replace 'teacherImageURL' with the URL of the teacher's image -->
                <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="<?php echo $teacherImageURL; ?>" alt="" width="32" height="32" class="rounded-circle me-2">
                    <!-- Replace 'teacherName' with the name of the teacher -->
                    <strong><?php echo $teacherName; ?></strong>
                </a>
                <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser">
                    <li><a class="dropdown-item" href="TrProfile.php">Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
                </ul>
            </div>
        </div>

        <!-- Content Section -->
        <div class="col-10">
            <div class="container">
                <div class="row">
                    <div class="col">
                    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <!-- jQuery -->
                        <?php
                        

                        // Include database connection file
                        include 'db_connection.php';

                        

                        

                        // Define the course ID
                        $courseID = 102; // Assuming the course ID is 102

                        // Check if the form was submitted and if the required fields are set
                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['studentID']) && isset($_POST['teacherID'])) {
                            // Retrieve student ID and teacher ID from the form
                            $studentID = $_POST['studentID'];
                            $teacherID = $_POST['teacherID'];

                            // Initialize variables for update
                            $approvedByTeacherID = $teacherID;
                            $admissionStatus = '';

                            // Check which button was clicked
                            if (isset($_POST['accept_button'])) {
                                // Check if the AdmissionStatus is already approved
                                $check_sql = "SELECT AdmissionStatus FROM admissions WHERE StudentID = ? AND CourseID = ?";
                                $check_stmt = $conn->prepare($check_sql);
                                $check_stmt->bind_param("ii", $studentID, $courseID);
                                $check_stmt->execute();
                                $check_result = $check_stmt->get_result();

                                if ($check_result->num_rows > 0) {
                                    $row = $check_result->fetch_assoc();

                                    if ($row['AdmissionStatus'] === 'Approved') {
                                        echo "Already approved!";
                                        echo '<a href="Commerce.php" class="btn btn-primary"> Next</a>';
                                        exit;
                                    } elseif ($row['AdmissionStatus'] === 'Rejected') {
                                        $admissionStatus = 'Rejected'; // Update admission status to rejected
                                    }
                                }

                                // Update ApprovedByTeacherID to the teacherID
                                $approvedByTeacherID = $teacherID;
                                $admissionStatus = 'Approved';
                            } elseif (isset($_POST['reject_button'])) {
                                // Check if the AdmissionStatus is already rejected
                                $check_sql = "SELECT AdmissionStatus FROM admissions WHERE StudentID = ? AND CourseID = ?";
                                $check_stmt = $conn->prepare($check_sql);
                                $check_stmt->bind_param("ii", $studentID, $courseID);
                                $check_stmt->execute();
                                $check_result = $check_stmt->get_result();

                                if ($check_result->num_rows > 0) {
                                    $row = $check_result->fetch_assoc();
                                    if ($row['AdmissionStatus'] === 'Rejected') {
                                        echo "Already rejected!";
                                        echo '<a href="Commerce.php" class="btn btn-primary"> Next</a>';
                                        exit;
                                    }
                                }

                                // Update ApprovedByTeacherID to the teacherID
                                $approvedByTeacherID = $teacherID;
                                $admissionStatus = 'Rejected';
                            }

                            // Prepare the SQL statement for updating admission status using the stored procedure
                            $sql_update = "CALL UpdateAdmissionStatus(?, ?, ?, ?)";

                            // Prepare the SQL statement
                            $stmt_update = $conn->prepare($sql_update);

                            if (!$stmt_update) {
                                // Error handling for statement preparation failure
                                echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
                                exit;
                            }

                            // Bind parameters and execute the statement
                            $stmt_update->bind_param("iiis", $studentID, $courseID, $approvedByTeacherID, $admissionStatus);
                            $stmt_update->execute();

                            if ($stmt_update->errno) {
                                // Error handling for execution failure
                                echo "Execute failed: (" . $stmt_update->errno . ") " . $stmt_update->error;
                                exit;
                            }

                            // Check if the update was successful
                            if ($stmt_update->affected_rows > 0) {
                                echo "Update successful!";
                                // Display button to go to Commerce.php
                                echo '<a href="Commerce.php" class="btn btn-primary"> Next</a>';
                            } else {
                                echo "Update failed. Please try again.";
                            }

                            // Close the statement
                            $stmt_update->close();
                        } else {
                            // If the form was not submitted correctly, redirect back to the previous page
                            header("Location: student_admission_form.php"); // Replace "student_admission_form.php" with the actual page URL
                            exit;
                        }

                        // Close the database connection
                        $conn->close();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
