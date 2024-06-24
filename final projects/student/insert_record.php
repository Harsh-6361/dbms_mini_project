<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usn = $_POST["usn"];
    $name = $_POST["name"];
    $sem = $_POST["sem"];
    $sgpa = $_POST["sgpa"];
    $department = $_POST["department"];
    $phone_no = $_POST["phone_no"];

    $sql = "INSERT INTO STUDENT (USN, NAME, SEM, SGPA, DEPARTMENT, PHONE_NO)
            VALUES ('$usn', '$name', '$sem', '$sgpa', '$department', '$phone_no')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Record inserted successfully');</script>";
    } else {
        echo "<script>alert('Error inserting record: ". $conn->error."');</script>";
    }
}
?>