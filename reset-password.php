<?php
session_start();
include('include/db_connect.php');

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Check if token is provided
if (!isset($_GET['token']) || empty($_GET['token'])) {
    $_SESSION['error'] = "Invalid password reset link.";
    header("Location: forgot-password.php");
    exit();
}

$token = $conn->real_escape_string($_GET['token']);

// Verify token
$stmt = $conn->prepare("SELECT member_id, reset_token_expiry FROM members WHERE reset_token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = "Invalid or expired password reset link.";
    header("Location: forgot-password.php");
    exit();
}

$user = $result->fetch_assoc();

// Check if token is expired
if (strtotime($user['reset_token_expiry']) < time()) {
    $_SESSION['error'] = "Password reset link has expired.";
    header("Location: forgot-password.php");
    exit();
}

// Process password reset form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['error'] = "Invalid request. Please try again.";
        header("Location: reset-password.php?token=" . urlencode($token));
        exit();
    }

    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate password
    if (empty($password) || empty($confirm_password)) {
        $_SESSION['error'] = "Both password fields are required.";
        header("Location: reset-password.php?token=" . urlencode($token));
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: reset-password.php?token=" . urlencode($token));
        exit();
    }

    if (strlen($password) < 8) {
        $_SESSION['error'] = "Password must be at least 8 characters long.";
        header("Location: reset-password.php?token=" . urlencode($token));
        exit();
    }

    // Update password and clear reset token
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $update_stmt = $conn->prepare("UPDATE members SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE member_id = ?");
    $update_stmt->bind_param("si", $hashed_password, $user['member_id']);
    $update_stmt->execute();
    $update_stmt->close();

    $_SESSION['success'] = "Your password has been reset successfully. You can now login with your new password.";
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Kenai Chama</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Same styles as forgot-password.php */
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

        .reset-container {
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

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem 2.5rem 0.75rem 2.5rem;
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

        @media (max-width: 480px) {
            .reset-container {
                padding: 1.5rem;
                margin: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="logo">
            <img src="images/logo.png" alt="Kenai Chama Logo">
        </div>

        <h2>Reset Your Password</h2>
        <p class="description">Create a new password for your account.</p>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle" style="margin-right: 8px;"></i>
                <?php echo htmlspecialchars($_SESSION['error']); ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="reset-password.php?token=<?php echo urlencode($token); ?>" method="POST" id="resetForm">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <div class="form-group">
                <label for="password">New Password</label>
                <div class="input-with-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="Enter new password" required>
                    <button type="button" class="toggle-password" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <small>Must be at least 8 characters long</small>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <div class="input-with-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password" required>
                    <button type="button" class="toggle-password" id="toggleConfirmPassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="submit-btn">Reset Password</button>
        </form>
    </div>

    <script>
        // Toggle password visibility
        function setupPasswordToggle(inputId, buttonId) {
            const toggleBtn = document.getElementById(buttonId);
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = toggleBtn.querySelector('i');

            toggleBtn.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                eyeIcon.classList.toggle('fa-eye');
                eyeIcon.classList.toggle('fa-eye-slash');
            });
        }

        // Initialize toggles for both password fields
        document.addEventListener('DOMContentLoaded', function() {
            setupPasswordToggle('password', 'togglePassword');
            setupPasswordToggle('confirm_password', 'toggleConfirmPassword');
            
            // Form submission handler
            document.getElementById('resetForm').addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Resetting...';
            });
        });
    </script>
</body>
</html>