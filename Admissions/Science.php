<?php
session_start();

include 'db_connection.php';

$teacherName = "Teacher"; // Default value
$teacherImageURL = "http://localhost/college/Resources/no_pp.png"; // Default value

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

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student List</title>

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
            margin-left: 20px;
        }

        .btn {
            float: right;
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
                            <a href="http://localhost/college/TrDashboard.php" class="nav-link " aria-current="page">
                                <svg class="bi pe-none me-2" width="16" height="16">
                                    <use xlink:href="#home"></use>
                                </svg>
                                Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="http://localhost/college/StudentDetails.php" class="nav-link " aria-current="page">
                                <svg class="bi pe-none me-2" width="16" height="16">
                                    <use xlink:href="#home"></use>
                                </svg>
                                Student Details
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link active dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <svg class="bi pe-none me-2" width="16" height="16">
                                    <use xlink:href="#home"></use>
                                </svg>
                                Admissions
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="Science.php">Science</a></li>
                                <li><a class="dropdown-item" href="Commerce.php">Commerce</a></li>
                                <li><a class="dropdown-item" href="Arts.php">Arts</a></li>
                            </ul>
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
                        <strong>
                            <?php echo $teacherName; ?>
                        </strong>
                    </a>
                    <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser">
                        <li><a class="dropdown-item" href="http://localhost/college/TrProfile.php">Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="http://localhost/college/logout.php">Sign out</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-10">
                <div class="container">
                    <h2 class="text-center">Student List - Science Course</h2>
                    
                    <div class="mb-3">
                        <label for="filterSelect">Filter by Admission Status:</label>
                        <select class="form-control" id="filterSelect" onchange="filterStudents(this.value)">
                            <option value="all">All</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Accepted</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>

                    <div class="list-group" id="studentList">
                    </div>
                </div>
            </div>
        </div>
    </div>

 <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

<script>
function storeStudentID(studentID) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log("Student ID stored in session: " + studentID);
            window.location.href = "store_student_id.php?studentID=" + studentID;
        }
    };
    xhttp.open("GET", "store_student_id.php?studentID=" + studentID, true);
    xhttp.send();
}

function filterStudents(status) {
    var url = 'filter_students.php?status=' + status;
    fetch(url)
      .then(response => response.text())
      .then(data => {
        document.getElementById('studentList').innerHTML = data;
      })
      .catch(error => console.error('Error fetching data:', error));
}

filterStudents('all');
</script>

</body>
</html>
