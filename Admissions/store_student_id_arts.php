<?php
// Start or resume session
session_start();

// Include database connection file
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

// Check if studentID is provided in the request
if (isset($_GET['studentID'])) {
    // Set the session variable with the provided studentID
    $_SESSION['form_studentID'] = $_GET['studentID'];
}

// Get the student ID from the session
$studentID = $_SESSION['form_studentID'] ?? null;

// Get the teacher email ID from the session
$teacherEmail = $_SESSION['email'] ?? null;

// Initialize teacherID variable
$teacherID = null;

// Find teacherID based on teacher email
if ($teacherEmail) {
    $sql_teacher = "SELECT TeacherID FROM teachers WHERE Email = ?";
    $stmt_teacher = $conn->prepare($sql_teacher);
    $stmt_teacher->bind_param("s", $teacherEmail);
    $stmt_teacher->execute();
    $result_teacher = $stmt_teacher->get_result();

    if ($result_teacher->num_rows > 0) {
        $teacher_row = $result_teacher->fetch_assoc();
        $teacherID = $teacher_row['TeacherID'];
    } else {
        echo '<p>Teacher not found with the given email ID.</p>';
        exit; // Exit if teacher not found
    }

    // Close the statement
    $stmt_teacher->close();
} else {
    echo '<p>Teacher email ID is not set in the session.</p>';
    exit; // Exit if teacher email ID is not set
}

if ($studentID) {
    // SQL query to fetch student information including image URL
    $sql = "SELECT s.*, ed.*, c.CourseName, s.Photo AS StudentPhoto
    FROM students s
    INNER JOIN educationdetails ed ON s.StudentID = ed.StudentID
    INNER JOIN courses c ON ed.CourseID = c.CourseID
    INNER JOIN admissions a ON s.StudentID = a.StudentID
    WHERE s.StudentID = ? AND a.CourseID = 103 AND c.CourseName = 'Arts'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $studentID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Output student information
        $student = $result->fetch_assoc();
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
                            <a href="http://localhost/college/StudentDetails.php"" class="nav-link " aria-current="page">
                                <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"></use></svg>
                                Student Details
                            </a>
                        </li>

                        <li class="nav-item ">
                            <a class="nav-link active dropdown-toggle" href="#" id="dropdownUser" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg class="bi pe-none me-2" width="16" height="16"><use
                                        xlink:href="#home"></use></svg>
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
                    <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle"
                        id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?php echo $teacherImageURL; ?>" alt="" width="32" height="32"
                            class="rounded-circle me-2">
                        <strong><?php echo $teacherName; ?></strong>
                    </a>
                    <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser">
                        <li><a class="dropdown-item" href="TrProfile.php">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
                    </ul>
                </div>
            </div>

            <!-- Student Information -->
            <div class="col-10">
                <div class="container">
                    <h2 class="text-center">Student Admission Form</h2>
                    <div class="card">
                        <div class="card-header">
                            Student Information
                        </div>
                        <div class="card-body">
                            <form method="post" action="update_status_a.php"> <!-- Updated form action -->

                                <!-- Display student photo if available -->
                                <?php if (!empty($student['StudentPhoto'])) : ?>
                                    <div class="form-group">
                                        <center>
                                            <img src="http://localhost/college/<?php echo $student['StudentPhoto']; ?>"
                                                class="img-thumbnail" alt="Student Photo">
                                        </center>
                                    </div>
                                <?php endif; ?>
                                <!-- Display other student details -->
                                <div class="form-group">
                                    <label for="firstName">First Name:</label>
                                    <input type="text" class="form-control" id="firstName"
                                        value="<?php echo $student['FirstName']; ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="lastName">Last Name:</label>
                                    <input type="text" class="form-control" id="lastName"
                                        value="<?php echo $student['LastName']; ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="email" class="form-control" id="email"
                                        value="<?php echo $student['Email']; ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="phoneNumber">Phone Number:</label>
                                    <input type="text" class="form-control" id="phoneNumber"
                                        value="<?php echo $student['PhoneNumber']; ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="address">Address:</label>
                                    <textarea class="form-control" id="address" rows="3"
                                        readonly><?php echo $student['Address']; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="course">Course:</label>
                                    <input type="text" class="form-control" id="course"
                                        value="<?php echo $student['CourseName']; ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="english">English:</label>
                                    <input type="text" class="form-control" id="english"
                                        value="<?php echo $student['Standard10_English']; ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="hindi">Hindi:</label>
                                    <input type="text" class="form-control" id="hindi"
                                        value="<?php echo $student['Standard10_Hindi']; ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="language">Second Language:</label>
                                    <input type="text" class="form-control" id="language"
                                        value="<?php echo $student['Standard10_MarathiORKonkani']; ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="maths">Maths:</label>
                                    <input type="text" class="form-control" id="maths"
                                        value="<?php echo $student['Standard10_Maths']; ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="science">Science:</label>
                                    <input type="text" class="form-control" id="science"
                                        value="<?php echo $student['Standard10_Science']; ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="Social_science">Social Science:</label>
                                    <input type="text" class="form-control" id="social_science"
                                        value="<?php echo $student['Standard10_Social_Science']; ?>" readonly>
                                </div>

                                <!-- Add more fields from educationdetails as needed -->

                                <div class="form-group">
                                    <label for="marksheet">Marksheet Photo:</label>
                                    <img src="http://localhost/college/<?php echo $student['MarksheetPhoto']; ?>"
                                        class="img-thumbnail" alt="Marksheet Photo">
                                </div>

                                <!-- Hidden input fields to pass studentID and teacherID -->
                                <input type="hidden" name="studentID" value="<?php echo $studentID; ?>">
                                <input type="hidden" name="teacherID" value="<?php echo $teacherID; ?>">

                                <!-- Buttons to accept or reject -->
                                <button type="submit" name="accept_button" class="btn btn-success">
                                    Accept
                                </button>
                                <button type="submit" name="reject_button" class="btn btn-danger">
                                    Reject
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <!-- jQuery -->

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>

<?php
    } else {
        echo '<p>No student found with the given ID.</p>';
    }
} else {
    echo '<p>Student ID is not set in the session.</p>';
}
?>

