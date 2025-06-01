<?php
include('../include/db_connect.php');

// Fetch the current maintenance mode setting from the database using MySQLi
$query = "SELECT setting_value FROM settings WHERE setting_name = 'maintenance_mode'";
$result = $conn->query($query);

// Check if the query was successful and fetch the result
if ($result->num_rows > 0) {
    $current_setting = $result->fetch_assoc();

    // Check the value of maintenance_mode and redirect if it's 'on'
    if ($current_setting['setting_value'] == 'on') {
        header("Location: maintenance.php");
        exit();
    }
} else {
    echo "Error: Unable to fetch the maintenance mode setting.";
}
?>

