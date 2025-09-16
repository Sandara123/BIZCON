<?php 

session_start();

include('connection.php');

if(!isset($_SESSION['loggin_in'])){
    header('location: ../login.php');
    exit;

}else{
    if(isset($_POST['place_order'])){


        //1. Get user info and store to db
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $zipcode = $_POST['zipcode'];
        $address = $_POST['address'];
        $order_cost = $_SESSION['total'];   
        $order_status = 'not paid';
        $user_id = $_SESSION['user_id'];    
        $order_date = date('Y-m-d H:i:s');

        $stmt = $conn->prepare("INSERT INTO orders (order_cost, order_status, user_id, user_phone, user_zipcode, user_address, order_date)
                        VALUES(?,?,?,?,?,?,?); ");

        $stmt->bind_param('isiisss', $order_cost, $order_status, $user_id, $phone, $zipcode, $address, $order_date);

        $stmt_status = $stmt->execute();

        if(!$stmt_status){
            header('location: ../login.php');
            exit;
        }


        //2. issue new order and store order info to db
        $order_id = $stmt->insert_id;


        //3.get products from cart


        foreach($_SESSION['cart'] as $key => $value){
            $product = $_SESSION['cart'][$key];
        $product_id = $product['product_id'];
        $product_name = $product['product_name'];
        $product_image = $product['product_image'];
        $product_price = $product['product_price'];
        $product_quantity = $product['product_quantity'];

            //4. store each single item
        $stmt1 = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, product_image, product_price, product_quantity, user_id, order_date)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?); ");

            $stmt1->bind_param('iissiiis', $order_id, $product_id, $product_name, $product_image, $product_price, $product_quantity, $user_id, $order_date);         
            $stmt1->execute();

        }

        //5. remove everything from cart --> delay until payment is done
        unset($_SESSION['cart']);
        

        //6. inform user whether everything is all goodss or no.
        header('location: ../order_details.php?order_status=order placed succesfully');
        

    }


}



?>