<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access");
}

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Validate file type
if (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
    die("Invalid file type. Only JPG, PNG, JPEG, and GIF are allowed.");
}

// Move the uploaded file
if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
    echo "Image uploaded successfully!";
    // Update user record in the database (add a `profile_image` column to the `users` table)
    $conn = new mysqli("localhost", "root", "", "virtual_marketplace");
    $userId = $_SESSION['user_id'];
    $conn->query("UPDATE users SET profile_image='$target_file' WHERE id=$userId");
    $conn->close();
} else {
    echo "Sorry, there was an error uploading your file.";
}
