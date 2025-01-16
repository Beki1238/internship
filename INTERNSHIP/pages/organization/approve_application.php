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

// Handle reject request
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $application_id = $_GET['id'];

    // Update application status
    $sql = "UPDATE applications SET status = 'rejected' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $application_id);

    if ($stmt->execute()) {
        echo "Application rejected successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
