<?php
include('include/auth.php');
include('../include/db_connect.php');

// Fetch the current maintenance mode setting from the database
$query = "SELECT setting_value FROM settings WHERE setting_name = 'maintenance_mode'";
$result = $conn->query($query);

// Check if the query was successful and fetch the result
if ($result->num_rows > 0) {
    $current_setting = $result->fetch_assoc();
} else {
    echo "<script>
            alert('Error: Unable to fetch the maintenance mode setting.');
            window.history.back();
          </script>";
    exit();
}

// Check if form is submitted to toggle maintenance mode
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $maintenance_mode = $_POST['maintenance_mode'];

    // Update the maintenance_mode setting in the database
    $update_query = "UPDATE settings SET setting_value = '$maintenance_mode' WHERE setting_name = 'maintenance_mode'";
    if ($conn->query($update_query) === TRUE) {
        echo "<script>
                alert('Maintenance mode updated successfully.');
                window.location.href = 'security.php';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Error updating maintenance mode: " . $conn->error . "');
                window.history.back();
              </script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Maintenance Mode</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
        .card {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-top: 30px;
        }

        .card h3 {
            font-size: 26px;
            margin-bottom: 25px;
            color: #555;
        }

        .card form {
            display: inline-block;
            text-align: left;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 70px;
            height: 36px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            border-radius: 50%;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: 0.4s;
        }

        input:checked + .slider {
            background-color: #4CAF50;
        }

        input:checked + .slider:before {
            transform: translateX(34px);
        }       

        .btn {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 12px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .row {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .col-12, .col-md-6, .col-lg-4 {
            width: 100%;
            max-width: 480px;
            padding: 0 15px;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <?php include('include/sidebar.php'); ?>
        <div class="main-content" id="main-content">
            <?php include('include/header.php'); ?>
            <div class="content">
                <h2 style="text-align: center; margin-bottom: 40px; color: #333;">System Maintenance Settings</h2>
                <div class="row justify-content-center">
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card">
                            <h3>Maintenance Mode</h3>
                            <form action="security.php" method="POST">
                                <label for="maintenance_mode" style="display:block; margin-bottom: 15px; font-size: 18px; color: #666;">Toggle Maintenance Mode:</label>
                                <label class="switch">
                                    <input type="checkbox" name="maintenance_mode" id="maintenance_mode" value="on" <?php if ($current_setting['setting_value'] == 'on') echo 'checked'; ?>>
                                    <span class="slider"></span>
                                </label>
                                <br><br>
                                <input type="submit" value="Update Maintenance Mode" class="btn">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/main.js"></script>
</body>
</html>
