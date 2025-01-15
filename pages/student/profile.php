<?php
session_start();
$conn = new mysqli("localhost", "root", "", "virtual_marketplace");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['id']; // User ID from session

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $skills = implode(',', $_POST['skills']); // Combine selected skills into a comma-separated string
    $proficiency = $_POST['proficiency'];

    // Update user name and password in the `users` table
    $stmt_user = $conn->prepare("UPDATE users SET name = ?, password = ? WHERE id = ?");
    $stmt_user->bind_param("ssi", $name, $password, $user_id);
    if (!$stmt_user->execute()) {
        die("Error updating user: " . $stmt_user->error);
    }

    // Update skills and proficiency in the `students` table
    $stmt_skills = $conn->prepare("UPDATE students SET skill_name = ?, proficiency = ? WHERE user_id = ?");
    $stmt_skills->bind_param("ssi", $skills, $proficiency, $user_id);
    if (!$stmt_skills->execute()) {
        die("Error updating skills: " . $stmt_skills->error);
    }

    echo "Profile updated successfully!";
}

// Fetch current profile data (name from `users`, skills and proficiency from `students`)
$stmt = $conn->prepare("
    SELECT u.name, s.skill_name, s.proficiency 
    FROM users u 
    JOIN students s ON u.id = s.user_id 
    WHERE u.id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();

if (!$profile) {
    die("Profile not found for user ID: " . $user_id);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
</head>
<body>
    <h1>Edit Profile</h1>
    <form method="POST">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($profile['name']); ?>" required><br>

        <label>Password:</label>
        <input type="password" name="password" required><br>

        <label>Skills:</label><br>
        <select name="skills[]" multiple>
            <option value="PHP" <?php echo strpos($profile['skill_name'], 'PHP') !== false ? 'selected' : ''; ?>>PHP</option>
            <option value="Python" <?php echo strpos($profile['skill_name'], 'Python') !== false ? 'selected' : ''; ?>>Python</option>
            <option value="Machine Learning" <?php echo strpos($profile['skill_name'], 'Machine Learning') !== false ? 'selected' : ''; ?>>Machine Learning</option>
            <option value="React" <?php echo strpos($profile['skill_name'], 'React') !== false ? 'selected' : ''; ?>>React</option>
            <option value="JavaScript" <?php echo strpos($profile['skill_name'], 'JavaScript') !== false ? 'selected' : ''; ?>>JavaScript</option>
            <option value="HTML" <?php echo strpos($profile['skill_name'], 'HTML') !== false ? 'selected' : ''; ?>>HTML</option>
        </select><br>

        <label>Proficiency:</label>
        <input type="text" name="proficiency" value="<?php echo htmlspecialchars($profile['proficiency']); ?>" required><br>

        <button type="submit">Update Profile</button>
    </form>
</body>
</html>
