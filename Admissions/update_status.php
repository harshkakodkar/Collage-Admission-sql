<?php
// Start or resume session
session_start();

// Include database connection file
include 'db_connection.php';

// Define the course ID
$courseID = 101; // Assuming the course ID is 101

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
                echo '<a href="Science.php" class="btn btn-primary"> Next</a>';
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
                echo '<a href="Science.php" class="btn btn-primary"> Next</a>';
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
        // Display button to go to Science.php
        echo '<a href="Science.php" class="btn btn-primary"> Next</a>';
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
    