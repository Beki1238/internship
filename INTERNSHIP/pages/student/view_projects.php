<?php
$conn = new mysqli("localhost", "root", "", "virtual_marketplace");
// Ensure only Admin can access this page
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//     die("Unauthorized access");
// }

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, title, organization, status FROM projects";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View All Projects</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>All Projects</h2>
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Organization</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['title']}</td>
                                <td>{$row['organization']}</td>
                                <td>{$row['status']}</td>
                                <td>
                                    <a href='approve_projects.php?id={$row['id']}' class='btn btn-success btn-sm'>Approve</a>
                                    <a href='delete_projects.php?id={$row['id']}' class='btn btn-danger btn-sm'>Delete</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No projects found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
