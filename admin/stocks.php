<?php
include('includes/header.php');
include('server/connection.php');

// Get the current page number, defaulting to 1 if not set
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 50; // Limit per page
$offset = ($page - 1) * $limit;

// Handle search functionality
$search_query = "";
if (isset($_GET['search'])) {
    $search_value = mysqli_real_escape_string($conn, $_GET['search']);
    $search_query = "WHERE product_name LIKE '%$search_value%' OR product_id LIKE '%$search_value%' OR product_category LIKE '%$search_value%'";
}

// Fetch data with the search query and pagination
$query = "SELECT * FROM products $search_query LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);

// Count the total number of rows for pagination
$total_query = "SELECT COUNT(*) FROM products $search_query";
$total_result = mysqli_query($conn, $total_query);
$total_rows = mysqli_fetch_row($total_result)[0];
$total_pages = ceil($total_rows / $limit);
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800" style="font-size: 50px; color: black;">Stocks Management</h1>
    </div>

    <!-- Search Form -->
    <div class="row mb-3">
        <div class="col-md-4">
            <input type="text" class="form-control" autocomplete="off" placeholder="Search by ID, Name, or Category" id="searchInput" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        </div>
    </div>

    <!-- Stock Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold" style="color: black;">Stock Information</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive" id="stockTableBody">
                <!-- The table content will be updated here dynamically via AJAX -->
                <form action="update_product.php" method="POST">
                    <table class="table table-bordered table-hover" id="stockTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="bg-light">
                                <th class="text-center">Product ID</th>
                                <th class="text-center">Product Name</th>
                                <th class="text-center">Category</th>
                                <th class="text-center">Description</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Stocks</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Loop through the fetched rows and display the data
                            while ($stock = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td class='text-center'><input type='hidden' name='product_id[]' value='{$stock['product_id']}'>{$stock['product_id']}</td>";
                                echo "<td><input type='text' class='form-control' name='product_name[]' value='{$stock['product_name']}'></td>";
                                echo "<td>
                                    <select class='form-control' name='product_category[]'>
                                        <option value='Printers' " . (($stock['product_category'] == 'Printers') ? 'selected' : '') . ">Printers</option>
                                        <option value='Cartridges' " . (($stock['product_category'] == 'Cartridges') ? 'selected' : '') . ">Cartridges</option>
                                        <option value='Clearance Sale' " . (($stock['product_category'] == 'Clearance Sale') ? 'selected' : '') . ">Clearance Sale</option>
                                        <option value='Inks' " . (($stock['product_category'] == 'Inks') ? 'selected' : '') . ">Inks</option>
                                        <option value='Ribbons' " . (($stock['product_category'] == 'Ribbons') ? 'selected' : '') . ">Ribbons</option>
                                        <option value='Toners' " . (($stock['product_category'] == 'Toners') ? 'selected' : '') . ">Toners</option>
                                    </select>
                                </td>";
                                echo "<td><input type='text' class='form-control' name='product_description[]' value='{$stock['product_description']}'></td>";
                                echo "<td><input type='text' class='form-control' name='product_price[]' value='{$stock['product_price']}'></td>";
                                echo "<td><input type='text' class='form-control' name='product_stock[]' value='{$stock['product_stock']}'></td>";
                                echo "<td><button type='submit' class='btn btn-dark'>Save</button></td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>

    <!-- Pagination Controls -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php
            for ($i = 1; $i <= $total_pages; $i++) {
                echo "<li class='page-item " . ($i == $page ? 'active' : '') . "'>
                        <a class='page-link' href='stocks.php?page=$i&search=" . urlencode($_GET['search'] ?? '') . "'>$i</a>
                      </li>";
            }
            ?>
        </ul>
    </nav>

</div>

<?php
include('includes/footer.php');
?>

<!-- JavaScript for Dynamic Search -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Function to fetch and update table content based on search
    function fetchStockData(query = "") {
        $.ajax({
            url: "stocks.php", // The current page, we will send the data here
            method: "GET",
            data: { search: query, page: 1 }, // Send the search query and page number
            success: function(response) {
                const tableContent = $(response).find("#stockTableBody").html(); // Get the updated table content
                const pagination = $(response).find(".pagination").html(); // Get pagination links
                $("#stockTableBody").html(tableContent); // Update table content
                $(".pagination").html(pagination); // Update pagination links
            }
        });
    }

    // Event listener for the search input field
    $('#searchInput').on('input', function() {
        const query = $(this).val().trim(); // Get the current search query
        fetchStockData(query); // Call function to fetch data with the new query
    });
});
</script>
