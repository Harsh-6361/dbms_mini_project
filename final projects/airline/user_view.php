<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["sess_user"])) {
    header("location: userlogin.php");
    exit();
}

// Database connection
$con = @mysqli_connect('localhost', 'root', '', 'airline', 3307) or die(mysqli_connect_error());

// Retrieve logged-in user details
$user = $_SESSION["sess_user"];
$sql_user = "SELECT * FROM users WHERE User_Name = ?";
$stmt_user = $con->prepare($sql_user);
$stmt_user->bind_param('s', $user);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows > 0) {
    $logged_in_user = $result_user->fetch_assoc();
} else {
    die("User details not found.");
}
$stmt_user->close();

// Retrieve bookings made by the logged-in user including passenger details
$sql_bookings = "SELECT b.Booking_ID, b.Flight_ID, b.Dep_Time, b.Name AS Booking_Name, b.Email AS Booking_Email, b.Phone AS Booking_Phone, b.Age AS Booking_Age, b.Gender AS Booking_Gender,
                        a.Fare, 
                        p.P_ID, p.Name AS Passenger_Name, p.Age AS Passenger_Age
                 FROM bookings b
                 LEFT JOIN passenger p ON b.Flight_ID = p.Flight_ID AND b.Dep_Time = p.Dep_Time
                 LEFT JOIN aircraft a ON b.Flight_ID = a.Flight_ID AND b.Dep_Time = a.Dep_Time
                 WHERE b.User_ID = ?";
$stmt_bookings = $con->prepare($sql_bookings);
$stmt_bookings->bind_param('i', $logged_in_user['User_ID']);
$stmt_bookings->execute();
$result_bookings = $stmt_bookings->get_result();
$stmt_bookings->close();

// Function to calculate remaining time until departure
function calculateRemainingTime($depTime) {
    $currentTime = time();
    $depTimestamp = strtotime($depTime);
    $remainingSeconds = $depTimestamp - $currentTime;
    if ($remainingSeconds < 0) {
        return [ "time" => "Completed", "completed" => true ];
    } else {
        $hours = floor($remainingSeconds / 3600);
        $minutes = floor(($remainingSeconds % 3600) / 60);
        return [ "time" => sprintf("%02d:%02d:00", $hours, $minutes), "completed" => false ];
    }
}

// Handle cancellation of bookings
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["cancel_booking"])) {
    $booking_id = $_POST["booking_id"];
    $sql_delete = "DELETE FROM bookings WHERE Booking_ID = ?";
    $stmt_delete = $con->prepare($sql_delete);
    $stmt_delete->bind_param('i', $booking_id);
    if ($stmt_delete->execute()) {
        // Booking successfully deleted
        // Redirect to refresh the page and avoid resubmission issues
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        // Error handling if deletion fails
        echo "Error deleting booking.";
    }
    $stmt_delete->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Bookings</title>
    <style>
        /* Ensure the body covers the entire viewport */
        body {
            margin: 0;
            overflow: hidden;
        }
        .page-container {
            height: 100vh; /* Full viewport height */
            overflow-y: auto; /* Allow scrolling */
            position: relative; /* Ensure absolute positioning inside works */
        }
        .cards-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
            padding: 20px; /* Added padding for better spacing */
        }
        .card {
            background-color: #f0f0f0;
            padding: 60px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 300px;
            position: relative;
        }
        .card h3 {
            margin-top: 0;
            text-align: center;
        }
        .card p {
            margin: 5px 0;
        }
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 10px;
            text-align: center;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            z-index: 1; /* Ensure button is above the background */
        }
        .cancel-button {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 10px;
            text-align: center;
            background-color: #ff6347; /* Red color for cancel button */
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            z-index: 1; /* Ensure button is above the background */
        }
        .vanta-background {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1; /* Place background behind other content */
        }
        .completed {
            background-color: #dff0d8; /* Light green for completed bookings */
        }
        .countdown {
            font-size: 16px;
            text-align: center;
            margin-top: 10px;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r121/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.clouds.min.js"></script>
</head>
<body>
    <div class="vanta-background"></div>
    <script>
        var setVanta = () => {
            if (window.VANTA) {
                window.VANTA.CLOUDS({
                    el: ".vanta-background",
                    mouseControls: true,
                    touchControls: true,
                    gyroControls: false,
                    minHeight: window.innerHeight,
                    minWidth: window.innerWidth,
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

        // Wait for DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            setVanta();
            if (window.edit_page && window.edit_page.Event) {
                window.edit_page.Event.subscribe("Page.beforeNewOneFadeIn", setVanta);
            } else {
                console.error('window.edit_page.Event is not defined');
            }
        });
    </script>

    <div class="page-container">
        <!-- Back Button -->
        <a href="userdashboard.php" class="back-button">Back to Dashboard</a>

        <h1 style="text-align: center; color: white;">Your Bookings</h1>

        <div class="cards-container">
            <?php while ($row = $result_bookings->fetch_assoc()) : ?>
                <?php
                    $timeData = calculateRemainingTime($row['Dep_Time']);
                    $remainingTime = $timeData['time'];
                    $isCompleted = $timeData['completed'];
                ?>
                <div class="card <?php echo $isCompleted ? 'completed' : ''; ?>">
                    <h3>Booking ID: <?php echo htmlspecialchars($row['Booking_ID']); ?></h3>
                    <p><strong>Flight ID:</strong> <?php echo htmlspecialchars($row['Flight_ID']); ?></p>
                    <p><strong>Departure Time:</strong> <?php echo htmlspecialchars($row['Dep_Time']); ?></p>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($row['Booking_Name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($row['Booking_Email']); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($row['Booking_Phone']); ?></p>
                    <p><strong>Age:</strong> <?php echo htmlspecialchars($row['Booking_Age']); ?></p>
                    <p><strong>Gender:</strong> <?php echo htmlspecialchars($row['Booking_Gender']); ?></p>
                    <p><strong>Price:</strong> $<?php echo htmlspecialchars($row['Fare']); ?></p>
                    <?php if (!$isCompleted) : ?>
                        <div class="countdown">
                            <strong>Countdown:</strong> <?php echo $remainingTime; ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($row['P_ID'])) : ?>
                        <hr>
                        <p><strong>Passenger Name:</strong> <?php echo htmlspecialchars($row['Passenger_Name']); ?></p>
                        <p><strong>Passenger Age:</strong> <?php echo htmlspecialchars($row['Passenger_Age']); ?></p>
                    <?php endif; ?>
                    <!-- Cancel Button -->
                    <?php if (!$isCompleted) : ?>
                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="cancel-form">
                            <input type="hidden" name="booking_id" value="<?php echo $row['Booking_ID']; ?>">
                            <button type="submit" name="cancel_booking" class="cancel-button">Cancel Booking</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

</body>
</html>


<?php
// Close database connection
mysqli_close($con);
?>
