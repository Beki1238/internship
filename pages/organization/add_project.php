<?php
// Start session and include database connection
session_start();
$conn = new mysqli("localhost", "root", "", "virtual_marketplace");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure only organizations can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'organization') {
    die("Unauthorized access");
}

// Check if the session ID is set
if (!isset($_SESSION['id'])) {
    die("Session ID not set. Please log in again.");
}

$organization_id = $_SESSION['id'];

// Handle form submission to add a project
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $duration = $_POST['duration'];
    $compensation = $_POST['compensation'];
    $required_skills = $_POST['required_skills'];

    // Validate inputs
    if (empty($title) || empty($description) || empty($duration) || empty($compensation) || empty($required_skills)) {
        $error = "All fields are required!";
    } else {
        // Insert into the database
        $stmt = $conn->prepare("INSERT INTO projects (title, description, duration, compensation, required_skills, organization, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
        $stmt->bind_param("sssdsi", $title, $description, $duration, $compensation, $required_skills, $organization_id);

        if ($stmt->execute()) {
            $success = "Project created successfully!";
        } else {
            $error = "Failed to create project: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Create a New Project</h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Project Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="duration" class="form-label">Duration (Months)</label>
                <input type="number" class="form-control" id="duration" name="duration" required>
            </div>
            <div class="mb-3">
                <label for="compensation" class="form-label">Compensation ($)</label>
                <input type="number" class="form-control" id="compensation" name="compensation" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="required_skills" class="form-label">Required Skills</label>
                <input type="text" class="form-control" id="required_skills" name="required_skills" required>
            </div>
            <button type="submit" class="btn btn-primary">Create Project</button>
        </form>
    </div>
</body>
</html>
