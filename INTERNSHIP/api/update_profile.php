<?php
session_start();
// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access");
}

// Database connection
$conn = new mysqli("localhost", "root", "", "virtual_marketplace");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $bio = $_POST['bio'];
    $resume_link = $_POST['resume_link'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;

    // Update query
    $sql = "UPDATE users SET name = ?, email = ?, bio = ?, resume_link = ?"
         . ($password ? ", password = ?" : "")
         . " WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($password) {
        $stmt->bind_param("sssssi", $name, $email, $bio, $resume_link, $password, $user_id);
    } else {
        $stmt->bind_param("ssssi", $name, $email, $bio, $resume_link, $user_id);
    }

    if ($stmt->execute()) {
        echo "Profile updated successfully!";
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
