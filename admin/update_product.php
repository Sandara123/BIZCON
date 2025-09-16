<?php
include('server/connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['product_id']) && isset($_POST['product_name']) && is_array($_POST['product_id'])) {
        
        // Loop through all the products
        $product_ids = $_POST['product_id'];
        $product_names = $_POST['product_name'];
        $product_categories = $_POST['product_category'];
        $product_descriptions = $_POST['product_description'];
        $product_prices = $_POST['product_price'];
        $product_stocks = $_POST['product_stock'];

        // Loop through each product and update
        for ($i = 0; $i < count($product_ids); $i++) {
            $product_id = $product_ids[$i];
            $product_name = mysqli_real_escape_string($conn, $product_names[$i]);
            $product_category = mysqli_real_escape_string($conn, $product_categories[$i]);
            $product_description = mysqli_real_escape_string($conn, $product_descriptions[$i]);
            $product_price = floatval($product_prices[$i]);
            $product_stock = intval($product_stocks[$i]);

            // Prepare query
            $query = "UPDATE products SET 
                      product_name = '$product_name', 
                      product_category = '$product_category',
                      product_description = '$product_description', 
                      product_price = $product_price,
                      product_stock = $product_stock 
                      WHERE product_id = $product_id";

            // Execute the query
            if (!mysqli_query($conn, $query)) {
                echo "Error: " . mysqli_error($conn);
            }
        }

        // Redirect to stocks.php with a success message
        echo "<script>alert('Product(s) updated successfully.'); window.location.href='stocks.php';</script>";
    } else {
        echo "Missing required data.";
    }
} else {
    echo "Invalid request method.";
}
?>
