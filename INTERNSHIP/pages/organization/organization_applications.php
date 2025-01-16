<?php

// Ensure organization access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'organization') {
    die("Unauthorized access");
}

// Database connection
$conn = new mysqli("localhost", "root", "", "virtual_marketplace");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure organization ID is set from session
$org_id = $_SESSION['id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organization - Student Information</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Student Information</h2>
        <p>Below is the list of students with detailed information, including their assigned advisor:</p>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Project Title</th>
                    <th>Skills</th>
                    <th>Proficiency</th>
                    <th>Status</th>
                    <th>Assigned Advisor</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query to fetch student information
                $sql = "
                    SELECT 
                        s.name AS student_name,
                        p.title AS project_title, 
                        st.skill_name, 
                        st.proficiency, 
                        st.status,
                        a.name AS advisor_name, 
                        st.id AS student_id
                    FROM 
                        students st
                    JOIN 
                        users s ON st.user_id = s.id
                    LEFT JOIN 
                        users a ON st.assigned_advisor = a.id
                    LEFT JOIN 
                        projects p ON st.project_id = p.id
                    WHERE 
                        s.role = 'student'
                ";
                $result = $conn->query($sql);

                // Check if students exist
                if ($result && $result->num_rows > 0) {
                    // Fetch and display each student
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['student_name']) . "</td>
                                <td>" . htmlspecialchars($row['project_title'] ?? 'No Project') . "</td>
                                <td>" . htmlspecialchars($row['skill_name'] ?? 'Not Provided') . "</td>
                                <td>" . htmlspecialchars($row['proficiency'] ?? 'Not Provided') . "</td>
                                <td>" . ucfirst(htmlspecialchars($row['status'] ?? 'N/A')) . "</td>
                                <td>" . (!empty($row['advisor_name']) ? htmlspecialchars($row['advisor_name']) : 'Not Assigned') . "</td>
                                <td>
                                    <a href='approve_application.php?id=" . htmlspecialchars($row['student_id']) . "' class='btn btn-success btn-sm'>Approve</a>
                                    <a href='reject_application.php?id=" . htmlspecialchars($row['student_id']) . "' class='btn btn-danger btn-sm'>Reject</a>
                                    <a href='contact_admin.php?student_id=" . htmlspecialchars($row['student_id']) . "' class='btn btn-info btn-sm'>Contact Admin</a>
                                </td>
                              </tr>";
                    }
                } else {
                    // Show message if no students are found
                    echo "<tr><td colspan='7' class='text-center'>No students found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
