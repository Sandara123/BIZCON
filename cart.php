<?php 
session_start();
include('server/connection.php');

// Initialize the cart session if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
    $_SESSION['total'] = 0; // Initialize total to 0
}

// Function to calculate the total cart value
function calculateTotalCart() {
    $total = 0;
    foreach ($_SESSION['cart'] as $key => $value) {
        $total += $value['product_price'] * $value['product_quantity'];
    }
    $_SESSION['total'] = $total;
}

// Handle actions: Add to Cart, Remove, and Edit Quantity
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];

    // Fetch stock from database
    $stmt = $conn->prepare("SELECT product_stock FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        echo '<script>alert("Product not found!");</script>';
    } elseif ($_POST['product_quantity'] > $product['product_stock']) {
        echo '<script>alert("Not enough stock available!");</script>';
    } else {
        $products_array_ids = array_column($_SESSION['cart'], "product_id");

        if (!in_array($product_id, $products_array_ids)) {
            // Add product to cart
            $_SESSION['cart'][$product_id] = array(
                'product_id' => $_POST['product_id'],
                'product_name' => $_POST['product_name'],
                'product_price' => $_POST['product_price'],
                'product_image' => $_POST['product_image'],
                'product_quantity' => $_POST['product_quantity']
            );
        } else {
            echo '<script>alert("Product already in cart!");</script>';
        }
    }

    calculateTotalCart();
} elseif (isset($_POST['remove_product'])) {
    $product_id = $_POST['product_id'];
    unset($_SESSION['cart'][$product_id]);   
    calculateTotalCart();
} elseif (isset($_POST['edit_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['product_quantity'];

    // Fetch stock from database
    $stmt = $conn->prepare("SELECT product_stock FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        echo '<script>alert("Product not found!");</script>';
    } elseif ($new_quantity > $product['product_stock']) {
        echo '<script>alert("Not enough stock available!");</script>';
    } else {
        // Update product quantity in the cart
        $_SESSION['cart'][$product_id]['product_quantity'] = $new_quantity;
        calculateTotalCart();
    }
}
?>

<?php include('layout/header.php'); ?>

<!-- Cart Section -->
<section class="cart container my-5 py-3">
    <div class="container mt-5">
        <h2 class="font-weight-bold">Your Cart</h2>
        <hr>
    </div>

    <?php if (empty($_SESSION['cart'])): ?>
        <!-- Display a message if the cart is empty -->
        <div class="empty-cart-message">
            <h3>Your cart is empty. Add some products!</h3>
        </div>
    <?php else: ?>
        <!-- Display the cart items -->
        <table class="mt-1 pt-5">
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>

            <?php foreach ($_SESSION['cart'] as $key => $value): 
                // Fetch stock from database
                $stmt = $conn->prepare("SELECT product_stock FROM products WHERE product_id = ?");
                $stmt->bind_param("i", $value['product_id']);
                $stmt->execute();
                $result = $stmt->get_result();
                $product = $result->fetch_assoc();
                $stock = $product ? $product['product_stock'] : 0;
            ?>
            <tr>
                <td>
                    <div class="product-info">
                        <img src="assets/imgs/product/<?php echo $value['product_image']; ?>" />
                        <div>
                            <p><?php echo $value['product_name']; ?></p>
                            <small><span>₱</span><?php echo $value['product_price']; ?></small>
                            <br>
                            <form method="POST" action="cart.php"> 
                                <input type="hidden" name="product_id" value="<?php echo $value['product_id']; ?>" /> 
                                <input type="submit" name="remove_product" class="remove-btn" value="Remove" />
                            </form>
                        </div>   
                    </div>
                </td>
                <td>
                    <form method="POST" action="cart.php"> 
                        <input type="hidden" name="product_id" value="<?php echo $value['product_id']; ?>" /> 
                        <input 
                            type="number" 
                            name="product_quantity" 
                            value="<?php echo $value['product_quantity']; ?>" 
                            min="1" 
                            max="<?php echo $stock; ?>" 
                            class="quantity-input" 
                        />
                        <input type="submit" class="edit-btn" value="Update" name="edit_quantity" />
                    </form>
                    <small class="stock-info">
                        Available Stock: <?php echo $stock; ?>
                    </small>
                </td>
                <td>
                    <span>₱</span>
                    <span class="product-price"><?php echo $value['product_quantity'] * $value['product_price']; ?></span>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <div class="cart-total">
            <table>
                <tr>
                    <td>Total</td>
                    <td>₱ <?php echo $_SESSION['total']; ?></td>
                </tr>
            </table>
        </div>

        <!-- Checkout Button Section -->
        <div class="checkout-container">
            <form method="POST" action="checkout.php">
                <input type="submit" class="btn checkout-btn" value="Checkout" />
            </form>
        </div>
    <?php endif; ?>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const quantityInputs = document.querySelectorAll('.quantity-input');
        quantityInputs.forEach(input => {
            input.addEventListener('input', function () {
                const maxStock = parseInt(this.getAttribute('max'), 10);
                if (parseInt(this.value, 10) > maxStock) {
                    this.value = maxStock;
                    alert(`You cannot order more than ${maxStock} units for this product.`);
                }
            });
        });
    });
</script>

<?php include('layout/footer.php'); ?>
