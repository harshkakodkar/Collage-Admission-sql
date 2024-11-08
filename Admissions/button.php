<?php
require_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_button'])) {
    $approvedByTeacherID = $_POST['approved_by_teacher_id'];

    $studentID = 2;
    $courseID = 101;

    $sql = "UPDATE admissions 
            SET ApprovedByTeacherID = ?
            WHERE StudentID = ? AND CourseID = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("iii", $approvedByTeacherID, $studentID, $courseID);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Update successful!";
    } else {
        echo "Update failed. Please try again.";
    }

    $stmt->close();
}

$conn->close();
?>

<form method="post">
    <label for="approved_by_teacher_id">Approved By Teacher ID:</label>
    <input type="text" name="approved_by_teacher_id" id="approved_by_teacher_id">
    <button type="submit" name="update_button">Update</button>
</form>
