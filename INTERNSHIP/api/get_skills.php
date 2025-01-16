<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "virtual_marketplace");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch skills for the logged-in student
$student_id = 1; // Replace with dynamic user ID from session or authentication
$sql = "SELECT skill_name, proficiency 
        FROM skills 
        WHERE student_id = $student_id";

$result = $conn->query($sql);

$skills = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $skills[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($skills);

$conn->close();
?>
