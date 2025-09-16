<?php
include('server/connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $product_price = floatval($_POST['product_price']);

    $query = "UPDATE order_items SET product_name = '$product_name', product_price = $product_price WHERE order_id = $order_id";

    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
    }
}
?>