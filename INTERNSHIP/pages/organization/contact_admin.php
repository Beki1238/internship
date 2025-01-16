<?php
session_start();
// Ensure organization access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'organization') {
    die("Unauthorized access");
}

// Database connection
$conn = new mysqli("localhost", "root", "", "virtual_marketplace");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get student ID from query parameter
$student_id = $_GET['student_id'] ?? null;

if ($student_id) {
    // Fetch student and advisor details
    $sql = "
        SELECT 
            s.name AS student_name, 
            st.skill_name, 
            st.proficiency, 
            a.name AS advisor_name
        FROM 
            students st
        JOIN 
            users s ON st.user_id = s.id
        LEFT JOIN 
            users a ON st.assigned_advisor = a.id
        WHERE 
            st.id = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
        // Display student and advisor details (or send to admin)
        echo "<h2>Contact Admin for " . htmlspecialchars($student['student_name']) . "</h2>";
        echo "<p>Skills: " . htmlspecialchars($student['skill_name']) . "</p>";
        echo "<p>Proficiency: " . htmlspecialchars($student['proficiency']) . "</p>";
        echo "<p>Assigned Advisor: " . (!empty($student['advisor_name']) ? htmlspecialchars($student['advisor_name']) : 'Not Assigned') . "</p>";
    } else {
        echo "<p>No student found.</p>";
    }

    $stmt->close();
}

$conn->close();
?>
