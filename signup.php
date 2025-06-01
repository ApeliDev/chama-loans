<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Juliet</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/signup.css">
</head>
<body>

    <div class="juliet-container">
        <div class="juliet-logo">
            <!-- Replace with your logo -->
            <img src="images/logo.png" alt="Juliet Logo">
        </div>

        <?php if (isset($_SESSION['signup_error'])): ?>
            <div class="juliet-alert juliet-alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($_SESSION['signup_error']); ?>
            </div>
            <?php unset($_SESSION['signup_error']); ?>
        <?php endif; ?>

        <form class="juliet-signup-form" action="signup-process.php" method="POST" id="signupForm">
            <h2>Create an Account</h2>
            
            <div class="juliet-form-group">
                <label for="full_name">Full Name</label>
                <div class="juliet-input-with-icon">
                    <i class="fas fa-user"></i>
                    <input type="text" id="full_name" name="full_name" required
                           value="<?php echo isset($_SESSION['form_data']['full_name']) ? htmlspecialchars($_SESSION['form_data']['full_name']) : ''; ?>">
                </div>
            </div>
            
            <div class="juliet-form-group">
                <label for="phone_number">Phone Number</label>
                <div class="juliet-input-with-icon">
                    <i class="fas fa-phone"></i>
                    <input type="tel" id="phone_number" name="phone_number" required
                           value="<?php echo isset($_SESSION['form_data']['phone_number']) ? htmlspecialchars($_SESSION['form_data']['phone_number']) : ''; ?>">
                </div>
                <small>Format: 10-15 digits (no spaces or special characters)</small>
            </div>
            
            <div class="juliet-form-group">
                <label for="email">Email</label>
                <div class="juliet-input-with-icon">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email" required
                           value="<?php echo isset($_SESSION['form_data']['email']) ? htmlspecialchars($_SESSION['form_data']['email']) : ''; ?>">
                </div>
                <small>We'll never share your email with anyone else.</small>
            </div>
            
            <div class="juliet-form-group">
                <label for="national_id">National ID</label>
                <div class="juliet-input-with-icon">
                    <i class="fas fa-id-card"></i>
                    <input type="text" id="national_id" name="national_id" required
                           value="<?php echo isset($_SESSION['form_data']['national_id']) ? htmlspecialchars($_SESSION['form_data']['national_id']) : ''; ?>">
                </div>
                <small>Your unique National ID number</small>
            </div>
            
            <div class="juliet-form-group">
                <label for="password">Password</label>
                <div class="juliet-input-with-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="juliet-password-strength" id="passwordStrength">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <small>Must be at least 8 characters with uppercase, number, and special character</small>
            </div>
            
            <div class="juliet-form-group">
                <label for="confirm_password">Confirm Password</label>
                <div class="juliet-input-with-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <small id="passwordMatchText"></small>
            </div>
            
            <div class="juliet-terms-checkbox">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">
                    I agree to the <a href="terms.php" target="_blank">Terms and Conditions</a> and <a href="privacy.php" target="_blank">Privacy Policy</a>
                </label>
            </div>
            
            <div class="juliet-form-group">
                <button type="submit" class="juliet-continue-button">Create Account</button>
            </div>
        </form>
        
        <div class="juliet-login-link">
            Already have an account? <a href="login.php">Log in</a>
        </div>
    </div>

    <script>
        // Password strength indicator
        const passwordInput = document.getElementById('password');
        const passwordStrength = document.getElementById('passwordStrength');
        const strengthSpans = passwordStrength.querySelectorAll('span');
        
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            
            // Length check
            if (password.length >= 8) strength++;
            if (password.length >= 12) strength++;
            
            // Complexity checks
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            // Update visual indicator
            strengthSpans.forEach((span, index) => {
                span.classList.toggle('active', index < strength);
            });
        });
        
        // Password match checker
        const confirmPasswordInput = document.getElementById('confirm_password');
        const passwordMatchText = document.getElementById('passwordMatchText');
        
        function checkPasswordMatch() {
            if (passwordInput.value && confirmPasswordInput.value) {
                if (passwordInput.value === confirmPasswordInput.value) {
                    passwordMatchText.textContent = "Passwords match!";
                    passwordMatchText.style.color = "green";
                } else {
                    passwordMatchText.textContent = "Passwords do not match!";
                    passwordMatchText.style.color = "red";
                }
            } else {
                passwordMatchText.textContent = "";
            }
        }
        
        passwordInput.addEventListener('input', checkPasswordMatch);
        confirmPasswordInput.addEventListener('input', checkPasswordMatch);
        
        // Form validation
        document.getElementById('signupForm').addEventListener('submit', function(e) {
            if (!document.getElementById('terms').checked) {
                e.preventDefault();
                alert('You must agree to the terms and conditions.');
            }
        });
    </script>

    <?php 
    // Clear form data from session after displaying it
    if (isset($_SESSION['form_data'])) {
        unset($_SESSION['form_data']);
    }
    ?>
</body>
</html>