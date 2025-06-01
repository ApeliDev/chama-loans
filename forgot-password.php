<?php
session_start();
include('include/db_connect.php');

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['error'] = "Invalid request. Please try again.";
        header("Location: forgot-password.php");
        exit();
    }

    $email = trim($conn->real_escape_string($_POST['email']));

    // Validate email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Please enter a valid email address.";
        header("Location: forgot-password.php");
        exit();
    }

    // Check if email exists
    $stmt = $conn->prepare("SELECT member_id, full_name FROM members WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Generate reset token (valid for 1 hour)
        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", time() + 3600); // 1 hour from now
        
        // Store token in database
        $update_stmt = $conn->prepare("UPDATE members SET reset_token = ?, reset_token_expiry = ? WHERE member_id = ?");
        $update_stmt->bind_param("ssi", $token, $expiry, $user['member_id']);
        $update_stmt->execute();
        $update_stmt->close();
        
        $reset_link = "https://kenai/reset-password.php?token=" . urlencode($token);
        $subject = "Password Reset Request";
        $message = "Hello " . htmlspecialchars($user['full_name']) . ",\n\n";
        $message .= "You requested to reset your password. Click the link below to proceed:\n\n";
        $message .= $reset_link . "\n\n";
        $message .= "This link will expire in 1 hour.\n\n";
        $message .= "If you didn't request this, please ignore this email.\n";
        
        // For demo purposes, we'll store the link in session
        $_SESSION['demo_reset_link'] = $reset_link;
        $_SESSION['success'] = "Password reset link has been sent to your email.";
    } else {
        // Don't reveal whether email exists (security best practice)
        $_SESSION['success'] = "If your email exists in our system, you'll receive a password reset link.";
    }
    
    header("Location: forgot-password.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Kenai Chama</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-dark: #4338ca;
            --error-color: #dc2626;
            --success-color: #10b981;
            --text-color: #1f2937;
            --light-gray: #f3f4f6;
            --border-color: #d1d5db;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--light-gray);
            color: var(--text-color);
            line-height: 1.6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        .forgot-container {
            width: 100%;
            max-width: 420px;
            padding: 2.5rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .logo {
            margin-bottom: 1.5rem;
        }

        .logo img {
            height: 60px;
        }

        h2 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-size: 1.8rem;
        }

        p.description {
            color: #6b7280;
            margin-bottom: 1.5rem;
        }

        .alert {
            padding: 0.75rem 1rem;
            margin-bottom: 1.5rem;
            border-radius: 6px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .alert-error {
            background-color: #fee2e2;
            color: var(--error-color);
            border-left: 4px solid var(--error-color);
        }

        .alert-success {
            background-color: #d1fae5;
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }

        .alert-info {
            background-color: #e0f2fe;
            color: #0369a1;
            border-left: 4px solid #0369a1;
        }

        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-color);
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .submit-btn {
            width: 100%;
            padding: 0.75rem;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-bottom: 1.5rem;
        }

        .submit-btn:hover {
            background-color: var(--primary-dark);
        }

        .back-to-login {
            text-align: center;
            font-size: 0.9rem;
        }

        .back-to-login a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .back-to-login a:hover {
            text-decoration: underline;
        }

        .demo-reset-link {
            margin-top: 1.5rem;
            padding: 1rem;
            background-color: #f3f4f6;
            border-radius: 8px;
            word-break: break-all;
            font-size: 0.8rem;
            display: none; /* Hidden by default */
        }

        @media (max-width: 480px) {
            .forgot-container {
                padding: 1.5rem;
                margin: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="forgot-container">
        <div class="logo">
            <img src="images/logo.png" alt="Kenai Chama Logo">
        </div>

        <h2>Forgot Password</h2>
        <p class="description">Enter your email address and we'll send you a link to reset your password.</p>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle" style="margin-right: 8px;"></i>
                <?php echo htmlspecialchars($_SESSION['error']); ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle" style="margin-right: 8px;"></i>
                <?php echo htmlspecialchars($_SESSION['success']); ?>
                <?php unset($_SESSION['success']); ?>
            </div>
            
            <?php if (isset($_SESSION['demo_reset_link'])): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle" style="margin-right: 8px;"></i>
                    Demo: Below is your reset link (would be emailed in production)
                </div>
                <div class="demo-reset-link" id="demoResetLink">
                    <?php echo htmlspecialchars($_SESSION['demo_reset_link']); ?>
                    <?php unset($_SESSION['demo_reset_link']); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <form action="forgot-password.php" method="POST" id="forgotForm">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-with-icon">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email" placeholder="Enter your email address" required>
                </div>
            </div>

            <button type="submit" class="submit-btn">Send Reset Link</button>
        </form>

        <div class="back-to-login">
            <a href="login.php"><i class="fas fa-arrow-left"></i> Back to login</a>
        </div>
    </div>

    <script>
        // Show demo reset link if it exists
        document.addEventListener('DOMContentLoaded', function() {
            const demoLink = document.getElementById('demoResetLink');
            if (demoLink && demoLink.textContent.trim() !== '') {
                demoLink.style.display = 'block';
            }
            
            // Form submission handler
            document.getElementById('forgotForm').addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            });
        });
    </script>
</body>
</html>