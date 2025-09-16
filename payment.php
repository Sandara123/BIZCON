<?php 
include('server/connection.php');

if (isset($_POST['order_pay_btn'])) {
    $order_status = $_POST['order_status'];
    $order_total_price = $_POST['order_total_price'];
    $order_id = $_POST['order_id']; // Ensure order_id is passed in the form

    // Check if the order is not paid
    if ($order_status == "not paid") {
        // Update the order status to "To be delivered"
        $stmt = $conn->prepare("UPDATE orders SET order_status = 'To be delivered' WHERE order_id = ?");
        $stmt->bind_param('i', $order_id);
        if ($stmt->execute()) {
            // Retrieve products in the order
            $stmt = $conn->prepare("SELECT product_id, product_quantity FROM order_items WHERE order_id = ?");
            $stmt->bind_param('i', $order_id);
            $stmt->execute();
            $order_items = $stmt->get_result();

            // Update the stock for each product
            $update_stock_stmt = $conn->prepare("UPDATE products SET product_stock = product_stock - ? WHERE product_id = ?");
            while ($item = $order_items->fetch_assoc()) {
                $product_id = $item['product_id']; // Ensure product_id matches your schema
                $ordered_quantity = $item['product_quantity'];

                // Bind and execute stock update
                $update_stock_stmt->bind_param('ii', $ordered_quantity, $product_id);
                $update_stock_stmt->execute();
            }

            // Redirect to account.php with a success message
            header('location: account.php?message=Payment successful! Order is now to be delivered.');
            exit;
        } else {
            // Handle error if the order status update fails
            header('location: account.php?error=Failed to update order status.');
            exit;
        }
    } else {
        // Handle cases where the order is already paid
        header('location: account.php?error=Order already paid or invalid order.');
        exit;
    }
}
?>
