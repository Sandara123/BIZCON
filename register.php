<?php
session_start();
include('server/connection.php');

if (isset($_SESSION['loggin_in'])) {
    header('location: account.php');
    exit;
}

if (isset($_POST['register'])) {

    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($password !== $confirmPassword) {
        header('location: register.php?error=password do not match');
        exit;
    }

    // New password validation (minimum 8 characters, 1 uppercase, 1 special character)
    $uppercase = preg_match('@[A-Z]@', $password);
    $specialChar = preg_match('@[^\w]@', $password);
    $number = preg_match('@[0-9]@', $password);

    if (strlen($password) < 8) {
        header('location: register.php?error=password must be at least 8 characters');
        exit;
    } else if (!$uppercase || !$specialChar || !$number) {
        header('location: register.php?error=password must contain at least one uppercase letter, one special character, and one number');
        exit;
    }

    // Check if username already exists
    $stmt1 = $conn->prepare("SELECT count(*) FROM users WHERE user_username=?");
    $stmt1->bind_param('s', $username);
    $stmt1->execute();
    $stmt1->bind_result($num_rows);
    $stmt1->store_result();
    $stmt1->fetch();

    if ($num_rows != 0) {
        header('location: register.php?error=username is already taken');
        exit;
    } else {
        // Insert user into the database
        $stmt = $conn->prepare("INSERT INTO users (user_name, user_username, user_password, user_role) VALUES(?,?,?,?)");
        $hashedPassword = md5($password); // Hash password using MD5
        $role = 'client'; // Set default role to client
        $stmt->bind_param('ssss', $name, $username, $hashedPassword, $role);

        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_username'] = $username;
            $_SESSION['user_name'] = $name;
            $_SESSION['loggin_in'] = true;
            header('location: account.php?register_success=Registered successfully');
        } else {
            header('location: register.php?error=Could not create account');
        }
    }
}
?>

<?php include('layout/header.php'); ?>

<!-- Register -->
<section class="my-5 py-5">
    <div class="container text-center mt-3 pt-3">
        <h2 class="font-weight-bold" style="text-align: center;">Create A Bizcon Account</h2>
    </div>
    <div class="mx-auto container">
        <form id="register-form" method="POST" action="register.php">
            <p id="error-message" style="color: red;">
                <?php if (isset($_GET['error'])) { echo $_GET['error']; } ?>
            </p>

            <div class="form-group">
                <label>Enter your Name</label>
                <input type="text" class="form-control" id="register-name" name="name" placeholder="Name" required />
            </div>

            <div class="form-group">
                <label>Enter your Username</label>
                <input type="text" class="form-control" id="register-username" name="username" placeholder="Username" required />
            </div>

            <div class="form-group">
                <label>Enter your Password</label>
                <input type="password" class="form-control" id="register-password" name="password" placeholder="Password" required />
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" class="form-control" id="register-confirm-password" name="confirmPassword" placeholder="Confirm Password" required />
            </div>

            <div class="form-group">
                <input type="submit" class="form-control" id="register-btn" name="register" value="Register" />
            </div>

            <div class="form-group">
                <a id="login-url" href="login.php" class="btn">Already have an Account</a>
            </div>

        </form>
    </div>
</section>

<?php include('layout/footer.php'); ?>

<script>
// JavaScript for client-side validation
document.getElementById('register-form').onsubmit = function(event) {
    var password = document.getElementById('register-password').value;
    var confirmPassword = document.getElementById('register-confirm-password').value;
    var errorMessage = '';

    // Check if passwords match
    if (password !== confirmPassword) {
        errorMessage = 'Passwords do not match';
    }

    // Validate password length and requirements
    var passwordRequirements = /^(?=.*[A-Z])(?=.*[^\w\d\s:]).{8,}$/;
    if (!passwordRequirements.test(password)) {
        errorMessage = 'Password must be at least 8 characters, contain one uppercase letter, and one special character.';
    }

    if (errorMessage) {
        // Display the error and prevent form submission
        document.getElementById('error-message').textContent = errorMessage;
        event.preventDefault();
    }
};
</script>
