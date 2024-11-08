<?php
session_start();

include_once 'db_connection.php';

$errorMessage = "";

$email = $_SESSION['email'] ?? ''; 
$studentName = "Student";
$studentImageURL = "Resources/no_pp.png";
$studentID = 0; 

if (!empty($email)) {
    $sql = "SELECT StudentID, FirstName, IFNULL(Photo, 'Resources/no_pp.png') AS Photo FROM students WHERE Email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $studentName = $row['FirstName'];
        $studentImageURL = $row['Photo'];
        $studentID = $row['StudentID'];
    }
}


$admissionSubmitted = false;
$courseID = 103; 
$admissionCheckSql = "SELECT * FROM educationdetails WHERE StudentID = (SELECT StudentID FROM students WHERE Email=?) AND CourseID=?";
$stmt = $conn->prepare($admissionCheckSql);
$stmt->bind_param("si", $email, $courseID);
$stmt->execute();
$admissionCheckResult = $stmt->get_result();

if ($admissionCheckResult->num_rows > 0) {
    $admissionSubmitted = true;
}

if ($admissionSubmitted) {
    header("Location: admission_already_submitted.php"); 
    exit(); 
} 


$englishErr = $hindiErr = $marathi_konkaniErr = $mathsErr = $scienceErr = $social_scienceErr = $marksheet_photoErr = "";

$successMessage = "";

function validateInteger($input) {
    return preg_match("/^[0-9]+$/", $input);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $english = $_POST['english'];
    if (!validateInteger($english)) {
        $englishErr = "Only integers allowed";
    }

    $hindi = $_POST['hindi'];
    if (!validateInteger($hindi)) {
        $hindiErr = "Only integers allowed";
    }

    $marathi_konkani = $_POST['marathi_konkani'];
    if (!validateInteger($marathi_konkani)) {
        $marathi_konkaniErr = "Only integers allowed";
    }

    $maths = $_POST['maths'];
    if (!validateInteger($maths)) {
        $mathsErr = "Only integers allowed";
    }

    $science = $_POST['science'];
    if (!validateInteger($science)) {
        $scienceErr = "Only integers allowed";
    }

    $social_science = $_POST['social_science'];
    if (!validateInteger($social_science)) {
        $social_scienceErr = "Only integers allowed";
    }

    $targetDir = "";
    $targetFile = $targetDir . basename($_FILES["marksheet_photo"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["marksheet_photo"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $marksheet_photoErr = "File is not an image.";
        $uploadOk = 0;
    }

    if ($_FILES["marksheet_photo"]["size"] > 500000) {
        $marksheet_photoErr = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif") {
        $marksheet_photoErr = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        $marksheet_photoErr = "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["marksheet_photo"]["tmp_name"], $targetFile)) {
            $courseID = 103; 
            $insertQuery = "INSERT INTO educationdetails (StudentID, CourseID, Standard10_English, Standard10_Hindi, Standard10_MarathiORKonkani, Standard10_Maths, Standard10_Science, Standard10_Social_Science, MarksheetPhoto) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("iiiiiiiss", $studentID, $courseID, $english, $hindi, $marathi_konkani, $maths, $science, $social_science, $targetFile);
            try {
                if ($stmt->execute()) {
                    $successMessage = "Form submitted successfully!";
                    
                    
                    
                    $updateAdmissionSql = "INSERT INTO admissions (StudentID, CourseID) VALUES (?, ?)";
                    $stmtAdmission = $conn->prepare($updateAdmissionSql);
                    $stmtAdmission->bind_param("ii", $studentID, $courseID);

                    if ($stmtAdmission->execute()) {
                        $successMessage = "Form submitted successfully!";
                        $admissionSubmitted = true; // Set admissionSubmitted to true
                    } else {
                        $errorMessage = "Failed to update admissions table.";
                    }
                }
            } catch (mysqli_sql_exception $exception) {
                $errorMessage = $exception->getMessage();
            }
        } else {
            $marksheet_photoErr = "Sorry, there was an error uploading your file.";
        }
    }
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
                            <a href="#" class="nav-link active" aria-current="page">
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
                <h2>Student Admission Form</h2>
                <span class="error"><?php echo $successMessage; ?></span>
                <span class="error"><?php echo $errorMessage; ?></span>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                    <label for="english">Marks For English:</label><br>
                    <input type="text" id="english" name="english" required>
                    <span class="error"><?php echo $englishErr;?></span><br>
                    
                    <label for="hindi">Marks For Hindi:</label><br>
                    <input type="text" id="hindi" name="hindi" required>
                    <span class="error"><?php echo $hindiErr;?></span><br>
                    
                    <label for="marathi_konkani">Marks For Marathi/Konkani:</label><br>
                    <input type="text" id="marathi_konkani" name="marathi_konkani" required>
                    <span class="error"><?php echo $marathi_konkaniErr;?></span><br>
                    
                    <label for="maths">Marks For Maths:</label><br>
                    <input type="text" id="maths" name="maths" required>
                    <span class="error"><?php echo $mathsErr;?></span><br>
                    
                    <label for="science">Marks For Science:</label><br>
                    <input type="text" id="science" name="science" required>
                    <span class="error"><?php echo $scienceErr;?></span><br>
                    
                    <label for="social_science">Marks For Social Science:</label><br>
                    <input type="text" id="social_science" name="social_science" required>
                    <span class="error"><?php echo $social_scienceErr;?></span><br>
                    
                    <label for="marksheet_photo">Marksheet Photo:</label><br>
                    <input type="file" id="marksheet_photo" name="marksheet_photo" required>
                    <span class="error"><?php echo $marksheet_photoErr;?></span><br><br>
                    
                    <input type="hidden" name="course_id" value="103">
                    
                    <input type="submit" value="Submit">
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script>
        document.getElementById('signOut').addEventListener('click', function() {
            window.location.href = 'logout.php';
        });
    </script>
</body>
</html>
