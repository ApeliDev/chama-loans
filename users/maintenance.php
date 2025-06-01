<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Maintenance</title>
    <style>
      
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #f1f1f1, #e2e8f0);
            margin: 0;
            padding: 0;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
            color: #333;
            overflow: hidden;
        }

        
        h1 {
            font-size: 3.5rem;
            color: #e74c3c;
            animation: fadeIn 2s ease-in-out;
            margin-bottom: 20px;
        }

        
        p {
            font-size: 1.5rem;
            color: #555;
            margin-top: 20px;
            animation: fadeIn 3s ease-in-out;
            margin-bottom: 50px;
        }

        
        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            padding: 20px 0;
            background-color: #34495e; 
            color: white;
            text-align: center;
            font-size: 1.2rem;
            font-weight: bold;
        }

        
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

    </style>
</head>
<body>
    <h1>System is Under Maintenance</h1>
    <p>We are currently performing maintenance. Please check back later.</p>
    
    <footer>
        <p style="color: white;">&copy; 2024 Apeli Solutions. All rights reserved.</p>
    </footer>
</body>
</html>
