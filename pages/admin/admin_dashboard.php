<?php
session_start();
// Ensure the user is an admin
if ($_SESSION['role'] !== 'admin') {
    die("Unauthorized access.");
}

// Database connection
$conn = new mysqli("localhost", "root", "", "virtual_marketplace");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch total counts
$total_users = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];
$total_projects = $conn->query("SELECT COUNT(*) AS count FROM projects")->fetch_assoc()['count'];
$total_applications = $conn->query("SELECT COUNT(*) AS count FROM applications")->fetch_assoc()['count'];

// Fetch advisors
$advisors = $conn->query("SELECT id, name, email FROM users WHERE role = 'advisor'");

// Handle assigning advisor
$assign_success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'], $_POST['advisor_id'])) {
    $student_id = $_POST['student_id'];
    $advisor_id = $_POST['advisor_id'];

    // Ensure the IDs exist
    $student_check = $conn->query("SELECT id FROM students WHERE id = $student_id");
    $advisor_check = $conn->query("SELECT id FROM users WHERE id = $advisor_id AND role = 'advisor'");

    if ($student_check->num_rows > 0 && $advisor_check->num_rows > 0) {
        // Update advisor ID for the student
        $stmt = $conn->prepare("UPDATE students SET advisor_id = ? WHERE id = ?");
        $stmt->bind_param("ii", $advisor_id, $student_id);
        if ($stmt->execute()) {
            $assign_success = "Advisor successfully assigned to the student!";
        } else {
            $assign_success = "Error assigning advisor: " . $stmt->error;
        }
    } else {
        $assign_success = "Invalid student or advisor ID.";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Admin Dashboard</h1>

        <!-- System Overview -->
        <div class="row mt-4">
            <div class="col-lg-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h4 class="card-title">Total Users</h4>
                        <p class="card-text"><?php echo $total_users; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h4 class="card-title">Total Projects</h4>
                        <p class="card-text"><?php echo $total_projects; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h4 class="card-title">Total Applications</h4>
                        <p class="card-text"><?php echo $total_applications; ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Functionalities -->
        <div class="mt-5">
            <h3>Manage Users</h3>
            <div class="btn-group">
                <a href="register_user.php" class="btn btn-primary">Register User</a>
                <a href="view_users.php" class="btn btn-secondary">View All Users</a>
            </div>
        </div>

        <div class="mt-5">
            <h3>Manage Projects</h3>
            <div class="btn-group">
                <a href="view_projects.php" class="btn btn-primary">View Projects</a>
                <a href="approve_projects.php" class="btn btn-secondary">Approve Projects</a>
            </div>
        </div>

        <div class="mt-5">
            <h3>Analytics</h3>
            <a href="analytics.php" class="btn btn-info">View Analytics</a>
        </div>

        <!-- Assign Advisor Button -->
        <div class="mt-5">
            <h3>Assign Advisor</h3>
            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#assignAdvisorForm" aria-expanded="false" aria-controls="assignAdvisorForm">
                Open Assign Advisor Form
            </button>

            <div class="collapse mt-3" id="assignAdvisorForm">
                <h4>Available Advisors</h4>
                <ul class="list-group mb-3">
                    <?php while ($advisor = $advisors->fetch_assoc()): ?>
                        <li class="list-group-item">
                            ID: <?php echo $advisor['id']; ?> | 
                            Name: <?php echo htmlspecialchars($advisor['name']); ?> | 
                            Email: <?php echo htmlspecialchars($advisor['email']); ?>
                        </li>
                    <?php endwhile; ?>
                </ul>

                <h4>Assign Advisor to Student</h4>
                <?php if ($assign_success): ?>
                    <div class="alert alert-info">
                        <?php echo $assign_success; ?>
                    </div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label for="student_id" class="form-label">Student ID:</label>
                        <input type="number" name="student_id" id="student_id" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="advisor_id" class="form-label">Advisor ID:</label>
                        <input type="number" name="advisor_id" id="advisor_id" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Assign Advisor</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
