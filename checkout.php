<?php 

  session_start();

  if(!empty($_SESSION['cart'])){



  }else{
    header('location: index.php');

  }

?>

<?php include('layout/header.php');?>


<!---Checkout-->
<section class="my-5 py-5">
  <div class="container text Center mt-3 pt-3">
    <h2 class="form=weight-bold" style="text-align: center;">Checkout Orders</h2>
  </div>
  <div class="mx-auto container">
    <form id="checkout-form" method="POST" action="server/place_order.php">
      <div class="form-group checkout-small-element">
        <label>Enter your Name</label>
        <input type="text" class="form-control" id="checkout-name" name="name" placeholder="Name" required />
      </div>

      <div class="form-group checkout-small-element">
        <label>Enter your Phone no.</label>
        <input 
          type="text" 
          class="form-control" 
          id="checkout-phone" 
          name="phone" 
          placeholder="Phone no." 
          required 
          oninput="validatePhone()"
        />
        <small id="phone-error" style="color: red; display: none;"></small>
      </div>

      <div class="form-group checkout-small-element">
        <label>Enter your ZIP Code</label>
        <input type="text" class="form-control" id="checkout-zipcode" name="zipcode" placeholder="ZIP Code" required />
      </div>

      <div class="form-group checkout-large-element">
        <label>Enter your Address</label>
        <input type="text" class="form-control" id="checkout-address" name="address" placeholder="Address" required />
      </div>
      <div class="form-group checkout-btn-container">
        <p>Total Amount: ₱ <?php echo $_SESSION['total'];?></p>
        <input type="submit" class="form-control" id="checkout-btn" name="place_order" value="Place Order" disabled />
      </div>

    </form>
  </div>
</section>

<script>
  function validatePhone() {
    const phoneInput = document.getElementById('checkout-phone');
    const phoneError = document.getElementById('phone-error');
    const submitButton = document.getElementById('checkout-btn');
    const phone = phoneInput.value;

    // Check if the phone number is 11 digits and starts with '0'
    if (!/^0\d{10}$/.test(phone)) {
      phoneError.textContent = 
        phone.length < 11 
          ? 'Phone number must be 11 digits long.' 
          : phone.charAt(0) !== '0' 
            ? 'Phone number must start with 0.' 
            : 'Invalid phone number format.';
      phoneError.style.display = 'block';
      submitButton.disabled = true;
    } else {
      phoneError.style.display = 'none';
      submitButton.disabled = false;
    }
  }
</script>

<?php include('layout/footer.php');?>
