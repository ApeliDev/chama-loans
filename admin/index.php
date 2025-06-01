<?php
session_start();

include('../include/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and retrieve form data
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Prepare the SQL query to check the email and password in the database
    $query = "SELECT * FROM adminusers WHERE email = ? AND status = 'active'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the user details
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Password is correct, start the session
            $_SESSION['admin_id'] = $user['admin_id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $user['username'];

            header('Location: dashboard.php');
            exit();
        } else {
            $error_message = "Invalid password. Please try again.";
        }
    } else {
        $error_message = "No account found with that email address.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal | Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --secondary: #3f37c9;
            --dark: #1f2937;
            --light: #f9fafb;
            --danger: #ef4444;
            --success: #10b981;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: 
                radial-gradient(at 80% 0%, hsla(189, 100%, 56%, 0.1) 0px, transparent 50%),
                radial-gradient(at 0% 50%, hsla(355, 100%, 93%, 0.1) 0px, transparent 50%);
        }
        
        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 2.5rem;
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
        }
        
        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 8px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }
        
        .logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .logo i {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        
        .logo h1 {
            font-size: 1.5rem;
            color: var(--dark);
            font-weight: 600;
        }
        
        .logo p {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }
        
        .form-group {
            margin-bottom: 1.25rem;
            position: relative;
        }
        
        .form-group label {
            display: block;
            font-size: 0.875rem;
            color: var(--dark);
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .input-wrapper i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 1rem;
        }
        
        .form-group input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            font-size: 0.9375rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background-color: #f9fafb;
            color: var(--dark);
            transition: all 0.3s ease;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
            background-color: white;
        }
        
        .form-group input::placeholder {
            color: #9ca3af;
        }
        
        .login-btn {
            width: 100%;
            padding: 0.875rem;
            font-size: 1rem;
            font-weight: 500;
            color: white;
            background-color: var(--primary);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 0.5rem;
        }
        
        .login-btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-1px);
        }
        
        .login-btn:active {
            transform: translateY(0);
        }
        
        .footer-links {
            display: flex;
            justify-content: space-between;
            margin-top: 1.5rem;
            font-size: 0.875rem;
        }
        
        .footer-links a {
            color: #6b7280;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer-links a:hover {
            color: var(--primary);
        }
        
        .error-message {
            text-align: center;
            color: var(--danger);
            font-size: 0.875rem;
            margin-top: 1rem;
            padding: 0.75rem;
            background-color: rgba(239, 68, 68, 0.1);
            border-radius: 8px;
            display: <?php echo isset($error_message) ? 'block' : 'none'; ?>;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .remember-me input {
            margin-right: 0.5rem;
        }
        
        @media (max-width: 480px) {
            .login-container {
                padding: 1.5rem;
                margin: 0 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <i class="fas fa-lock"></i>
            <h1>Admin Portal</h1>
            <p>Sign in to access your dashboard</p>
        </div>
        
        <?php if (isset($error_message)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        
        <form action="admin_login.php" method="POST">
            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" id="email" placeholder="your@email.com" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" id="password" placeholder="••••••••" required>
                </div>
            </div>
            
            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember me</label>
            </div>
            
            <button type="submit" class="login-btn">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
        </form>
        
        <div class="footer-links">
            <a href="reset_password.php"><i class="fas fa-key"></i> Forgot password?</a>
            <a href="#"><i class="fas fa-question-circle"></i> Need help?</a>
        </div>
    </div>
</body>
</html>