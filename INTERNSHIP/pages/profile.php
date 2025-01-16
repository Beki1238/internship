<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Update Profile</h2>
        <form action="../api/update_profile.php" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" id="name" value="John Doe" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" id="email" value="johndoe@example.com" required>
            </div>
            <div class="mb-3">
                <label for="bio" class="form-label">Bio</label>
                <textarea name="bio" class="form-control" id="bio" rows="3" placeholder="Write a brief bio..."></textarea>
            </div>
            <div class="mb-3">
                <label for="resume_link" class="form-label">Resume Link</label>
                <input type="url" name="resume_link" class="form-control" id="resume_link" placeholder="http://example.com/resume">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">New Password</label>
                <input type="password" name="password" class="form-control" id="password">
                <small class="form-text text-muted">Leave blank to keep your current password.</small>
            </div>
            <button type="submit" class="btn btn-success">Update Profile</button>
        </form>
    </div>
</body>
</html>
