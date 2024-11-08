<?php
// Start or resume session
session_start();

// Include database connection file
include 'db_connection.php';

// Get the student ID from the session
$studentID = $_SESSION['form_studentID'] ?? null;

if ($studentID) {
    // SQL query to fetch student information
    $sql = "SELECT * FROM students WHERE StudentID = $studentID";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output student information
        $student = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Form</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
  <h2 class="text-center">Student Form</h2>

  <div class="card">
    <div class="card-header">
      Student Information
    </div>
    <div class="card-body">
      <p><strong>Name:</strong> <?php echo $student['FirstName'] . ' ' . $student['LastName']; ?></p>
      <p><strong>Email:</strong> <?php echo $student['Email']; ?></p>
      <p><strong>Phone Number:</strong> <?php echo $student['PhoneNumber']; ?></p>
      <p><strong>Address:</strong> <?php echo $student['Address']; ?></p>
      <!-- Add more fields as needed -->
    </div>
  </div>

</div>

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
