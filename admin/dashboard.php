<?php 
include('includes/header.php');
include('server/connection.php');  // Add a semicolon here
// Check if the user is logged in and has the admin role
if (!isset($_SESSION['loggin_in']) || $_SESSION['user_role'] != 'admin') {
    // Redirect to login page if not logged in or not an admin
    header('location: login.php');
    exit();
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="row mt-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="ms-3">
                        <h3 class="mb-0 h4 font-weight-bolder" style="font-size: 50px">Dashboard</h3> 
                    </div>
                </div>

                <?php
                // Fetch data from the database
                // Overall Users
                $query_users = "SELECT COUNT(*) AS total_users FROM users";
                $result_users = mysqli_query($conn, $query_users);
                $total_users = mysqli_fetch_assoc($result_users)['total_users'];

                // Total Sales
                $query_sales = "SELECT SUM(order_cost) AS total_sales FROM orders";
                $result_sales = mysqli_query($conn, $query_sales);
                $total_sales = mysqli_fetch_assoc($result_sales)['total_sales'];  

                // Total Products
                $query_products = "SELECT COUNT(*) AS total_products FROM products";
                $result_products = mysqli_query($conn, $query_products);
                $total_products = mysqli_fetch_assoc($result_products)['total_products'];

                // Total Orders
                $query_orders = "SELECT COUNT(*) AS total_orders FROM orders";
                $result_orders = mysqli_query($conn, $query_orders);
                $total_orders = mysqli_fetch_assoc($result_orders)['total_orders'];
                ?>

                <!-- Dashboard Cards -->
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-2 ps-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-sm mb-0 text-capitalize">Overall Users</p>
                                    <h4 class="mb-0"><?= $total_users ?></h4>
                                </div>
                                <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                                    <i class="material-symbols-rounded opacity-10">person</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="card">
                        <div class="card-header p-2 ps-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-sm mb-0 text-capitalize">Sales</p>
                                    <h4 class="mb-0">₱<?= number_format($total_sales, 2) ?></h4>
                                </div>
                                <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                                    <i class="material-symbols-rounded opacity-10">weekend</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-2 ps-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-sm mb-0 text-capitalize">Total Products</p>
                                    <h4 class="mb-0"><?= $total_products ?></h4>
                                </div>
                                <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                                    <i class="material-symbols-rounded opacity-10">inventory_2</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="card">
                        <div class="card-header p-2 ps-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-sm mb-0 text-capitalize">Total Orders</p>
                                    <h4 class="mb-0"><?= $total_orders ?></h4>
                                </div>
                                <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                                    <i class="material-symbols-rounded opacity-10">shopping_cart</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header p-3">
                    <h4 class="mb-0">Add New Product</h4>
                </div>
                <div class="card-body">
                    <?php
                   if (isset($_POST['add_product'])) {
    // Sanitize input
    $product_name = mysqli_real_escape_string($conn, $_POST['name']);
    $product_category = mysqli_real_escape_string($conn, $_POST['category']);
    $product_price = floatval($_POST['price']);
    $product_description = mysqli_real_escape_string($conn, $_POST['description']);

    // Handle image upload
    $product_image = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Extract file name and extension
        $image_name = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
        $image_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $product_image = $image_name . '.' . $image_extension; // Save name and extension

        // Set target directory with absolute path
        $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/BIZCON_WEBSITE/assets/imgs/product/";

        // Ensure the directory exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Move uploaded file to the correct directory
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $product_image)) {
            echo "<div class='alert alert-danger'>Failed to upload image.</div>";
            exit;
        }
    }

    // Insert into database
    $query = "INSERT INTO products (product_name, product_category, product_description, product_image, product_price, product_stock, product_color, product_reference) 
              VALUES ('$product_name', '$product_category', '$product_description', '$product_image', $product_price, 0, '', '')";

    if (mysqli_query($conn, $query)) {
        echo "<div class='alert alert-success'>Product added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error adding product: " . mysqli_error($conn) . "</div>";
    }
}
                    ?>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Product Name</label>
                                <input type="text" name="name" class="form-control border border-dark" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Category</label>
                                <select class="form-select border border-dark text-center" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="Printers">Printers</option>
                                    <option value="Cartridges">Cartridges</option>
                                    <option value="Clearance Sale">Clearance Sale</option>
                                    <option value="Inks">Inks</option>
                                    <option value="Ribbons">Ribbons</option>
                                    <option value="Toners">Toners</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Price</label>
                                <input type="number" step="0.01" name="price" class="form-control border border-dark" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control border border-dark" required></textarea>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Image</label>
                                <input type="file" name="image" class="form-control border border-dark" accept="image/*" required>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" name="add_product" class="btn bg-gradient-dark">Add Product</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header p-3">
                    <h4 class="mb-0">Recent Orders</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Order ID</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Customer Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Product</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Amount</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
    <?php
    $query_orders = "
        SELECT oi.order_id, u.user_name, oi.product_name, oi.product_price, o.order_status
        FROM order_items oi
        JOIN users u ON oi.user_id = u.user_id
        JOIN orders o ON oi.order_id = o.order_id
        ORDER BY oi.order_id DESC
        LIMIT 10";  // You can adjust the limit as needed

    $result_orders = mysqli_query($conn, $query_orders);

    if (mysqli_num_rows($result_orders) > 0) {
        while ($order = mysqli_fetch_assoc($result_orders)) {
            $status_class = '';
            switch ($order['order_status']) {
                case 'Not Paid':
                    $status_class = 'bg-gradient-warning';
                    break;
                case 'To Be Delivered':
                    $status_class = 'bg-gradient-yellow';
                    break;
                case 'Received':
                    $status_class = 'bg-gradient-success';
                    break;
                default:
                    $status_class = 'bg-gradient-secondary';
                    break;
            }

            // Set the fields to be editable or non-editable
            $editable = false;
            $product_name = $order['product_name'];
            $product_price = number_format($order['product_price'], 2);

            // Editable fields are placed in input fields
            echo "<tr data-order-id='{$order['order_id']}'>";
            echo "<td class='ps-4'><p class='text-xs font-weight-bold mb-0'>#{$order['order_id']}</p></td>";
            echo "<td class='ps-4'>
                    <input type='text' class='form-control text-xs font-weight-bold' value='{$order['user_name']}' readonly>
                  </td>";
            echo "<td class='ps-4'>
                    <input type='text' class='form-control text-xs font-weight-bold' value='{$product_name}' " . ($editable ? "" : "readonly") . ">
                  </td>";
            echo "<td class='ps-4'>
                    <input type='number' class='form-control text-xs font-weight-bold' value='{$product_price}' " . ($editable ? "" : "readonly") . ">
                  </td>";
            echo "<td class='ps-4'><span class='badge {$status_class}'>{$order['order_status']}</span></td>";
            echo "<td class='ps-4'>
                    <button class='btn btn-link text-dark px-3 mb-0 edit-btn'>
                        <i class='material-symbols-rounded text-dark position-relative text-sm'>edit</i>
                    </button>
                    <button class='btn btn-link text-danger px-3 mb-0 delete-btn'>
                        <i class='material-symbols-rounded text-dark position-relative text-sm'>delete</i>
                    </button>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6' class='text-center'>No recent orders found.</td></tr>";
    }
    ?>
</tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Handle Edit Button Click
    document.querySelectorAll('.edit-btn').forEach(function (btn) {
        btn.addEventListener('click', function (event) {
            var row = event.target.closest('tr');  // Get the closest row (tr)
            var inputs = row.querySelectorAll('input');  // Get all inputs in this row
            var order_id = row.getAttribute('data-order-id');  // Get the order id

            // Toggle read-only on input fields
            inputs.forEach(function(input) {
                input.readOnly = !input.readOnly;
            });

            // Change the button to Save or Edit
            if (btn.innerText === 'edit') {
                btn.innerHTML = `<i class="material-symbols-rounded text-dark position-relative text-sm">save</i>`;
            } else {
                // When the button is clicked again, save changes (you can add AJAX here)
                btn.innerHTML = `<i class="material-symbols-rounded text-dark position-relative text-sm">edit</i>`;
                // Handle the saving part with an AJAX request to update the database
                var formData = new FormData();
                formData.append('order_id', order_id);
                formData.append('product_name', inputs[1].value);
                formData.append('product_price', inputs[2].value);

                fetch('update_order.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Order updated successfully');
                    } else {
                        alert('Error updating order');
                    }
                });
            }
        });
    });

    // Handle Delete Button Click
    document.querySelectorAll('.delete-btn').forEach(function (btn) {
        btn.addEventListener('click', function (event) {
            var row = event.target.closest('tr');
            var order_id = row.getAttribute('data-order-id');

            if (confirm("Are you sure you want to delete this order?")) {
                fetch('delete_order.php', {
                    method: 'POST',
                    body: JSON.stringify({ order_id: order_id }),
                    headers: { 'Content-Type': 'application/json' }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        row.remove(); // Remove the row from the table
                        alert('Order deleted successfully');
                    } else {
                        alert('Error deleting order');
                    }
                });
            }
        });
    });
});
</script>

<?php include('includes/footer.php');?>