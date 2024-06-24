<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome Employee</title>
    <style>
        body {
            margin: 0;
            font-family: 'Yantramanav', sans-serif;
            color: #ffffff; /* White text color */
            font-size: 100%;

        }
        .content {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }
        h1 {
            font-size: 2.5em; /* Adjust font size */
            margin: 20px 0;
            text-transform: uppercase;
            color: #261A15; /* Dark text color */
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8); /* Text shadow for 3D effect */
        }
        form {
            width: 80%; /* Adjust form width */
            max-width: 500px; /* Max width for larger screens */
            background-color: rgba(0, 0, 0, 0.8); /* Semi-transparent black */
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
        fieldset {
            border: none;
            margin: 0;
            padding: 0;
        }
        legend {
            font-size: 1.5em;
            color: #ffffff; /* White text color */
            margin-bottom: 10px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #ffffff; /* White text color */
        }
        input[type="text"],
        input[type="datetime-local"],
        input[type="date"],
        input[type="number"],
        select {
            width: calc(100% - 22px); /* Adjust width minus padding */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
            font-size: 1em;
        }
        input[type="submit"] {
            background-color: #4CAF50; /* Green background */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #45a049; /* Darker green on hover */
        }
        .button {
            position: absolute;
            top: 20px;
            left: 20px;
        }
        .button a {
            color: white; /* Button text color */
            background-color: #4CAF50; /* Green button background */
            text-decoration: none;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            display: inline-block;
            transition: background-color 0.3s ease;
        }
        .button a:hover {
            background-color: #45a049; /* Darker green on hover */
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r121/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.clouds.min.js"></script>
    <script>
        function navigateBack() {
            window.location.href = "adminhome.php";
        }
    </script>
</head>
<body class="vanta-background">


    <div class="content">
    <div class="header">
        <h1>AIRLINE RESERVATION SYSTEM</h1>
        <button class="button" onclick="navigateBack()">Back</button>
    </div>
        <form action="" method="POST">
            <fieldset>
                <legend>Enter Flight Details</legend>
                <label for="fid">Flight ID:</label>
                <input type="text" id="fid" name="fid" required><br>

                <label for="planename">Plane Name:</label>
                <select id="planename" name="planename" required>
                    <option value="">Select airline</option>
                    <option value="AirIndia">AirIndia</option>
                    <option value="Emirates">Emirates</option>
                    <option value="Indigo">Indigo</option>
                    <option value="SpiceJet">SpiceJet</option>
                    <option value="Kingfisher">Kingfisher</option>
                </select><br>

                <label for="from">Pickup (From):</label>
                <select id="from" name="from" required>
                    <option value="">Select pickup</option>
                    <option value="Bangalore">Bangalore</option>
                    <option value="Mumbai">Mumbai</option>
                    <option value="Delhi">Delhi</option>
                    <option value="Chennai">Chennai</option>
                </select><br>

                <label for="to">Destination (To):</label>
                <select id="to" name="to" required>
                    <option value="">Select destination</option>
                    <option value="Bangalore">Bangalore</option>
                    <option value="Mumbai">Mumbai</option>
                    <option value="Delhi">Delhi</option>
                    <option value="Chennai">Chennai</option>
                </select><br>

                <label for="deptime">Departure Time:</label>
                <input type="datetime-local" id="deptime" name="deptime" required><br>

                <label for="arrtime">Arrival Time:</label>
                <input type="datetime-local" id="arrtime" name="arrtime" required><br>

                <label for="fare">Fare:</label>
                <input type="number" id="fare" name="fare" required><br>

                <label for="depdate">Departure Date:</label>
                <input type="date" id="depdate" name="depdate" required><br>

                <input type="submit" value="Insert Flight Details" name="insert">
            </fieldset>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            VANTA.CLOUDS({
                el: "body",
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
<?php
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection details
    $servername = "localhost";  // Replace with your MySQL server name
    $username = "root";     // Replace with your MySQL username
    $password = "";     // Replace with your MySQL password
    $dbname = "airline";  // Replace with your MySQL database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname,3307);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind SQL statement
    $stmt = $conn->prepare("INSERT INTO aircraft (Flight_ID, Dep_Time, Arr_Time, Plane_Name, Src, Dstn, Fare, Dep_Date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    // Bind parameters
    $stmt->bind_param("ssssssds", $flight_id, $dep_time, $arr_time, $plane_name, $src, $dstn, $fare, $dep_date);

    // Set parameters from POST data
    $flight_id = $_POST['fid'];
    $dep_time = $_POST['deptime'];
    $arr_time = $_POST['arrtime'];
    $plane_name = $_POST['planename'];
    $src = $_POST['from'];
    $dstn = $_POST['to'];
    $fare = $_POST['fare'];
    $dep_date = $_POST['depdate'];

   

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
