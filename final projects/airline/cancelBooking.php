<?php
// Database connection
$con = mysqli_connect("localhost", "root", "", "airline", 3307);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

if (isset($_GET['Booking_ID'])) {
    $bookingId = $_GET['Booking_ID'];

    // Delete the booking
    $query = "DELETE FROM bookings WHERE Booking_ID = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $bookingId);
    if ($stmt->execute()) {
        echo "Booking cancelled successfully.";
    } else {
        echo "Error cancelling booking: " . $con->error;
    }

    $stmt->close();
}

$con->close();
header("Location: Bookings.php"); // Redirect back to the Bookings page
exit();
?>
