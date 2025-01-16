<?php
session_start();

// Ensure only Advisors can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'advisor') {
    die("Unauthorized access.");
}

// Database connection
$conn = new mysqli("localhost", "root", "", "virtual_marketplace");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the logged-in advisor's ID
$advisor_id = $_SESSION['id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advisor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Advisor Dashboard</h2>
        <p>Welcome to your dashboard. Manage and view the progress of your assigned students.</p>

        <div class="mt-4">
            <a href="advisor_manage_students.php" class="btn btn-primary">Manage Students</a>
        </div>
    </div>
</body>
</html>
