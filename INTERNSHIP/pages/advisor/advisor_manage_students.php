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

// Fetch students assigned to this advisor
$sql = "
    SELECT 
        s.id AS student_id, 
        u.name AS student_name, 
        u.email, 
        p.title AS project_title, 
        s.status 
    FROM 
        students s 
    JOIN 
        users u ON s.user_id = u.id 
    LEFT JOIN 
        projects p ON s.project_id = p.id 
    WHERE 
        s.assigned_advisor = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $advisor_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Assigned Students</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Project Title</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['student_id']) . "</td>
                                <td>" . htmlspecialchars($row['student_name']) . "</td>
                                <td>" . htmlspecialchars($row['email']) . "</td>
                                <td>" . htmlspecialchars($row['project_title']) . "</td>
                                <td>" . htmlspecialchars($row['status']) . "</td>
                                <td>
                                    <a href='advisor_view_statistics.php?student_id=" . htmlspecialchars($row['student_id']) . "' class='btn btn-info btn-sm'>View Statistics</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>No students assigned.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php $stmt->close(); $conn->close(); ?>
