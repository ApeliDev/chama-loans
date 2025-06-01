<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #6a11cb, #2575fc);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .reset-container {
            width: 350px;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
        }

        .reset-container h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            color: #e3e3e3;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .form-group input::placeholder {
            color: #ccc;
        }

        .form-group input:focus {
            outline: none;
            background-color: rgba(255, 255, 255, 0.3);
        }

        .reset-btn {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            color: #fff;
            background-color: #4caf50;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .reset-btn:hover {
            background-color: #45a049;
        }

        .message {
            text-align: center;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <h2>Reset Your Password</h2>
        <form action="process_reset_password.php" method="POST">
            <div class="form-group">
                <label for="new-password">New Password:</label>
                <input type="password" name="new_password" id="new-password" placeholder="Enter new password" required>
            </div>
            <div class="form-group">
                <label for="confirm-password">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm-password" placeholder="Confirm new password" required>
            </div>
            <button type="submit" class="reset-btn">Reset Password</button>
        </form>
    </div>
</body>
</html>
