<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Virtual Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation bar with a login button -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Virtual Marketplace</a>
            <div class="d-flex">
                <a href="login.php" class="btn btn-outline-primary">Login</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <header class="text-center mb-5">
            <h1>Welcome to the Virtual Internship and Project Marketplace</h1>
            <p>Your one-stop solution for connecting students, advisors, and organizations for internships and projects.</p>
        </header>

        <!-- Main features section -->
        <div class="row mb-5">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body text-center">
                        <h4>For Students</h4>
                        <p>Looking for internships? Find opportunities to gain real-world experience and boost your career.</p>
                        <a href="student_opportunities.php" class="btn btn-success">Explore Opportunities</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body text-center">
                        <h4>For Organizations</h4>
                        <p>Looking for talented interns? Post your projects and find the right candidates.</p>
                        <a href="pages/organization_dashboard.php" class="btn btn-warning">Post a Project</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action Section -->
        <div class="text-center">
            <h2>Why Choose Us?</h2>
            <p>
                Our platform bridges the gap between students eager to learn and organizations seeking talent. 
                Advisors can guide students and track their progress, ensuring a seamless internship experience for everyone involved.
            </p>
        </div>
    </div>


<!-- Footer -->
<footer class="bg-light text-center text-lg-start mt-5">
    <div class="text-center p-3">
        © 2024 DireDawa University. All rights reserved.
        <br>
        <a href="privacy.php" class="text-dark">Privacy Policy</a> | 
        <a href="terms.php" class="text-dark">Terms of Service</a>
        <br>
        <small>Powered by LYUnetics</small>
    </div>
</footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
