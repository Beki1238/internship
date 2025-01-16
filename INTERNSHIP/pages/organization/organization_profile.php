<h4>Profile Management</h4>
<p>Update your organization details and password here.</p>
<!-- Profile Update Form -->
<form action="update_profile.php" method="POST">
    <div class="mb-3">
        <label for="name" class="form-label">Organization Name</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password">
    </div>
    <button type="submit" class="btn btn-primary">Update Profile</button>
</form>
