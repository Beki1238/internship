<?php
session_start();
// Ensure only organizations can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'organization') {
    die("Unauthorized access");
}

// Database connection
$conn = new mysqli("localhost", "root", "", "virtual_marketplace");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $skills = $_POST['skills'];
    $duration = $_POST['duration'];

    // Update query
    $sql = "UPDATE projects SET title = ?, description = ?, skills_required = ?, duration = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $title, $description, $skills, $duration, $project_id);

    if ($stmt->execute()) {
        echo "Project updated successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
