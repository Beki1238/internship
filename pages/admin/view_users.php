<?php
session_start();
// Ensure only Admin can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized access");
}

// Database connection
$conn = new mysqli("localhost", "root", "", "virtual_marketplace");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize search and filter variables
$search = $_GET['search'] ?? '';
$role = $_GET['role'] ?? '';

// Build the query dynamically
$sql = "SELECT id, name, email, role FROM users WHERE 1=1";
if (!empty($search)) {
    $sql .= " AND (name LIKE ? OR email LIKE ?)";
}
if (!empty($role)) {
    $sql .= " AND role = ?";
}

// Prepare the statement to prevent SQL injection
$stmt = $conn->prepare($sql);

if (!empty($search) && !empty($role)) {
    $likeSearch = "%$search%";
    $stmt->bind_param("sss", $likeSearch, $likeSearch, $role);
} elseif (!empty($search)) {
    $likeSearch = "%$search%";
    $stmt->bind_param("ss", $likeSearch, $likeSearch);
} elseif (!empty($role)) {
    $stmt->bind_param("s", $role);
}

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View All Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>All Registered Users</h2>

        <!-- Search and Filter Form -->
        <form method="GET" action="view_users.php" class="mb-4">
            <div class="row">
                <div class="col-md-6">
                    <input 
                        type="text" 
                        name="search" 
                        class="form-control" 
                        placeholder="Search by name or email" 
                        value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="col-md-4">
                    <select name="role" class="form-select">
                        <option value="">All Roles</option>
                        <option value="student" <?php echo $role === 'student' ? 'selected' : ''; ?>>Student</option>
                        <option value="advisor" <?php echo $role === 'advisor' ? 'selected' : ''; ?>>Advisor</option>
                        <option value="organization" <?php echo $role === 'organization' ? 'selected' : ''; ?>>Organization</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                </div>
            </div>
        </form>

        <!-- User Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                <th>Image</th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>";
                        if (!empty($row['profile_image'])) {
                            echo "<img src='" . htmlspecialchars($row['profile_image']) . "' alt='Profile' width='50' height='50'>";
                        } else {
                            echo "No Image";
                        }
                        echo "</td>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['role']) . "</td>";
                        echo "<td>
                                <a href='edit_user.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                <a href='delete_user.php?id=" . $row['id'] . "' 
                                class='btn btn-danger btn-sm' 
                                onclick='return confirm(\"Are you sure you want to delete this user?\");'>
                                Delete
                                </a>
                                <a href='export_users.php' class='btn btn-info btn-sm'>Export Users to CSV</a>
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>No users found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
