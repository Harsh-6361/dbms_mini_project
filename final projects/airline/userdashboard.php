<?php
session_start();

if (!isset($_SESSION["sess_user"])) {
    header("location:userlogin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r121/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.clouds.min.js"></script>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            overflow-y: auto;
        }
        .vanta-background {
            position: fixed;
            width: 100%;
            height: 100vh;
            top: 0;
            left: 0;
            z-index: -1;
        }
        body {
            font-family: 'Yantramanav', sans-serif;
            font-size: 110%;
            color: #261A15;
            background-attachment: fixed;
        }
        .content {
            padding: 30px;
            position: relative;
            z-index: 1;
        }
        h1 {
            font-family: 'Open Sans', sans-serif;
            font-size: 150%;
            color: green;
            text-align: center;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
            margin-bottom: 10px;
        }
        h2 {
            text-align: center;
            color: #261A15;
        }
        a {
            color: blue;
            text-decoration: none;
        }
        .button-container {
            text-align: center;
            margin-top: 20px;
        }
        .button {
            margin: 10px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .button a {
            color: white;
            text-decoration: none;
        }
        .header-buttons {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .header-buttons button {
            margin-left: 10px;
        }
    </style>
</head>
<body class="vanta-background">
<script>
  // Initialize VANTA background
  VANTA.CLOUDS({
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
</script>
<div class="content">
    <div class="header-buttons">
        <button class="button">
            <a href="user_view.php" style="color:blue">My Booking</a>
        </button>
        <button class="button">
            <a href="logout1.php" style="color:blue">Logout</a>
        </button>
    </div>
    <h1><u>AIRLINE RESERVATION SYSTEM</u></h1>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION["sess_user"]); ?></h2>
    <form method="POST" action="search_flights.php">
        
           
            <center>
            <h3>Search Flights</h3>
                <br>
                <b>Depart On:</b>
                <input type="date" name="depdate" required/>
                <br><br>
                <b>From:</b>
                <select name="from1" required>
                    <option value="">Select Departure</option>
                    <option value="Bangalore">Bangalore</option>
                    <option value="Mumbai">Mumbai</option>
                    <option value="Delhi">Delhi</option>
                    <option value="Chennai">Chennai</option>
                </select>
                &nbsp; &nbsp; &nbsp; &nbsp;
                <b>To:</b>
                <select name="to1" required>
                    <option value="">Select Destination</option>
                    <option value="Bangalore">Bangalore</option>
                    <option value="Mumbai">Mumbai</option>
                    <option value="Delhi">Delhi</option>
                    <option value="Chennai">Chennai</option>
                </select>
                <br><br>
                <input type="submit" value="Proceed" name="proceed" />
            </center>
        
    </form>
</div>
</body>
</html>
