<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "virtual_marketplace");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the logged-in student's ID (from session)
$student_id = $_SESSION['id'];

// Fetch user details
$sql_user = "SELECT name FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $student_id);
$stmt_user->execute();
$user_result = $stmt_user->get_result();
$user = $user_result->fetch_assoc();

// Fetch student's skills
$stmt_skills = $conn->prepare("SELECT skill_name FROM students WHERE user_id = ?");
$stmt_skills->bind_param("i", $student_id);
$stmt_skills->execute();
$result_skills = $stmt_skills->get_result();

$student_skills = [];
while ($row = $result_skills->fetch_assoc()) {
    $student_skills[] = $row['skill_name'];
}

// Convert skills array to a comma-separated string for SQL
$skills_placeholder = "'" . implode("', '", $student_skills) . "'";

// Fetch recommended projects with organization name
$sql_projects = "
    SELECT 
        p.title, 
        u.name AS organization_name, 
        p.description, 
        p.duration, 
        p.compensation, 
        p.required_skills 
    FROM 
        projects p 
    JOIN 
        users u 
    ON 
        p.organization = u.id
    WHERE 
        (" . implode(" OR ", array_map(fn($skill) => "p.required_skills LIKE '%$skill%'", $student_skills)) . ")
    AND 
        p.status = 'approved'
";

$projects_result = $conn->query($sql_projects);

if (!$projects_result) {
    die("Error in query: " . $conn->error);
}

// Fetch application statuses
$sql_applications = "SELECT p.title, a.status FROM applications a JOIN projects p ON a.project_id = p.id WHERE a.student_id = ?";
$stmt_applications = $conn->prepare($sql_applications);
$stmt_applications->bind_param("i", $student_id);
$stmt_applications->execute();
$applications_result = $stmt_applications->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .dashboard-header {
            background-color: #343a40;
            color: white;
            padding: 1rem;
            text-align: center;
        }
        .skills-badge {
            display: inline-block;
            margin: 0.3rem;
            padding: 0.5rem 1rem;
            background-color: #17a2b8;
            color: white;
            border-radius: 5px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">University Internship Portal</a>
            <div style="position: absolute; top: 10px; right: 10px;">
                <a href="profile.php">
                    <img src="profile_icon.png" alt="Profile" style="width: 40px; height: 40px; border-radius: 50%;">
                </a>
            </div>
        </div>
    </nav>

    <header class="dashboard-header">
        <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h1>
        <p>Track your progress, explore projects, and grow your skills.</p>
    </header>

    <div class="container mt-4">
        <div class="row">
            <!-- Recommended Projects -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">Recommended Projects</div>
                    <div class="card-body">
                        <?php if ($projects_result->num_rows > 0): ?>
                            <?php while ($project = $projects_result->fetch_assoc()): ?>
                                <div class="mb-3">
                                    <h5><?php echo htmlspecialchars($project['title']); ?></h5>
                                    <p>Organization: <?php echo htmlspecialchars($project['organization_name']); ?></p>
                                    <p>Description: <?php echo htmlspecialchars($project['description']); ?></p>
                                    <p>Duration: <?php echo htmlspecialchars($project['duration']); ?> months</p>
                                    <p>Compensation: <?php echo htmlspecialchars($project['compensation']); ?></p>
                                    <button class="btn btn-outline-primary btn-sm">View Details</button>
                                    <button class="btn btn-outline-success btn-sm">Apply</button>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>No recommended projects available.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Skills Summary -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header bg-info text-white">Skills Summary</div>
                    <div class="card-body">
                        <?php if (!empty($student_skills)): ?>
                            <?php foreach ($student_skills as $skill): ?>
                                <span class="skills-badge"><?php echo htmlspecialchars($skill); ?></span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No skills added.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Application Status -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header bg-success text-white">Application Status</div>
                    <div class="card-body">
                        <ul class="list-group">
                            <?php if ($applications_result->num_rows > 0): ?>
                                <?php while ($application = $applications_result->fetch_assoc()): ?>
                                    <li class="list-group-item">
                                        <?php echo htmlspecialchars($application['title']); ?> - 
                                        <span class="badge bg-<?php echo $application['status'] === 'Accepted' ? 'success' : 'warning'; ?>">
                                            <?php echo htmlspecialchars($application['status']); ?>
                                        </span>
                                    </li>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p>No applications found.</p>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
