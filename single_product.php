<?php 

include('server/connection.php');

if(isset($_GET['product_id'])){

  $product_id = $_GET['product_id'];

  // Prepare the SQL query to select specific product IDs
  $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
  $stmt->bind_param("i", $product_id);
  // Execute the query
  $stmt->execute();
  
  // Fetch the result
  $product = $stmt->get_result();

} else {
  header('location: index.php');
}

?>

<?php include('layout/header.php'); ?>

<!----single-product---->
<section class="container single-product my-5 pt-3">
  <div class="row mt-5">
    
    <?php while($row = $product->fetch_assoc()){ ?>

      <div class="col-lg-5 col-md-6 col-sm-12">
        <img class="img-fluid w-100% pb-1" src="assets/imgs/product/<?php echo $row['product_image']; ?>" id="mainImg"/>
      </div>

      <div class="col-lg-6 col-md-12 col-sm-12">
        <h6><?php echo $row['product_category']; ?></h6>
        <h3 class="py-2"><?php echo $row['product_name']; ?></h3>
        <h2>₱<?php echo $row['product_price']; ?></h2>

        <form method="POST" action="cart.php">
          <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>"/>
          <input type="hidden" name="product_image" value="<?php echo $row['product_image']; ?>"/>
          <input type="hidden" name="product_name" value="<?php echo $row['product_name']; ?>"/>
          <input type="hidden" name="product_price" value="<?php echo $row['product_price']; ?>"/>
          <input 
            type="number" 
            name="product_quantity" 
            value="1" 
            min="1" 
            max="<?php echo $row['product_stock']; ?>" 
            <?php echo ($row['product_stock'] == 0) ? 'disabled' : ''; ?> 
            id="quantityInput"
          />
          
          <?php if($row['product_stock'] == 0): ?>
            <button class="buy-btn" type="button" style="background-color: gray; cursor: not-allowed;" disabled>Out of Stock</button>
          <?php else: ?>
            <button class="buy-btn" type="submit" name="add_to_cart">Add to Cart</button>
          <?php endif; ?>
        </form>

        <h4 class="mt-5 mb-2">Product Stocks</h4>
        <span><?php echo $row['product_stock']; ?></span>
      </div>

    <?php } ?>

  </div>
</section>

<script>
  // JavaScript to enforce stock quantity dynamically
  document.addEventListener('DOMContentLoaded', function () {
    const quantityInput = document.getElementById('quantityInput');

    if (quantityInput) {
      const maxStock = parseInt(quantityInput.getAttribute('max'), 10);

      quantityInput.addEventListener('input', function () {
        if (parseInt(this.value, 10) > maxStock) {
          this.value = maxStock; // Reset to max stock if user exceeds it
          alert(`You cannot order more than ${maxStock} units for this product.`);
        }
      });
    }
  });
</script>

<?php include('layout/footer.php'); ?>
