<?php
require_once 'config.php';

// Check if the update button is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    $usn = $_POST["usn"];
    $name = $_POST["name"];
    $sem = $_POST["sem"];
    $sgpa = $_POST["sgpa"];
    $department = $_POST["department"];
    $phone_no = $_POST["phone_no"];

    // Update the record
    $sql = "UPDATE STUDENT SET NAME =?, SEM =?, SGPA =?, DEPARTMENT =?, PHONE_NO =? WHERE USN =?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sidsii", $name, $sem, $sgpa, $department, $phone_no, $usn);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Display a success message
        echo "<script type='text/javascript'>alert('Record updated successfully!'); window.location.href = 'display_records.php';</script>";
        echo "<div style='background-color: #dff0d8; padding: 10px; border: 1px solid #3e8e41; border-radius: 5px;'><strong>Success!</strong> Record updated successfully!</div>";
    } else {
        echo "<script type='text/javascript'>alert('ERROR WHILE UPDATING'); window.location.href = 'display_records.php';</script>";
        echo "<div style='background-color: #f2dede; padding: 10px; border: 1px solid #a94442; border-radius: 5px;'><strong>Error!</strong> Error updating record: ". $conn->error."</div>";
    }
}
?>