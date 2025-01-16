<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "virtual_marketplace");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch recommendations for the logged-in student
$student_id = 1; // Replace with dynamic user ID after authentication
$sql = "SELECT projects.title, projects.organization, recommendations.match_score 
        FROM recommendations 
        JOIN projects ON recommendations.project_id = projects.id 
        WHERE recommendations.student_id = $student_id 
        ORDER BY recommendations.match_score DESC";

$result = $conn->query($sql);

$recommendations = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $recommendations[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($recommendations);

$conn->close();
?>
<div class="section recommendations">
    <h2>Recommended Projects</h2>
    <?php
    // Fetch recommendations from the API
    $recommendations_json = file_get_contents("http://localhost/get_recommendations.php");
    $recommendations = json_decode($recommendations_json, true);

    foreach ($recommendations as $project) {
        echo "<div class='project'>";
        echo "<strong>" . htmlspecialchars($project['title']) . "</strong><br>";
        echo "Organization: " . htmlspecialchars($project['organization']) . "<br>";
        echo "Match: " . htmlspecialchars($project['match_score']) . "%<br>";
        echo "<button>View Details</button> <button>Apply</button>";
        echo "</div>";
    }
    ?>
</div>
