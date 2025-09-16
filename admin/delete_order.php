<?php
include('server/connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $order_id = $data['order_id'];

    // Delete from order_items table
    $query = "DELETE FROM order_items WHERE order_id = $order_id";
    $result = mysqli_query($conn, $query);

    // If order has no other items, delete from orders table as well
    if ($result) {
        $query_check = "SELECT COUNT(*) AS count FROM order_items WHERE order_id = $order_id";
        $result_check = mysqli_query($conn, $query_check);
        $count = mysqli_fetch_assoc($result_check)['count'];

        if ($count == 0) {
            // No more items in the order, delete from orders table
            $query_delete_order = "DELETE FROM orders WHERE order_id = $order_id";
            mysqli_query($conn, $query_delete_order);
        }

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
    }
}
?>
