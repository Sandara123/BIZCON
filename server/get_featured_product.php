<?php

include('connection.php');

// Prepare the SQL query to select specific product IDs
$stmt = $conn->prepare("SELECT * FROM products WHERE product_id IN (64, 40, 93, 47, 53, 10)");

// Execute the query
$stmt->execute();

// Fetch the result
$featured_product = $stmt->get_result();

?>
