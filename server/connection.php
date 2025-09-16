<?php
// Start a session only if one isn't already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "bizcon")   
    or die("Can't Connect to Database");
?>