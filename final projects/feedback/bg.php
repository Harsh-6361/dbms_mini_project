<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }
        #vanta-bg {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: -1; /* Send to back */
        }
        .content {
            position: relative;
            z-index: 1;
            /* Add any additional styles for your content */
        }
    </style>
</head>
<body>
    <div id="vanta-bg"></div>
    <div class="content">
        <!-- Your content will go here -->
    </div>

    <!-- Include the required scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r121/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.net.min.js"></script>
    <script>
        VANTA.NET({
            el: "#vanta-bg",
            mouseControls: true,
            touchControls: true,
            gyroControls: false,
            minHeight: 200.00,
            minWidth: 200.00,
            scale: 1.00,
            scaleMobile: 1.00
        });
    </script>
</body>
</html>
