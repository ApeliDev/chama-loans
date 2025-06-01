<?php
include('include/db_connect.php');
session_start();

// Function to redirect with error messages
function redirectWithError($error, $formData = []) {
    $_SESSION['signup_error'] = $error;
    $_SESSION['form_data'] = $formData;
    header("Location: signup.php");
    exit();
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $full_name = $conn->real_escape_string(trim($_POST['full_name']));
    $phone_number = $conn->real_escape_string(trim($_POST['phone_number']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $national_id = $conn->real_escape_string(trim($_POST['national_id']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $terms = isset($_POST['terms']) ? 1 : 0;

    // Store form data for repopulation
    $formData = compact('full_name', 'phone_number', 'email', 'national_id');

    // Validate required fields
    if (empty($full_name) || empty($phone_number) || empty($email) || empty($national_id) || empty($password)) {
        redirectWithError("All fields are required.", $formData);
    }

    // Validate terms agreement
    if (!$terms) {
        redirectWithError("You must agree to the terms and conditions.", $formData);
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirectWithError("Invalid email format.", $formData);
    }

    // Validate phone number (basic validation)
    if (!preg_match('/^[0-9]{10,15}$/', $phone_number)) {
        redirectWithError("Phone number must be 10-15 digits.", $formData);
    }

    // Check password match
    if ($password !== $confirm_password) {
        redirectWithError("Passwords do not match.", $formData);
    }

    // Validate password strength
    if (strlen($password) < 8) {
        redirectWithError("Password must be at least 8 characters long.", $formData);
    }
    if (!preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[^A-Za-z0-9]/', $password)) {
        redirectWithError("Password must contain at least one uppercase letter, one number, and one special character.", $formData);
    }

    // Check for existing user by email
    $email_check = $conn->prepare("SELECT member_id FROM members WHERE email = ?");
    $email_check->bind_param("s", $email);
    $email_check->execute();
    $email_check->store_result();
    if ($email_check->num_rows > 0) {
        redirectWithError("Email already registered.", $formData);
    }
    $email_check->close();

    // Check for existing user by phone number
    $phone_check = $conn->prepare("SELECT member_id FROM members WHERE phone_number = ?");
    $phone_check->bind_param("s", $phone_number);
    $phone_check->execute();
    $phone_check->store_result();
    if ($phone_check->num_rows > 0) {
        redirectWithError("Phone number already registered.", $formData);
    }
    $phone_check->close();

    // Check for existing user by national ID
    $id_check = $conn->prepare("SELECT member_id FROM members WHERE national_id = ?");
    $id_check->bind_param("s", $national_id);
    $id_check->execute();
    $id_check->store_result();
    if ($id_check->num_rows > 0) {
        redirectWithError("National ID already registered.", $formData);
    }
    $id_check->close();

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert data into database with prepared statement
    $stmt = $conn->prepare("INSERT INTO members (full_name, email, phone_number, national_id, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $full_name, $email, $phone_number, $national_id, $hashed_password);

    if ($stmt->execute()) {
        // Clear session data
        unset($_SESSION['signup_error']);
        unset($_SESSION['form_data']);
        
        // Set success message
        $_SESSION['signup_success'] = "Account created successfully! You can now login.";
        
        // Redirect to login page
        header("Location: login.php");
        exit();
    } else {
        redirectWithError("Error creating account. Please try again later.", $formData);
    }

    $stmt->close();
    $conn->close();
} else {
    // Redirect if accessed directly
    header("Location: signup.php");
    exit();
}
?>