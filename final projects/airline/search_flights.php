<?php
session_start();

if (!isset($_SESSION["sess_user"])) {
    header("location:userlogin.php");
    exit();
}

if (isset($_POST["proceed"])) {
    if (!empty($_POST['from1']) && !empty($_POST['to1']) && !empty($_POST['depdate'])) {
        $from = $_POST['from1'];
        $to = $_POST['to1'];
        $depdate = $_POST['depdate'];

        $con = @mysqli_connect('localhost', 'root', '', 'airline', 3307) or die(mysqli_error($con));
        $user = $_SESSION["sess_user"];
        $today = strtotime('today');
        $date_timestamp = strtotime($depdate);

        if ($date_timestamp < $today) {
            $error = 'Enter a valid date!';
        } elseif ($from == $to) {
            $error = 'Pickup and destination cannot be the same.';
        } else {
            $_SESSION['sess_depdate'] = $depdate;
            $_SESSION['sess_from'] = $from;
            $_SESSION['sess_to'] = $to;

            $sql = "SELECT Flight_ID, Dep_Time, Arr_Time, Plane_Name, Src, Dstn, Fare, Dep_Date FROM aircraft WHERE Src = ? AND Dstn = ? AND Dep_Date = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param('sss', $from, $to, $depdate);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $flights = $result->fetch_all(MYSQLI_ASSOC);
            } else {
                $error = "No flights available for the selected route and date.";
            }
            $stmt->close();
        }
        mysqli_close($con);
    } else {
        $error = "All fields are required!";
    }
}

if (isset($_POST["sort_flights"])) {
    if (!empty($_POST['sort_by'])) {
        $sort_by = $_POST['sort_by'];
        $from = $_SESSION['sess_from'];
        $to = $_SESSION['sess_to'];
        $depdate = $_SESSION['sess_depdate'];

        $con = @mysqli_connect('localhost', 'root', '', 'airline', 3307) or die(mysqli_error($con));
        $sql = "SELECT Flight_ID, Dep_Time, Arr_Time, Plane_Name, Src, Dstn, Fare, Dep_Date FROM aircraft WHERE Src = ? AND Dstn = ? AND Dep_Date = ? ORDER BY $sort_by";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('sss', $from, $to, $depdate);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $flights = $result->fetch_all(MYSQLI_ASSOC);
        } else {
            $error = "No flights available for the selected route, date, and sorting option.";
        }
        $stmt->close();
        mysqli_close($con);
    } else {
        $error = "Please select a sorting option!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Search Results</title>
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
        h3 {
            text-align: center;
            color: #261A15;
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
        fieldset {
            background-color: gray;
            color: white;
            opacity: 0.7;
            border: none;
            margin: 30px auto;
            width: 20%;
            padding: 20px;
            border-radius: 10px;
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
            opacity: 0.8;
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
    </style>
</head>
<body>
    <div class="vanta-background"></div>
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
        <h1>Flight Search Results</h1>

        <?php if (isset($error)) : ?>
            <p style="color: red; text-align: center;"><?php echo $error; ?></p>
        <?php endif; ?>

        <?php if (isset($flights) && !empty($flights)) : ?>
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
                <?php foreach ($flights as $flight) : ?>
                    <div class="card">
                        <h3>Flight Details</h3>
                        <p><strong>Flight ID:</strong> <?php echo htmlspecialchars($flight['Flight_ID']); ?></p>
                        <p><strong>Departure Time:</strong> <?php echo htmlspecialchars($flight['Dep_Time']); ?></p>
                        <p><strong>Arrival Time:</strong> <?php echo htmlspecialchars($flight['Arr_Time']); ?></p>
                        <p><strong>Aircraft:</strong> <?php echo htmlspecialchars($flight['Plane_Name']); ?></p>
                        <p><strong>From:</strong> <?php echo htmlspecialchars($flight['Src']); ?></p>
                        <p><strong>To:</strong> <?php echo htmlspecialchars($flight['Dstn']); ?></p>
                        <p><strong>Fare:</strong> $<?php echo htmlspecialchars($flight['Fare']); ?></p>
                        <p><strong>Departure Date:</strong> <?php echo htmlspecialchars($flight['Dep_Date']); ?></p>
                        <form method="POST" action="book_flight.php">
                            <input type="hidden" name="flight_id" value="<?php echo htmlspecialchars($flight['Flight_ID']); ?>">
                            <input type="hidden" name="dep_date" value="<?php echo htmlspecialchars($flight['Dep_Date']); ?>">
                            <button type="submit">Book Now</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
