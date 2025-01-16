<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "virtual_marketplace");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Secure password hashing
    $role = $_POST['role'];

    
 // Validate role
 $allowed_roles = ['student', 'advisor', 'organization', 'admin'];
 if (!in_array($role, $allowed_roles)) {
     die("Invalid role selected.");
 }
    // Insert user into database
    $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $password, $role);

    if ($stmt->execute()) {
        echo "Registration successful! Please log in.";
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
