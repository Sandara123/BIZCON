<?php 


session_start();
include('server/connection.php');


if(!isset($_SESSION['loggin_in'])){
  header('location: login.php');
  exit;
}

if(isset($_GET['logout'])){
  if(isset($_SESSION['loggin_in'])){
    unset($_SESSION['loggin_in']);
    unset($_SESSION['user_username']);
    unset($_SESSION['user_name']);
    header('location: login.php');
    exit;
  }
}

if(isset($_POST['change_password'])){
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirmPassword'];
            $user_username = $_SESSION['user_username'];

            if($password !== $confirmPassword){
              header('location: account.php?error=password do not match');
            
            }else if(strlen($password) < 8){
              header('location: account.php?error=password must be at least 8 character');
            
            }else{
              $stmt = $conn->prepare("UPDATE users SET user_password=? WHERE user_username=?");
              $stmt->bind_param('ss', md5($password), $user_username);
              
              if($stmt->execute()){
                header('location: account.php?message=password update successfully');
              }else{
                header('location: account.php?error=password update failed');
              }
            }

}


if(isset($_SESSION['loggin_in'])){

  $user_id = $_SESSION['user_id'];

  $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ?");

  $stmt->bind_param('i', $user_id);

  $stmt->execute();

  $orders = $stmt->get_result();

}
if (isset($_POST['mark_as_received'])) {
    $order_id = $_POST['order_id'];
    $stmt = $conn->prepare("UPDATE orders SET order_status = 'Received' WHERE order_id = ?");
    $stmt->bind_param('i', $order_id);

    if ($stmt->execute()) {
        header('location: account.php?message=Order status updated to Received');
    } else {
        header('location: account.php?error=Failed to update order status');
    }
    exit;
}

?>





<?php include('layout/header.php');?>


      <!---Account--> 
      <section class="my-5 py-5">
        <div class="row container mx-auto">
            <div class="text-center mt-3 pt-3 col-lg-6 col-md-12 col-sm-12">
            <p class="text-center" style="color: forestgreen;"><?php if(isset($_GET['register_success'])){echo $_GET['register_success'];}?></p>
            <p class="text-center" style="color: forestgreen;"><?php if(isset($_GET['login_success'])){echo $_GET['login_success'];}?></p>
                <h3 class="font-weight-bold">Account Information</h3>
                <hr class="mx-auto">
                <div class="account-info">
                    <p>Name: <span><?php if(isset($_SESSION['user_name'])){echo $_SESSION['user_name'];}?></span></p>
                    <p>Username: <span><?php if(isset($_SESSION['user_username'])){echo $_SESSION['user_username'];}?></span></p>
                    <p><a href="#orders" id="order-btn">Your order</a></p>
                    <p><a href="account.php?logout=1" id="logout-btn">Logout</a></p>
                </div>
                
            </div>

            <div class="col-lg-6 col-md-12 col-sm-12">
                <form id="account-form" method="POST" action="account.php">
                <p class="text-center" style="color: red;"><?php if(isset($_GET['error'])){echo $_GET['error'];}?></p>
                <p class="text-center" style="color: forestgreen;"><?php if(isset($_GET['message'])){echo $_GET['message'];}?></p>
                    <h3>Change Password</h3>
                    <hr class="mx-auto">
                    <div class="form-group">
                        <label>Password</label>
                    <input type="password" class="form-control" id="account-password" name="password" placeholder="Password" required/>
                    </div> 
                    
                    <div class="form-group">
                        <label>Confirm Password</label>
                    <input type="password" class="form-control" id="account-password-confirm" name="confirmPassword" placeholder="Password" required/>
                    </div> 
                    <div class="form-group">
                        <input type="submit" value="Change Password" name="change_password" class="btn" id="change-pass-btn">
                    </div>
                
                </form>
            </div>
        </div>
      </section>



      <!---Orders-->
    <section id="orders" class="orders container my-5 py-3">
      <div class="container mt-2">
          <h2 class="font-weight-bolde text-center">Your Orders</h2>
          <hr>
      </div>
      <table class="mt-3 pt-3">
          <tr>
              <th>Order ID</th>
              <th style= "text-align: left;">Order Cost</th>
              <th style="padding-left: 10px;">Order Status</th>
              <th style="padding-left: 20px;">Order Date</th>
              <th>Order Details</th>
          </tr>

        <?php while( $row = $orders->fetch_assoc()){ ?>
   <tr>
      <td>  
        <span><?php echo $row['order_id']; ?></span>
      </td>

      <td> 
        <span>₱<?php echo $row['order_cost']; ?></span>
      </td>

      <td> 
        <span><?php echo $row['order_status']; ?></span>
      </td>

      <td> 
        <span><?php echo $row['order_date']; ?></span>
      </td>

      <td>
    <!-- Details Button -->
    <form method="POST" action="order_details.php" style="display: inline-block;">
    <input type="hidden" value="<?php echo $row['order_id']; ?>" name="order_id" />
    <input type="hidden" value="<?php echo $row['order_status']; ?>" name="order_status" />
    <input class="btn order-details-btn" name="order_details_btn" type="submit" value="Details">
</form>

    <!-- Received Button -->
    <?php if ($row['order_status'] === "To be delivered") { ?>
        <form method="POST" action="account.php" style="display: inline-block;">
            <input type="hidden" value="<?php echo $row['order_id']; ?>" name="order_id" />
            <input class="btn order-details-btn" name="mark_as_received" type="submit" value="Received">
        </form>
    <?php } ?>
</td>
  </tr>



<?php } ?>

        </table>
  </section>




  <?php include('layout/footer.php');?>