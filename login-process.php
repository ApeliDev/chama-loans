<?php
session_start();
include('include/db_connect.php');

// Function to redirect with error message
function redirectWithError($error) {
    $_SESSION['login_error'] = $error;
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        redirectWithError("Invalid request. Please try again.");
    }

    // Get and sanitize form data
    $username = trim($conn->real_escape_string($_POST['username']));
    $password = $_POST['password'];
    $remember_me = isset($_POST['remember_me']) ? 1 : 0;

    // Validate inputs
    if (empty($username) || empty($password)) {
        redirectWithError("Both username and password are required.");
    }

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM members WHERE email = ? OR phone_number = ?");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password with timing-safe comparison
        if (password_verify($password, $user['password'])) {
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);

            // Set session variables
            $_SESSION['user_id'] = $user['member_id'];
            $_SESSION['username'] = $user['full_name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['logged_in'] = true;
            $_SESSION['last_activity'] = time();

            // Set remember me cookie if selected
            if ($remember_me) {
                $token = bin2hex(random_bytes(32));
                $expiry = time() + 60 * 60 * 24 * 30; // 30 days
                
                // Store token in database
                $update_stmt = $conn->prepare("UPDATE members SET remember_token = ?, token_expiry = ? WHERE member_id = ?");
                $update_stmt->bind_param("ssi", $token, date('Y-m-d H:i:s', $expiry), $user['member_id']);
                $update_stmt->execute();
                $update_stmt->close();
                
                // Set cookie
                setcookie('remember_token', $token, $expiry, '/', '', true, true);
            }

            // Clear any existing error
            unset($_SESSION['login_error']);

            // Redirect to dashboard
            header("Location: ./users/dashboard.php");
            exit();
        } else {
            // Invalid password - use generic message to prevent user enumeration
            redirectWithError("Invalid credentials. Please try again.");
        }
    } else {
        // User not found - use generic message to prevent user enumeration
        redirectWithError("Invalid credentials. Please try again.");
    }

    $stmt->close();
    $conn->close();
} else {
    // Redirect if accessed directly
    header("Location: login.php");
    exit();
}
?>