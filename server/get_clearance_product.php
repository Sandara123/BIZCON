<?php

include('connection.php');

$stmt = $conn->prepare("SELECT * FROM products WHERE product_category = 'Clearance Sale'");

$stmt->execute();

$clearance_product = $stmt->get_result();


?>