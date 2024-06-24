<?php
// Database connection
$con = mysqli_connect("localhost", "root", "", "airline", 3307);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

if (isset($_GET['P_ID'])) {
    $passengerId = $_GET['P_ID'];

    // Delete the passenger
    $query = "DELETE FROM passenger WHERE P_ID = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $passengerId);
    if ($stmt->execute()) {
        echo "Passenger cancelled successfully.";
    } else {
        echo "Error cancelling passenger: " . $con->error;
    }

    $stmt->close();
}

$con->close();
header("Location: Bookings.php"); // Redirect back to the Bookings page
exit();
?>
