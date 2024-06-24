<?php
session_start();

if (!isset($_SESSION["sess_user"])) {
    header("location:userlogin.php");
    exit();
}

// Database connection
$con = @mysqli_connect('localhost', 'root', '', 'airline', 3307) or die(mysqli_error($con));

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

// Initialize variables
$flight_details = null;
$dep_time = null;

// Retrieve flight details to be booked
if (isset($_POST["flight_id"]) && isset($_POST["dep_date"])) {
    $flight_id = $_POST['flight_id'];
    $dep_date = $_POST['dep_date'];

    $sql_flight = "SELECT * FROM aircraft WHERE Flight_ID = ? AND Dep_Date = ?";
    $stmt_flight = $con->prepare($sql_flight);
    $stmt_flight->bind_param('ss', $flight_id, $dep_date);
    $stmt_flight->execute();
    $result_flight = $stmt_flight->get_result();

    if ($result_flight->num_rows > 0) {
        $flight_details = $result_flight->fetch_assoc();
        $dep_time = $flight_details['Dep_Time']; // Retrieve dep_time from database
    } else {
        die("Flight details not found.");
    }
    $stmt_flight->close();
}

// Handle form submission for booking confirmation
if (isset($_POST["confirm_booking"])) {
    // Retrieve form data
    $flight_id = $_POST['flight_id'];
    $name = $logged_in_user['User_Name'];
    $email = $logged_in_user['Email'];
    $phone = $logged_in_user['Phone'];
    $age = $logged_in_user['Age'];
    $gender = $logged_in_user['Gender'];

    // Insert booking details into `bookings` table
    $sql_booking = "INSERT INTO bookings (User_ID, Flight_ID, Dep_Time, Name, Email, Phone, Age, Gender) VALUES (?,?,?,?,?,?,?,?)";
    $stmt_booking = $con->prepare($sql_booking);
    $stmt_booking->bind_param('isssssis', $logged_in_user['User_ID'], $flight_id, $dep_time, $name, $email, $phone, $age, $gender);
    $stmt_booking->execute();
    $booking_id = $stmt_booking->insert_id; // Get the auto-generated Booking_ID
    $stmt_booking->close();

    // Check if passenger details are entered
    if (!empty($_POST['passenger_name']) && !empty($_POST['passenger_age'])) {
        // Retrieve passenger details
        $passenger_name = $_POST['passenger_name'];
        $passenger_age = $_POST['passenger_age'];

        // Insert passenger details into `passenger` table
        $sql_passenger = "INSERT INTO passenger (Name, Age, Flight_ID, Dep_Time, User_ID) VALUES (?,?,?,?,?)";
        $stmt_passenger = $con->prepare($sql_passenger);
        $stmt_passenger->bind_param('sissi', $passenger_name, $passenger_age, $flight_id, $dep_time, $logged_in_user['User_ID']);
        $stmt_passenger->execute();
        $stmt_passenger->close();
    }

    // Redirect or display success message after booking confirmation
    // Example redirect (modify as per your application flow)
    header("Location: user_view.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
            background-color: #f0f0f0; /* Light gray background */
        }
        .container {
            max-width: 800px;
            margin: auto;
            background-color: #fff; /* White background */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1); /* Soft shadow */
        }
        h1 {
            color: #333; /* Dark gray for headings */
        }
        h2 {
            color: #007bff; /* Blue for section headings */
            border-bottom: 1px solid #007bff; /* Blue underline */
            padding-bottom: 5px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555; /* Dark gray for labels */
        }
        input[type="text"], input[type="submit"] {
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc; /* Light gray border */
            border-radius: 4px;
            width: 100%;
            box-sizing: border-box;
            font-size: 16px;
        }
        input[type="submit"] {
            background-color: #28a745; /* Green submit button */
            color: #fff; /* White text */
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #218838; /* Darker green on hover */
        }
        .btn-back {
            display: inline-block;
            background-color: #6c757d; /* Gray back button */
            color: #fff; /* White text */
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .btn-back:hover {
            background-color: #5a6268; /* Darker gray on hover */
        }
        hr {
            border: none;
            border-top: 1px solid #ccc; /* Light gray horizontal rule */
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Booking Confirmation</h1>

        <!-- Display flight details to be booked -->
        <h2>Flight Details</h2>
        <?php if ($flight_details) : ?>
            <p><strong>Flight ID:</strong> <?php echo htmlspecialchars($flight_details['Flight_ID']); ?></p>
            <p><strong>Departure Time:</strong> <?php echo htmlspecialchars($flight_details['Dep_Time']); ?></p>
            <p><strong>Arrival Time:</strong> <?php echo htmlspecialchars($flight_details['Arr_Time']); ?></p>
            <p><strong>Aircraft:</strong> <?php echo htmlspecialchars($flight_details['Plane_Name']); ?></p>
            <p><strong>From:</strong> <?php echo htmlspecialchars($flight_details['Src']); ?></p>
            <p><strong>To:</strong> <?php echo htmlspecialchars($flight_details['Dstn']); ?></p>
            <p><strong>Fare:</strong> $<?php echo htmlspecialchars($flight_details['Fare']); ?></p>
            <p><strong>Departure Date:</strong> <?php echo htmlspecialchars($flight_details['Dep_Date']); ?></p>
        <?php else : ?>
            <p>No flight details found.</p>
        <?php endif; ?>
        <hr>

        <!-- Booking confirmation form -->
        <form method="POST" action="">
            <!-- Hidden inputs for flight details -->
            <input type="hidden" name="flight_id" value="<?php echo isset($_POST['flight_id']) ? htmlspecialchars($_POST['flight_id']) : ''; ?>">
            <input type="hidden" name="dep_date" value="<?php echo isset($_POST['dep_date']) ? htmlspecialchars($_POST['dep_date']) : ''; ?>">
            <input type="hidden" name="dep_time" value="<?php echo isset($dep_time) ? htmlspecialchars($dep_time) : ''; ?>">

            <!-- Display logged-in user details (for confirmation only) -->
            <h2>User Details (for confirmation)</h2>
            <p><strong>User Name:</strong> <?php echo htmlspecialchars($logged_in_user['User_Name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($logged_in_user['Email']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($logged_in_user['Phone']); ?></p>
            <p><strong>Age:</strong> <?php echo htmlspecialchars($logged_in_user['Age']); ?></p>
            <hr>

            <!-- Option to add passenger details -->
            <h2>Add Passenger Details (optional)</h2>
            <label for="passenger_name">Passenger Name:</label>
            <input type="text" id="passenger_name" name="passenger_name">
            <br><br>
            <label for="passenger_age">Passenger Age:</label>
            <input type="text" id="passenger_age" name="passenger_age">
            <br><br>

            <!-- Submit button -->
            <input type="submit" value="Confirm Booking" name="confirm_booking" style="background-color: #007bff; color: #fff; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px;">

        </form>

        <!-- Back button -->
        <a href="userdashboard.php" class="btn-back">Back</a>

    </div>
</body>
</html>


<?php
mysqli_close($con);
?>
