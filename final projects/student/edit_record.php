<?php
require_once 'config.php';

// Display the edit form
if (isset($_GET["edit"])) {
    $usn = $_GET["edit"];
    $sql = "SELECT * FROM STUDENT WHERE USN = '$usn'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    echo "<div style='text-align: center;'>";
    echo "<form method='post' style='background-color: #f0f0f0; padding: 20px; border: 1px solid #ddd; border-radius: 10px; width: 50%; margin: 0 auto;'>";
    echo "<h2 style='color: #337ab7;'>Edit Student Details</h2>";
    echo "<label for='usn' style='color: #337ab7;'>USN:</label>";
    echo "<input type='hidden' name='usn' value='". $row["USN"]. "'>";
    echo "<br>";
    echo "<label for='name' style='color: #337ab7;'>Name:</label>";
    echo "<input type='text' name='name' value='". $row["NAME"]. "' style='width: 100%; height: 30px; border: 1px solid #ddd; border-radius: 5px; padding: 10px;'>";
    echo "<br>";
    echo "<label for='sem' style='color: #337ab7;'>Sem:</label>";
    echo "<input type='number' name='sem' value='". $row["SEM"]. "' style='width: 100%; height: 30px; border: 1px solid #ddd; border-radius: 5px; padding: 10px;'>";
    echo "<br>";
    echo "<label for='sgpa' style='color: #337ab7;'>SGPA:</label>";
    echo "<input type='number' name='sgpa' value='". $row["SGPA"]. "' step='any' style='width: 100%; height: 30px; border: 1px solid #ddd; border-radius: 5px; padding: 10px;'>";
    echo "<br>";
    echo "<label for='department' style='color: #337ab7;'>Department:</label>";
    echo "<input type='text' name='department' value='". $row["DEPARTMENT"]. "' style='width: 100%; height: 30px; border: 1px solid #ddd; border-radius: 5px; padding: 10px;'>";
    echo "<br>";
    echo "<label for='phone_no' style='color: #337ab7;'>Phone No:</label>";
    echo "<input type='number' name='phone_no' value='".$row["PHONE_NO"]. "' style='width: 100%; height: 30px; border: 1px solid #ddd; border-radius: 5px; padding: 10px;'>";
    echo "<br>";
    echo "<input type='submit' name='update' value='Update' style='background-color: #337ab7; color: #fff; border: none; border-radius: 5px; padding: 10px 20px; cursor: pointer;'>";
    echo "</form>";
    echo "</div>";
}
?>