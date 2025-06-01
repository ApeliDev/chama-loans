<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Admin details
$admin_email = $_SESSION['email'];
$admin_role = $_SESSION['role'];
$admin_username = $_SESSION['username']

?>