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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organization Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Welcome to the Organization Dashboard</h2>
        <p>Manage your projects, view applications, and track progress efficiently.</p>

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs" id="dashboardTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link <?php echo !isset($_GET['tab']) || $_GET['tab'] === 'profile' ? 'active' : ''; ?>" href="?tab=profile">Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo isset($_GET['tab']) && $_GET['tab'] === 'projects' ? 'active' : ''; ?>" href="?tab=projects">Projects</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo isset($_GET['tab']) && $_GET['tab'] === 'applications' ? 'active' : ''; ?>" href="?tab=applications">Applications</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-danger" href="logout.php">Logout</a>
            </li>
        </ul>

        <!-- Dynamic Content -->
        <div class="mt-3">
            <?php
            // Include the appropriate content based on the selected tab
            if (!isset($_GET['tab']) || $_GET['tab'] === 'profile') {
                include 'organization_profile.php';
            } elseif ($_GET['tab'] === 'projects') {
                include 'organization_projects.php';
            } elseif ($_GET['tab'] === 'applications') {
                include 'organization_applications.php';
            } else {
                echo "<p>Invalid tab selected.</p>";
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
