<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AIRLINE RESERVATION SYSTEM</title>
    <!-- Include Vanta.js and Three.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r121/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.clouds.min.js"></script>
    <style>
        body {
            margin: 0;
            font-family: 'Yantramanav', sans-serif;
            color: #ffffff; /* White text color */
            font-size: 70%;
            height: 100vh; /* Full viewport height */
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: black; /* Fallback background color */
            overflow: hidden; /* Hide overflow for Vanta.js full screen effect */
        }
        .content {
            text-align: center;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        h1 {
            font-size: 6em; /* Adjust font size as needed */
            margin: 0;
            padding: 20px;
            position: relative;
            display: inline-block;
            text-transform: uppercase;
            color: #261A15; /* Dark text color */
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8); /* Text shadow for 3D effect */
        }
        .card-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        .card {
            background-color: rgba(0, 0, 0, 0.3); /* Semi-transparent white */
            border-radius: 10px;
            padding: 20px;
            width: 300px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-10px); /* Lift card slightly on hover */
        }
        .card h2 {
            font-size: 2em;
            margin-top: 0;
            color: #261A15; /* Dark text color */
        }
        .card p {
            font-size: 1.2em;
            margin: 10px 0;
            color: #ffffff; /* White text color */
        }
        .card a {
            color: #ffffff; /* White link color */
            text-decoration: none;
            font-size: 24px; /* Adjust link font size */
            display: block;
            text-align: center;
            transition: color 0.3s ease; /* Smooth color transition */
            margin-top: 10px;
        }
        .card a:hover {
            color: #66ff66; /* Brighter color on hover */
        }
    </style>
</head>
<body id="vanta-background" class="vanta-background">
    <!-- Vanta.js Clouds Background -->
    <div></div>

    <!-- Content -->
    <div class="content">
        <h1>AIRLINE RESERVATION SYSTEM</h1>
        <div class="card-container">
            <div class="card">
                <h2>User Login</h2>
                <p>Log in as a registered user to access your account and manage bookings.</p>
                <a href="login.php">Login</a>
            </div>
            <div class="card">
                <h2>Admin Login</h2>
                <p>Admins can log in to manage flights, user accounts, and system configurations.</p>
                <a href="adminlogin.php">Login</a>
            </div>
        </div>
    </div>

    <!-- Script for Vanta.js Clouds Background -->
    <script>
        // Initialize Vanta.js background
        document.addEventListener('DOMContentLoaded', function() {
            VANTA.CLOUDS({
                el: "#vanta-background",
                mouseControls: true,
                touchControls: true,
                gyroControls: false,
                minHeight: 200.00,
                minWidth: 200.00,
                skyColor: 0x589eb8,
                cloudColor: 0xc0c0d9,
                cloudShadowColor: 0x38749d,
                sunColor: 0xff6100,
                sunGlareColor: 0xff7200,
                sunlightColor: 0xffffff,
                speed: 0.70
            });
        });
    </script>
</body>
</html>
