<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kenai Chama - Support Chat Interface</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/main.css">
    <style>
        #chatPanel {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
            height: 500px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            z-index: 9999;
            overflow: hidden;
            transition: opacity 0.3s ease;
        }

        #chatPanel iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .closeChatBtn {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #f44336;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 50%;
        }

        .closeChatBtn:hover {
            background-color: #d32f2f;
        }

        #overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9998;
        }
        #sidebarLink {
            display: inline-block;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #sidebarLink:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    
    <!-- Sidebar link to open the chat -->
    <div id="sidebarLink" onclick="openChat()">Start Live Chat</div>

    <!-- Overlay background when chat is open -->
    <div id="overlay" onclick="closeChat()"></div>

    <!-- Chat Panel -->
    <div id="chatPanel">
        <button class="closeChatBtn" onclick="closeChat()">X</button>
        <iframe id="tawkFrame" src="about:blank"></iframe>
    </div>

    <script>
        // Function to open chat panel and load Tawk.to
        function openChat() {
            document.getElementById('overlay').style.display = 'block';
            document.getElementById('chatPanel').style.display = 'block';

            // Load Tawk.to live chat script dynamically
            var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
            (function(){
                var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
                s1.async = true;
                s1.src = 'https://embed.tawk.to/your_tawk_id/default';
                s1.charset = 'UTF-8';
                s1.setAttribute('crossorigin', '*');
                s0.parentNode.insertBefore(s1, s0);
            })();
        }

        // Function to close chat panel
        function closeChat() {
            document.getElementById('overlay').style.display = 'none';
            document.getElementById('chatPanel').style.display = 'none';
        }
    </script>

</body>
</html>


<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/673683482480f5b4f59e2c5f/1icmf1kun';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->