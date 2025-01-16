<?php
session_start();
// Ensure only Admin can access this functionality
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized access");
}

// Database connection
$conn = new mysqli("localhost", "root", "", "virtual_marketplace");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if ID is provided
if (isset($_GET['id'])) {
    $project_id = intval($_GET['id']);
    $sql = "DELETE FROM projects WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $project_id);

    if ($stmt->execute()) {
        header("Location: view_projects.php?msg=project Deleted Successfully");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
