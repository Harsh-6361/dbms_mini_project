<?php   
session_start();  
if(!isset($_SESSION["sess_ename"])){  
    header("location:adminlogin.php");  
} else {
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Employee</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r121/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.clouds.min.js"></script>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden;
            font-family: 'Yantramanav', sans-serif;
            color: #261A15;
        }
        .vanta-background {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
        }
        .content {
            position: relative;
            z-index: 1;
            padding: 20px;
            text-align: center;
        }
        .logout-button {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #f44336;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .logout-button a {
            color: white;
            text-decoration: none;
        }
        h1 {
            color: black;
            margin-bottom: 40px;
        }
        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        .card {
            background-color: rgba(255, 255, 255, 0.8);
            color: black;
            border-radius: 10px;
            padding: 20px;
            width: 250px;
            text-align: left;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .card h3 {
            margin: 0 0 10px;
            font-size: 1.2em;
        }
        .card p {
            margin: 0 0 10px;
        }
        .card button {
            margin-top: 10px;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        .card button a {
            color: white;
            text-decoration: none;
            display: block;
        }
    </style>
</head>
<body>
    <div class="vanta-background"></div>
    <div class="content">
        <button class="logout-button"><a href="adminlogout.php">Logout</a></button>
        <center><h1><u>AIRLINE RESERVATION SYSTEM</u></h1></center>
        <h2>Welcome</h2>
        <div class="card-container">
            <div class="card">
                <h3>Add Flight</h3>
                <p>Add new flights to the system including details like destination, time, and date.</p>
                <button><a href="add_flight.php">Add Flight</a></button>
            </div>
            <div class="card">
                <h3>View Flights</h3>
                <p>View all the flights currently available in the system and their details.</p>
                <button><a href="viewflights.php">View Flights</a></button>
            </div>
            <div class="card">
                <h3>Update Timings</h3>
                <p>Update the flight timings and schedules for existing flights.</p>
                <button><a href="Updatetimings.php">Update Timings</a></button>
            </div>
            <div class="card">
                <h3>Bookings</h3>
                <p>View and manage all the bookings made by passengers.</p>
                <button><a href="bookings.php">View Bookings</a></button>
            </div>
        </div>
    </div>
    <script>
        var setVanta = () => {
            if (window.VANTA) {
                window.VANTA.CLOUDS({
                    el: ".vanta-background",
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
            } else {
                console.error('VANTA is not defined');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            setVanta();
        });
    </script>
</body>
</html>
<?php
}
?>
