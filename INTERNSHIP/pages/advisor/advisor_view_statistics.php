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

// Get the student ID from the query string
if (!isset($_GET['student_id'])) {
    die("Student ID is required.");
}
$student_id = $_GET['student_id'];

// Fetch student details
$sql = "
    SELECT 
        u.name AS student_name, 
        u.email, 
        p.title AS project_title, 
        s.skill_name, 
        s.proficiency, 
        s.status, 
        a.name AS advisor_name 
    FROM 
        students s
    JOIN 
        users u ON s.user_id = u.id
    LEFT JOIN 
        projects p ON s.project_id = p.id
    LEFT JOIN 
        users a ON s.assigned_advisor = a.id
    WHERE 
        s.id = ?
";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Query preparation failed: " . $conn->error);
}
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    die("Student not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Student Statistics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Student Statistics</h2>
        <table class="table table-bordered">
            <tr>
                <th>Name</th>
                <td><?php echo htmlspecialchars($student['student_name']); ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo htmlspecialchars($student['email']); ?></td>
            </tr>
            <tr>
                <th>Project Title</th>
                <td><?php echo htmlspecialchars($student['project_title'] ?? 'Not Assigned'); ?></td>
            </tr>
            <tr>
                <th>Skills</th>
                <td><?php echo htmlspecialchars($student['skill_name']); ?></td>
            </tr>
            <tr>
                <th>Proficiency</th>
                <td><?php echo htmlspecialchars($student['proficiency']); ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?php echo htmlspecialchars(ucfirst($student['status'])); ?></td>
            </tr>
            <tr>
                <th>Advisor</th>
                <td><?php echo htmlspecialchars($student['advisor_name'] ?? 'Not Assigned'); ?></td>
            </tr>
        </table>

        <a href="advisor_manage_students.php" class="btn btn-primary">Back to Manage Students</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
