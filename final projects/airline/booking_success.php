<?php
session_start();

if (!isset($_SESSION["sess_user"])) {
    header("location:userlogin.php");
    exit();
}

if (!isset($_GET['booking_id'])) {
    header("location:search_flights.php");
    exit();
}

$booking_id = $_GET['booking_id'];

// Fetch booking details from the database
$con = @mysqli_connect('localhost', 'root', '', 'airline', 3307) or die(mysqli_connect_error());
$sql = "SELECT b.Booking_ID, b.Flight_ID, b.Dep_Date, b.Name, b.Email, b.Phone, b.Age, b.Gender, a.Dep_Time, a.Arr_Time, a.Plane_Name, a.Src, a.Dstn, a.Fare FROM bookings b JOIN aircraft a ON b.Flight_ID = a.Flight_ID WHERE b.Booking_ID = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param('i', $booking_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $booking = $result->fetch_assoc();
} else {
    $error = "Booking not found.";
}

$stmt->close();
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Successful</title>
    <style>
        body {
            font-family: 'Yantramanav', sans-serif;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            width: 50%;
            max-width: 600px;
        }
        h1 {
            color: green;
        }
        p {
            font-size: 16px;
            line-height: 1.5;
        }
        .ticket-details {
            margin-top: 20px;
            text-align: left;
        }
        .ticket-details p {
            margin: 5px 0;
        }
        .back-button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .back-button a {
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Booking Successful!</h1>
        <?php if (isset($error)) : ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php else : ?>
            <p>Thank you for booking with us, <?php echo htmlspecialchars($booking['Name']); ?>.</p>
            <div class="ticket-details">
                <h3>Flight Ticket</h3>
                <p><strong>Booking ID:</strong> <?php echo htmlspecialchars($booking['Booking_ID']); ?></p>
                <p><strong>Flight ID:</strong> <?php echo htmlspecialchars($booking['Flight_ID']); ?></p>
                <p><strong>Departure Date:</strong> <?php echo htmlspecialchars($booking['Dep_Date']); ?></p>
                <p><strong>Departure Time:</strong> <?php echo htmlspecialchars($booking['Dep_Time']); ?></p>
                <p><strong>Arrival Time:</strong> <?php echo htmlspecialchars($booking['Arr_Time']); ?></p>
                <p><strong>Aircraft:</strong> <?php echo htmlspecialchars($booking['Plane_Name']); ?></p>
                <p><strong>From:</strong> <?php echo htmlspecialchars($booking['Src']); ?></p>
                <p><strong>To:</strong> <?php echo htmlspecialchars($booking['Dstn']); ?></p>
                <p><strong>Fare:</strong> $<?php echo htmlspecialchars($booking['Fare']); ?></p>
                <p><strong>Passenger Name:</strong> <?php echo htmlspecialchars($booking['Name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($booking['Email']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($booking['Phone']); ?></p>
                <p><strong>Age:</strong> <?php echo htmlspecialchars($booking['Age']); ?></p>
                <p><strong>Gender:</strong> <?php echo htmlspecialchars($booking['Gender']); ?></p>
            </div>
        <?php endif; ?>
        <button class="back-button"><a href="search_flights.php">Back to Search</a></button>
    </div>
</body>
</html>
