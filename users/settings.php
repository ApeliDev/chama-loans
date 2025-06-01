<?php
include('../include/db_connect.php');
include('include/auth.php');
include('maintenance_check.php');
$member_id = $_SESSION['user_id'];

// Fetch member information
$query = $conn->prepare("SELECT * FROM members WHERE member_id = ?");
$query->bind_param("i", $member_id);
$query->execute();
$result = $query->get_result();
$member = $result->fetch_assoc();

// update account information if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $member['password'];
    $confirm_password = $_POST['confirm_password'];
    $profile_image = !empty($_FILES['profile_image']['name']) ? file_get_contents($_FILES['profile_image']['tmp_name']) : $member['profile_image'];

    if ($_POST['password'] && ($_POST['password'] !== $confirm_password)) {
        echo "<script>
                alert('Passwords do not match. Please try again.');
                window.history.back();
              </script>";
    } else {
        // Check if profile image is set, otherwise use NULL
        $profile_image_param = $profile_image ?: NULL;

        $update_query = $conn->prepare("UPDATE members SET full_name = ?, email = ?, phone_number = ?, password = ?, profile_image = ? WHERE member_id = ?");
        $update_query->bind_param("ssssbi", $full_name, $email, $phone_number, $password, $profile_image_param, $member_id);

        if ($update_query->execute()) {
            echo "<script>
                    alert('Account settings updated successfully!');
                    window.location.href = 'settings.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Error updating account settings: " . $conn->error . "');
                    window.history.back();
                  </script>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/main.css">
    <style>
        .account-settings-container {
            margin-left: 260px;
            padding: 80px 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
            color: #333;
            font-family: Arial, sans-serif;
            overflow-y: auto;
            max-height: 70vh;
        }
        .account-settings-container h2 {
            font-size: 2em;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }
        .account-settings-container form {
            width: 100%;
        }
        .account-settings-container label {
            font-weight: bold;
            margin-top: 15px;
            display: block;
            color: #555;
        }
        .account-settings-container input[type="text"],
        .account-settings-container input[type="email"],
        .account-settings-container input[type="password"],
        .account-settings-container input[type="file"] {
            padding: 12px;
            width: 100%;
            margin: 10px 0 20px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }
        .account-settings-container button.btn-primary {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }
        .account-settings-container button.btn-primary:hover {
            background-color: #45a049;
        }
        .account-settings-container .note {
            font-size: 0.9em;
            color: #888;
            margin-top: -10px;
            display: block;
        }
        .account-settings-container p.success, .account-settings-container p.error {
            text-align: center;
            font-size: 1em;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .account-settings-container p.success {
            color: green;
        }
        .account-settings-container p.error {
            color: red;
        }
    </style>
</head>
<body>
    <?php include('include/sidebar.php'); ?>
    <?php include('include/header.php'); ?>

    <div class="account-settings-container">
        <h2>Account Settings</h2>
        
        <!-- Success and Error Messages -->
        <?php if (isset($success_message)) echo "<p class='success'>$success_message</p>"; ?>
        <?php if (isset($error_message)) echo "<p class='error'>$error_message</p>"; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <label>Full Name</label>
            <input type="text" name="full_name" value="<?= htmlspecialchars($member['full_name']) ?>" required>

            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($member['email']) ?>" required>

            <label>Phone Number</label>
            <input type="text" name="phone_number" value="<?= htmlspecialchars($member['phone_number']) ?>" required>

            <label>New Password (leave blank to keep current password)</label>
            <input type="password" name="password">
            <span class="note">Enter a new password to update.</span>

            <label>Confirm New Password</label>
            <input type="password" name="confirm_password">
            <span class="note">Re-enter the new password to confirm.</span>

            <label>Profile Image</label>
            <input type="file" name="profile_image">
            <span class="note">Upload a new profile image to update.</span>

            <button type="submit" class="btn-primary">Update Settings</button>
        </form>
    </div>
</body>
</html>
