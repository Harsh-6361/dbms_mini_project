
<?php
session_start();

// Connect to database
$con = @mysqli_connect('localhost', 'root', '', 'airline', 3307) or die(mysqli_error($con));

// Check connection
if (!$conn) {
    die("Connection failed: ". mysqli_connect_error());
}

// Retrieve search criteria from previous page
$src = $_POST['src'];
$dstn = $_POST['dstn'];
$dep_date = $_POST['dep_date'];

// Retrieve flights from database based on search criteria
$query = "SELECT * FROM flights WHERE Src = '$src' AND Dstn = '$dstn' AND Dep_Date = '$dep_date'";
$result = mysqli_query($conn, $query);

// Check if there are any flights
if (mysqli_num_rows($result) > 0) {
    $flights = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $flights[] = $row;
    }
} else {
    $flights = array();
}

// Close connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Flights</title>
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
            height: 100vh%;
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
        fieldset {
            background-color: black;
            color: white;
            opacity: 0.8;
            border: none;
            margin: 30px auto;
            width: 40%;
            padding: 20px;
            border-radius: 10px;
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
       .cards-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
       .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            text-align: center;
            width: 300px;
        }
       .card h3 {
            margin-top: 0;
        }
       .card p {
            margin: 5px 0;
        }
       .card button {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<bodyclass="vanta-background">
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
        <h1><u>Available Flights</u></h1>
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION["sess_user"]);?></h2>
        <?php if (isset($flights) &&!empty($flights)) :?>
            <form method="POST" action="">
                <fieldset>
                    <h3>Sort Flights</h3>
                    <center>
                        <select name="sort_by" required>
                            <option value="">Select Sorting Option</option>
                            <option value="Dep_Time ASC">Departure Time (Earliest First)</option>
                            <option value="Dep_Time DESC">Departure Time (Latest First)</option>
                            <option value="Fare ASC">Price (Lowest First)</option>
                            <option value="Fare DESC">Price (Highest First)</option>
                        </select>
                        <br><br>
                        <input type="submit" value="Sort Flights" name="sort_flights" />
                    </center>
                </fieldset>
            </form>

            <div class="cards-container">
                <?php foreach ($flights as $flight) :?>
                    <div class="card">
                        <h3>Flight Details</h3>
                        <p><strong>Flight ID:</strong> <?php echo htmlspecialchars($flight['Flight_ID']);?></p>
                        <p><strong>Departure Time:</strong> <?php echo htmlspecialchars($flight['Dep_Time']);?></p>
                        <p><strong>Arrival Time:</strong><?php echo htmlspecialchars($flight['Arr_Time']);?></p>
                        <p><strong>Aircraft:</strong> <?php echo htmlspecialchars($flight['Plane_Name']);?></p>
                        <p><strong>From:</strong> <?php echo htmlspecialchars($flight['Src']);?></p>
                        <p><strong>To:</strong> <?php echo htmlspecialchars($flight['Dstn']);?></p>
                        <p><strong>Fare:</strong> $<?php echo htmlspecialchars($flight['Fare']);?></p>
                        <form method="POST" action="book_flight.php">
                            <input type="hidden" name="flight_id" value="<?php echo htmlspecialchars($flight['Flight_ID']);?>">
                            <input type="hidden" name="dep_date" value="<?php echo htmlspecialchars($flight['Dep_Date']);?>">
                            <button type="submit">Book Now</button>
                        </form>
                    </div>
                <?php endforeach;?>
            </div>
        <?php endif;?>
    </div>
</body>
</html>