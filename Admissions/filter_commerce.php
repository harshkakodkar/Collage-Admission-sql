<?php
session_start();

include 'db_connection.php';

function sanitize($data) {
    return htmlspecialchars(strip_tags($data));
}

$status = isset($_GET['status']) ? sanitize($_GET['status']) : '';

$sql = "SELECT s.StudentID, s.FirstName, s.LastName 
        FROM students s
        INNER JOIN admissions a ON s.StudentID = a.StudentID
        INNER JOIN courses c ON a.CourseID = c.CourseID
        WHERE c.CourseName = 'Commerce'";

if ($status !== 'all') {
    $status = $conn->real_escape_string($status);
    $sql .= " AND a.AdmissionStatus = '$status'";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<a href="#" class="list-group-item list-group-item-action" onclick="storeStudentID(' . $row["StudentID"] . ')">' . $row["FirstName"] . ' ' . $row["LastName"] . ' <button class="btn btn-primary btn-sm float-right">View Form</button></a>';
    }
} else {
    echo '<p>No students found for the selected admission status and course.</p>';
}

$conn->close();

?>
<script>
function storeStudentID(studentID) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log("Student ID stored in session: " + studentID);
            window.location.href = "student_form.php";
        }
    };
    xhttp.open("GET", "store_student_id_commerce.php?studentID=" + studentID, true);
    xhttp.send();
}
</script>
