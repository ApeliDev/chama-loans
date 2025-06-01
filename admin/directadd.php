<?php
include('../include/db_connect.php');

// Initialize variables
$errors = [];
$success = false;

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize form inputs
    $full_name = trim(htmlspecialchars($_POST['full_name']));
    $email = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $role = 'admin'; // Default role

    // Validate inputs
    if (empty($full_name)) {
        $errors['full_name'] = "Full name is required";
    } elseif (strlen($full_name) < 3) {
        $errors['full_name'] = "Full name must be at least 3 characters";
    }

    if (empty($email)) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    }

    if (empty($password)) {
        $errors['password'] = "Password is required";
    } elseif (strlen($password) < 8) {
        $errors['password'] = "Password must be at least 8 characters";
    } elseif (!preg_match("/[A-Z]/", $password) || !preg_match("/[0-9]/", $password)) {
        $errors['password'] = "Password must contain at least one uppercase letter and one number";
    }

    if ($password !== $confirm_password) {
        $errors['confirm_password'] = "Passwords do not match";
    }

    // Check if the email already exists only if email is valid
    if (empty($errors['email'])) {
        $check_email_query = "SELECT * FROM adminusers WHERE email = ?";
        $stmt = $conn->prepare($check_email_query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errors['email'] = "Email already exists";
        }
    }

    // If no errors, proceed with insertion
    if (empty($errors)) {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Generate a unique username
        $base_username = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $full_name));
        $username = $base_username;
        $counter = 1;
        
        // Ensure username is unique
        while (true) {
            $check_username = "SELECT admin_id FROM adminusers WHERE username = ?";
            $stmt = $conn->prepare($check_username);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                break;
            }
            
            $username = $base_username . '_' . $counter;
            $counter++;
        }

        // Insert the new admin into the database
        $insert_query = "INSERT INTO adminusers (username, password, email, role, full_name, created_at) 
                         VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("sssss", $username, $hashed_password, $email, $role, $full_name);

        if ($stmt->execute()) {
            $success = true;
            $_SESSION['success_message'] = "Admin added successfully!";
            header("Location: admin.php");
            exit;
        } else {
            $errors['database'] = "Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Admin</title>
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
        
        .admin-form-container {
            width: 100%;
            max-width: 500px;
            padding: 2.5rem;
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
        }
        
        .admin-form-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 8px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .form-header i {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        
        .form-header h1 {
            font-size: 1.5rem;
            color: var(--dark);
            font-weight: 600;
        }
        
        .form-group {
            margin-bottom: 1.25rem;
        }
        
        .form-group label {
            display: block;
            font-size: 0.875rem;
            color: var(--dark);
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 0.9375rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background-color: #f9fafb;
            color: var(--dark);
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
            background-color: white;
        }
        
        .error-message {
            color: var(--danger);
            font-size: 0.75rem;
            margin-top: 0.25rem;
            display: block;
        }
        
        .submit-btn {
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
        
        .submit-btn:hover {
            background-color: var(--primary-dark);
        }
        
        .success-message {
            text-align: center;
            color: var(--success);
            font-size: 0.875rem;
            margin-top: 1rem;
            padding: 0.75rem;
            background-color: rgba(16, 185, 129, 0.1);
            border-radius: 8px;
        }
        
        .password-strength {
            margin-top: 0.25rem;
            font-size: 0.75rem;
            color: #6b7280;
        }
        
        .password-strength.strong {
            color: var(--success);
        }
        
        .password-strength.weak {
            color: var(--danger);
        }
        
        @media (max-width: 480px) {
            .admin-form-container {
                padding: 1.5rem;
                margin: 0 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="admin-form-container">
        <div class="form-header">
            <i class="fas fa-user-plus"></i>
            <h1>Add New Admin</h1>
        </div>
        
        <?php if (!empty($errors['database'])): ?>
            <div class="error-message" style="margin-bottom: 1rem;">
                <i class="fas fa-exclamation-circle"></i> <?php echo $errors['database']; ?>
            </div>
        <?php endif; ?>
        
        <form action="" method="POST" autocomplete="off">
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" name="full_name" id="full_name" class="form-control" 
                       value="<?php echo htmlspecialchars($full_name ?? ''); ?>" required>
                <?php if (!empty($errors['full_name'])): ?>
                    <span class="error-message"><i class="fas fa-exclamation-circle"></i> <?php echo $errors['full_name']; ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" 
                       value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                <?php if (!empty($errors['email'])): ?>
                    <span class="error-message"><i class="fas fa-exclamation-circle"></i> <?php echo $errors['email']; ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
                <div class="password-strength" id="password-strength"></div>
                <?php if (!empty($errors['password'])): ?>
                    <span class="error-message"><i class="fas fa-exclamation-circle"></i> <?php echo $errors['password']; ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                <?php if (!empty($errors['confirm_password'])): ?>
                    <span class="error-message"><i class="fas fa-exclamation-circle"></i> <?php echo $errors['confirm_password']; ?></span>
                <?php endif; ?>
            </div>
            
            <button type="submit" class="submit-btn">
                <i class="fas fa-save"></i> Create Admin
            </button>
        </form>
    </div>

    <script>
        // Password strength indicator
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthIndicator = document.getElementById('password-strength');
            
            if (password.length === 0) {
                strengthIndicator.textContent = '';
                strengthIndicator.className = 'password-strength';
                return;
            }
            
            // Calculate strength
            let strength = 0;
            if (password.length >= 8) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^A-Za-z0-9]/)) strength++;
            
            // Display result
            if (password.length < 8) {
                strengthIndicator.textContent = 'Password too short (min 8 characters)';
                strengthIndicator.className = 'password-strength weak';
            } else if (strength < 3) {
                strengthIndicator.textContent = 'Weak password';
                strengthIndicator.className = 'password-strength weak';
            } else if (strength < 4) {
                strengthIndicator.textContent = 'Good password';
                strengthIndicator.className = 'password-strength';
            } else {
                strengthIndicator.textContent = 'Strong password';
                strengthIndicator.className = 'password-strength strong';
            }
        });
    </script>
</body>
</html>