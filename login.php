<?php 

session_start();
include('server/connection.php'); 

if(isset($_SESSION['loggin_in'])){
  header('location: account.php');
}

if(isset($_POST['login_btn'])){

    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $stmt = $conn->prepare("SELECT user_id, user_name, user_username, user_password, user_role FROM users WHERE user_username =? AND user_password =? LIMIT 1");

    $stmt->bind_param('ss', $username, $password);

    if($stmt->execute()){
        $stmt->bind_result($user_id, $user_name, $user_username, $user_password, $user_role);
        $stmt->store_result();

        if($stmt->num_rows() == 1){
            $stmt->fetch();
            
            // Set session variables
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $user_name;
            $_SESSION['user_username'] = $user_username;
            $_SESSION['user_role'] = $user_role;  // Store user role in session
            $_SESSION['loggin_in'] = true;

            // Redirect based on user role
            if($user_role == 'admin'){
                header('location: http://localhost/BIZCON_WEBSITE/admin/dashboard.php');  // Admin redirection
            } else if($user_role == 'client'){
                header('location: http://localhost/BIZCON_WEBSITE/index.php');  // Client redirection
            }

        } else {
            header('location: login.php?error=unknown account');
        }

    } else {
        header('location: login.php?error=something went wrong');
    }

}
?>


<?php include('layout/header.php');?>

<!-- Login Section -->
<section class="my-5 py-5">
    <div class="container text-center mt-3 pt-3">
        <h2 class="form-weight-bold" style="text-align: center;">Welcome to <span>Bizcon </span>Distribution Inc.</h2>
    </div>
    <div class="mx-auto container">
        <form id="login-form" method="POST" action="login.php">
            <p style="color: red" class="text-center"><?php if(isset($_GET['error'])){echo $_GET['error'];}?></p>
            <div class="form-group">
                <label>Enter your Username</label>
                <input type="text" class="form-control" id="login-username" name="username" placeholder="Username" required/>
            </div>

            <div class="form-group">
                <label>Enter your Password</label>
                <input type="password" class="form-control" id="login-password" name="password" placeholder="Password" required/>
            </div>

            <div class="form-group">
                <input type="submit" class="form-control" id="login-btn" name="login_btn" value="Login"/>
            </div>

            <div class="form-group">
                <a id="register-url" href="register.php" class="btn">Don't have an account? Register now!</a>
            </div>

        </form>
    </div>

</section>

<?php include('layout/footer.php');?>
