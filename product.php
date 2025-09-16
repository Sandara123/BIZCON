<?php 
include('server/connection.php');

// Function to check login status
function isUserLoggedIn() {
    return isset($_SESSION['loggin_in']) && $_SESSION['loggin_in'] === true;
}

// Default category is 'All'
$category = isset($_POST['category']) ? $_POST['category'] : 'all';

// Query based on category
if ($category != 'all') {
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_category=?");
    $stmt->bind_param("s", $category);
} else {
    $stmt = $conn->prepare("SELECT * FROM products");
}

$stmt->execute();
$products = $stmt->get_result();
?>



<?php include('layout/header.php');?>




<!---Search-->
<section id="search" class="my-5 py-5 ms-2">
    <div class="container mt-2 py-2">
      <p>Search Product</p>
      <hr>
    </div>

    <form action="product.php" method="POST">
      <div class="row mx-auto container">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="d-flex flex-wrap">
                <div class="form-check me-3">
                  <input class="form-check-input" type="radio" name="category" id="category_all" value="all" <?php if($category == 'all') echo 'checked'; ?> onchange="this.form.submit()">
                  <label class="form-check-label" for="category_all">All</label>
                </div>
                <div class="form-check me-3">
                  <input class="form-check-input" type="radio" name="category" id="category_one" value="inks" <?php if($category == 'inks') echo 'checked'; ?> onchange="this.form.submit()">
                  <label class="form-check-label" for="category_one">Inks</label>
                </div>
                <div class="form-check me-3">
                  <input class="form-check-input" type="radio" name="category" id="category_two" value="printers" <?php if($category == 'printers') echo 'checked'; ?> onchange="this.form.submit()">
                  <label class="form-check-label" for="category_two">Printers</label>
                </div>
                <div class="form-check me-3">
                  <input class="form-check-input" type="radio" name="category" id="category_three" value="toners" <?php if($category == 'toners') echo 'checked'; ?> onchange="this.form.submit()">
                  <label class="form-check-label" for="category_three">Toners</label>
                </div>
                <div class="form-check me-3">
                  <input class="form-check-input" type="radio" name="category" id="category_four" value="cartridges" <?php if($category == 'cartridges') echo 'checked'; ?> onchange="this.form.submit()">
                  <label class="form-check-label" for="category_four">Cartridges</label>
                </div>
                <div class="form-check me-3">
                  <input class="form-check-input" type="radio" name="category" id="category_five" value="clearance sale" <?php if($category == 'clearance sale') echo 'checked'; ?> onchange="this.form.submit()">
                  <label class="form-check-label" for="category_five">Clearance Sale</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="category" id="category_six" value="ribbons" <?php if($category == 'ribbons') echo 'checked'; ?> onchange="this.form.submit()">
                  <label class="form-check-label" for="category_six">Ribbons</label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </section>


   <!----products-->
   <section id="product" class="my-1 py-1">
              <div class="container  mt-5 py-3">
                <h3>Our Products</h3>
                <hr>
                <p>Here you can check out our products</p>
              </div>
              <div class="row mx-auto container">

              <?php while($row = $products->fetch_assoc()){?>

                 <div class="product text-center col-lg-2 col-md-4 col-sm-12">
            <img class="img-fluid mb-3" src="assets/imgs/product/<?php echo $row['product_image'] ?>"/>
            <h5 class="p-name"><?php echo $row['product_name'] ?></h5>
            <h4 class="p-price"><?php echo number_format($row['product_price'], 2); ?></h4>

            <!-- Add logic for Buy Now button -->
            <a href="<?php echo isUserLoggedIn() 
                ? "single_product.php?product_id={$row['product_id']}" 
                : "login.php"; ?>">
                <button class="btn buy-btn">Buy Now</button>
            </a>
        </div>

                <?php } ?>

        


      </div>
    </section> 

    <?php include('layout/footer.php');?>