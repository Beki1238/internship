<?php
$conn = new mysqli("localhost", "root", "", "virtual_marketplace");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Fetch total counts
$total_users = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];
$total_projects = $conn->query("SELECT COUNT(*) AS count FROM projects")->fetch_assoc()['count'];
$total_applications = $conn->query("SELECT COUNT(*) AS count FROM applications")->fetch_assoc()['count'];

// Fetch classified user counts
$total_students = $conn->query("SELECT COUNT(*) AS count FROM users WHERE role = 'student'")->fetch_assoc()['count'];
$total_advisors = $conn->query("SELECT COUNT(*) AS count FROM users WHERE role = 'advisor'")->fetch_assoc()['count'];
$total_organizations = $conn->query("SELECT COUNT(*) AS count FROM users WHERE role = 'organization'")->fetch_assoc()['count'];

// Fetch classified project counts
$paid_projects = $conn->query("SELECT COUNT(*) AS count FROM projects WHERE compensation IS NOT NULL AND compensation != ''")->fetch_assoc()['count'];
$unpaid_projects = $conn->query("SELECT COUNT(*) AS count FROM projects WHERE compensation IS NULL OR compensation = ''")->fetch_assoc()['count'];

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Platform Analytics</h2>
        <div class="row mt-4">
            <div class="col-lg-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h4 class="card-title">Total Users</h4>
                        <p class="card-text"><?php echo $total_users; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h4 class="card-title">Total Projects</h4>
                        <p class="card-text"><?php echo $total_projects; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h4 class="card-title">Total Applications</h4>
                        <p class="card-text"><?php echo $total_applications; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>


            <!-- User Classification -->
            <div class="mt-5">
            <h3>User Classification</h3>
            <div class="row">
                <div class="col-lg-4">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <h5 class="card-title">Students</h5>
                            <p class="card-text"><?php echo $total_students; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card text-white bg-dark">
                        <div class="card-body">
                            <h5 class="card-title">Advisors</h5>
                            <p class="card-text"><?php echo $total_advisors; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card text-white bg-secondary">
                        <div class="card-body">
                            <h5 class="card-title">Organizations</h5>
                            <p class="card-text"><?php echo $total_organizations; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <!-- Project Classification -->
        <div class="mt-5">
            <h3>Project Classification</h3>
            <div class="row">
                <div class="col-lg-6">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h5 class="card-title">Paid Projects</h5>
                            <p class="card-text"><?php echo $paid_projects; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card text-white bg-warning">
                        <div class="card-body">
                            <h5 class="card-title">Unpaid Projects</h5>
                            <p class="card-text"><?php echo $unpaid_projects; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>


</body>
</html>
