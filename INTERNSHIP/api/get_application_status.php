<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "virtual_marketplace");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch application statuses for the logged-in student
$student_id = 1; // Replace with dynamic user ID from session or authentication
$sql = "SELECT projects.title, applications.status 
        FROM applications 
        JOIN projects ON applications.project_id = projects.id 
        WHERE applications.student_id = $student_id";

$result = $conn->query($sql);

$applications = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $applications[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($applications);

$conn->close();
?>
