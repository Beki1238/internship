<?php
session_start();
$conn = new mysqli("localhost", "root", "", "virtual_marketplace");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check credentials
    $sql = "SELECT id, password, role FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            switch ($user['role']) {
                case 'admin':
                    header("Location: ../pages/admin_dashboard.php");
                    break;
                case 'advisor':
                    header("Location: ../pages/advisor_dashboard.php");
                    break;
                case 'organization':
                    header("Location: ../pages/org_dashboard.php");
                    break;
                case 'student':
                    header("Location: ../pages/student_dashboard.php");
                    break;
                default:
                    echo "Invalid role.";
                    break;
            }
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with this email.";
    }

    $stmt->close();
}

$conn->close();
?>
