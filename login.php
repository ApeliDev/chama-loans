<?php
session_start();
include('include/db_connect.php');

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kenai Chama</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <div class="login-container">
        <div class="logo">
            <img src="images/logo.png" alt="Kenai Chama Logo">
        </div>

        <h2>Welcome Back</h2>

        <?php if (isset($_SESSION['login_error'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle" style="margin-right: 8px;"></i>
                <?php echo htmlspecialchars($_SESSION['login_error']); ?>
                <?php unset($_SESSION['login_error']); ?>
            </div>
        <?php endif; ?>

        <form action="login-process.php" method="POST" id="loginForm">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <div class="form-group">
                <label for="username">Email or Phone Number</label>
                <div class="input-with-icon">
                    <i class="fas fa-user"></i>
                    <input type="text" id="username" name="username" placeholder="Enter your email or phone number" required
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-with-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    <button type="button" class="toggle-password" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="remember-forgot">
                <!--<div class="remember-me">
                    <input type="checkbox" id="remember_me" name="remember_me">
                    <label for="remember_me">Remember me</label>
                </div>-->
                <div class="forgot-password">
                    <a href="forgot-password.php">Forgot password?</a>
                </div>
            </div>

            <button type="submit" class="login-btn">Login</button>
        </form>

        <div class="signup-link">
            Don't have an account? <a href="signup.php">Sign up</a>
        </div>
    </div>

    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const eyeIcon = togglePassword.querySelector('i');

        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');
            
            // Optional: Change the title tooltip
            this.setAttribute('title', type === 'password' ? 'Show password' : 'Hide password');
        });

        // Form submission handler
        document.getElementById('loginForm').addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';
        });
    </script>
</body>

</html>