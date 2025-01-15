<h4>Manage Projects</h4>
<a href="add_project.php" class="btn btn-success mb-3">Create New Project</a>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Duration</th>
            <th>Compensation</th>
            <th>Required Skills</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $org_id = $_SESSION['id'];
        $sql = "SELECT id, title, description, duration, compensation, required_skills, status FROM projects WHERE organization = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $org_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['title']}</td>
                    <td>{$row['description']}</td>
                    <td>{$row['duration']}</td>
                    <td>{$row['compensation']}</td>
                    <td>{$row['required_skills']}</td>
                    <td>{$row['status']}</td>
                    <td>
                        <a href='edit_project.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                        <a href='delete_project.php?id={$row['id']}' class='btn btn-danger btn-sm'>Delete</a>
                    </td>
                  </tr>";
        }
        $stmt->close();
        ?>
    </tbody>
</table>
