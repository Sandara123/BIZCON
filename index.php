<?php

// Function to check login status
function isUserLoggedIn() {
    return isset($_SESSION['loggin_in']) && $_SESSION['loggin_in'] === true;
}

// Redirect user to login if not logged in
function redirectToLogin() {
    header('Location: login.php');
    exit();
}
?>

<?php include('layout/header.php');?>

      
       <!-- Carousel -->
<div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="assets/imgs/bg1.png" class="d-block w-100">
    </div>
    <div class="carousel-item">
      <img src="assets/imgs/bg2.png" class="d-block w-100">
    </div>
    <div class="carousel-item">
      <img src="assets/imgs/bg3.png" class="d-block w-100">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
  <!-- HOME Content Overlay -->
  <section id="home">
    <div class="container">
      <h4>Welcome to...</h4>
      <h1>Bizcon Distribution Inc.</h1>
      <p><i>Printers and Tools Available</i></p>
      <a href="product.php"><button>Shop Now</button></a>
    </div>
  </section>
</div>

      <!----Featured-->
      <section id="featured" class="my-5">
        <div class="container text-center m-7 py-3">
          <h3>Our Featured Product</h3>
          <hr>
          <p>Here you can check out our featured product</p>
        </div>
        <div class="row mx-auto container-fluid">

        <?php include('server/get_featured_product.php'); ?>


        <?php while($row= $featured_product->fetch_assoc()){?>

          <div class="product text-center col-lg-2 col-md-4 col-sm-12">
                <img class="img-fluid mb-3" src="assets/imgs/product/<?php echo $row['product_image']; ?>" />
                <h5 class="p-name"><?php echo $row['product_name']; ?></h5>
                <h4 class="p-price"><?php echo $row['product_price']; ?></h4>

                <!-- eto code sa baba to check if the user is logged in -->
                <a href="<?php echo isUserLoggedIn() 
                    ? "single_product.php?product_id={$row['product_id']}" 
                    : "login.php"; ?>">
                    <button class="btn buy-btn">Buy Now</button>
                </a>
            </div>

          <?php } ?>

        </div>
      </section>     

      <!---Banner-->
      <section id="banner">
        <div class="container">
          <h4>THE BEST PRICE THIS SEASON</h4>
          <h1>12.12 CHRISTMAS SALES<br> UP TO 60% OFF</h1>
          <button class="text-uppercase">SHOP NOW</button>
        </div>
      </section>

            <!----Clearance-->
            <section id="featured" class="my-3">
              <div class="container text-center m-7 py-3">
                <h3>Clearance Sale!</h3>
                <hr>
                <p>Here you can check out our other products</p>
              </div>
              <div class="row mx-auto container-fluid">

              <?php include('server/get_clearance_product.php'); ?>

              <?php while($row=$clearance_product->fetch_assoc()){?>

                 <div class="product text-center col-lg-2 col-md-4 col-sm-12">
                <img class="img-fluid mb-3" src="assets/imgs/product/<?php echo $row['product_image']; ?>" />
                <h5 class="p-name"><?php echo $row['product_name']; ?></h5>
                <h4 class="p-price"><?php echo $row['product_price']; ?></h4>
                <!-- Add logic to check login status -->
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