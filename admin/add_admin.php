<?php
include('../include/db_connect.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize form inputs
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = 'admin';

    // Validate inputs
    if (empty($full_name) || empty($email) || empty($password)) {
        echo "All fields are required.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    // Check if the email already exists
    $check_email_query = "SELECT * FROM adminusers WHERE email = ?";
    $stmt = $conn->prepare($check_email_query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Email already exists.";
        exit;
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new admin into the database
    $insert_query = "INSERT INTO adminusers (username, password, email, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);

    // Generate a default username based on the full name
    $username = strtolower(str_replace(' ', '_', $full_name));

    $stmt->bind_param("ssss", $username, $hashed_password, $email, $role);

    if ($stmt->execute()) {
        echo "Admin added successfully!";
        header("Location: admin.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
