<?php
include('includes/header.php');
include('server/connection.php'); // Assuming you already have this file for DB connection

// Logout logic
if (isset($_GET['logout'])) {
    if (isset($_SESSION['loggin_in'])) {
        unset($_SESSION['loggin_in']);
        unset($_SESSION['user_username']);
        unset($_SESSION['user_name']);
        header('Location: http://localhost/BIZCON_WEBSITE/login.php'); // Redirect to the desired login page
        exit;
    }
}

// Admin account creation logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input
    $admin_username = mysqli_real_escape_string($conn, $_POST['admin_name']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Check if the new password matches the confirmation
    if ($new_password === $confirm_password) {
        // Hash the new password using MD5
        $hashed_password = md5($new_password);

        // Insert the new admin into the users table
        $insert_query = "INSERT INTO users (user_name, user_username, user_password, user_role) VALUES ('ADMIN', '$admin_username', '$hashed_password', 'admin')";

        if (mysqli_query($conn, $insert_query)) {
            echo "<div class='alert alert-success'>Admin account created successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error creating admin account: " . mysqli_error($conn) . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Password and confirmation do not match.</div>";
    }
}
?>

<div class="container">
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header p-3">
                    <h4 class="mb-0">Settings</h4>
                </div>
                <div class="card-body">
                    <form action="settings.php" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Admin Username</label>
                                <input type="text" name="admin_name" class="form-control border border-dark" 
                                    style="border-width: 1px !important; padding: 8px 12px;" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" name="new_password" class="form-control border border-dark" 
                                    style="border-width: 1px !important; padding: 8px 12px;" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" name="confirm_password" class="form-control border border-dark" 
                                    style="border-width: 1px !important; padding: 8px 12px;" required>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success">Create Admin</button>
                            </div>
                        </div>
                    </form>

                    <!-- Logout Button -->
                    <a href="settings.php?logout=true" class="btn btn-danger mt-3">Logout</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
