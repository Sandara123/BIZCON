<?php
include('includes/header.php');
include('server/connection.php');  // Assuming this file contains your database connection

// Check if the form is submitted (for saving the edited data)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Loop through the POST data for each user update
    foreach ($_POST['user_id'] as $index => $userId) {
        $username = $_POST['user_username'][$index];
        $password = $_POST['user_password'][$index];
        $role = $_POST['user_role'][$index];

        // Fetch the current password from the database to keep it if the password field is empty
        $query = "SELECT user_password FROM users WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $currentPassword);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        // Hash the password with MD5 if it's updated (i.e., not empty)
        if (!empty($password)) {
            $hashedPassword = md5($password); // Use MD5 for password hashing
        } else {
            // If password is not changed, keep the existing password
            $hashedPassword = $currentPassword;
        }

        // Prepare the SQL update query using prepared statements
        if (!empty($hashedPassword)) {
            $stmt = mysqli_prepare($conn, "UPDATE users SET user_username = ?, user_role = ?, user_password = ? WHERE user_id = ?");
            mysqli_stmt_bind_param($stmt, "sssi", $username, $role, $hashedPassword, $userId);
        } else {
            $stmt = mysqli_prepare($conn, "UPDATE users SET user_username = ?, user_role = ? WHERE user_id = ?");
            mysqli_stmt_bind_param($stmt, "ssi", $username, $role, $userId);
        }

        if ($stmt) {
            mysqli_stmt_execute($stmt);
            if (mysqli_stmt_error($stmt)) {
                echo "Error updating account with ID $userId: " . mysqli_stmt_error($stmt);
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Error preparing statement: " . mysqli_error($conn);
        }
    }
    echo "Accounts updated successfully!";
}

// Fetch data from the users table
$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);

// Check if data is fetched
if (!$result) {
    die('Query failed: ' . mysqli_error($conn));
}
?>

<!-- HTML and Form Section -->
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800" style="font-size: 50px; color: black;">Accounts</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold" style="color: black;">Accounts Information</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <form method="POST" action="">
                    <table class="table table-bordered table-hover" id="accountsTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="bg-light">
                                <th class="text-center">Account ID</th>
                                <th class="text-center">Username</th>
                                <th class="text-center">Password</th>
                                <th class="text-center">Role</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($account = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                // Display the user_id as a non-editable field
                                echo "<td class='text-center'>" . $account['user_id'] . "</td>";
                                
                                // Editable fields for username, password, and role
                                echo "<td class='text-center'>
                                          <input type='text' name='user_username[]' value='" . $account['user_username'] . "' class='form-control'>
                                      </td>";
                                echo "<td class='text-center'>
                                          <input type='password' name='user_password[]' class='form-control' value=''>
                                      </td>";
                                echo "<td class='text-center'>
                                          <select name='user_role[]' class='form-control'>
                                              <option value='admin'" . ($account['user_role'] == 'admin' ? ' selected' : '') . ">Admin</option>
                                              <option value='client'" . ($account['user_role'] == 'client' ? ' selected' : '') . ">Client</option>
                                          </select>
                                      </td>";

                                // Hidden field for user_id to track which user to update
                                echo "<td class='text-center'>
                                          <input type='hidden' name='user_id[]' value='" . $account['user_id'] . "' />
                                          <button type='submit' class='btn btn-dark'>Save</button>
                                      </td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let isEditing = false;

    $('#editBtn').click(function() {
        isEditing = !isEditing;
        
        if (isEditing) {
            $(this).text('Cancel');
            $('#saveBtn').show();
            $('.editable').each(function() {
                const spanValue = $(this).find('span').text();
                const inputValue = $(this).find('input').val();
                
                $(this).find('span').hide(); // Hide the span
                $(this).find('input').val(inputValue || spanValue).show(); // Show the input with the correct value
                if ($(this).find('select').length) {
                    const selectedValue = $(this).find('select').val();
                    $(this).find('select').show();
                }
            });
        } else {
            $(this).text('Edit');
            $('#saveBtn').hide();
            $('.editable').each(function() {
                const inputValue = $(this).find('input').val();
                const selectValue = $(this).find('select').val();
                
                $(this).find('input').hide(); // Hide the input
                $(this).find('select').hide(); // Hide the select
                $(this).find('span').text(inputValue || selectValue).show(); // Show the span with the current value
            });
        }
    });
});
</script>



<style>
.table {
    border-collapse: collapse; /* Collapse borders */
    width: 100%; /* Full-width table */
}

.table-bordered {
    border: 1px solid #e3e6f0; /* Ensure outer table border */
}

.table-bordered th,
.table-bordered td {
    border: 1px solid #e3e6f0; /* Ensure cell borders */
    vertical-align: middle; /* Vertically center content */
    text-align: center; /* Horizontally center content */
    padding: 0.75rem; /* Add padding for spacing */
}

.table-hover tbody tr:hover {
    background-color: #f8f9fc; /* Highlight row on hover */
}

.editable input {
    width: 100%; /* Full width input */
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    color: #6e707e;
    background-color: #fff;
    border: 1px solid #d1d3e2;
    border-radius: 0.35rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.editable input:focus {
    border-color: #bac8f3;
    outline: 2px solid rgba(78, 115, 223, 0.5);
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.editable select {
    width: 100%; /* Full width select */
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    color: #6e707e;
    background-color: #fff;
    border: 1px solid #d1d3e2;
    border-radius: 0.35rem;
}
</style>

<?php
include('includes/footer.php');
?>
