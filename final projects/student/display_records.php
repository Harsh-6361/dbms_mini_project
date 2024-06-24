<nav style="background-color: #337ab7; padding: 10px; text-align: right;">
  <button style="background-color: #337ab7; color: #fff; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer;"><a href="my_profile.php" style="color: #fff; text-decoration: none;">My Profile</a></button>
  <button style="background-color: #337ab7; color: #fff; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer;"><a href="index.php" style="color: #fff; text-decoration: none;">Insert Record</a></button>
  <button style="background-color: #337ab7; color: #fff; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer;"><a href="sgpa.html" style="color: #fff; text-decoration: none;">SGPA Calculator</a></button>
  <button style="background-color: #337ab7; color: #fff; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer;"><a href="logout.php" style="color: #fff; text-decoration: none;">Logout</a></button>
  
</nav>
<br></br>

<style>
 .back-button {
    position: absolute;
    top: 60px; /* adjust this value to position the button below the nav buttons */
    left: 10px;
    background-color: #337ab7;
    color: #fff;
    border: none;
    border-radius: 50%; /* add this to make the button circular */
    padding: 10px 20px;
    cursor: pointer;
    width: 30px; /* set a fixed width to make the button circular */
    height: 30px; /* set a fixed height to make the button circular */
    text-align: center; /* center the text horizontally */
    line-height: 30px; /* center the text vertically */
  }
</style>

<a href="#" class="back-button" onclick="goBack()"><<</a>

<script>
  function goBack() {
    window.history.back();
  }
</script>
<?php

require_once 'config.php';

require_once 'update_record.php';

// Check if the delete button is clicked
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["delete"])) {
    $usn = $_GET["delete"];
    $sql = "DELETE FROM STUDENT WHERE USN = '$usn'";
    $conn->query($sql);
    header("Location: display_records.php");
    exit;
}

// Display the records
$sql = "SELECT * FROM STUDENT";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table style='margin: 0 auto; border-collapse: collapse;'>";
    echo "<tr style='background-color: #f0f0f0;'>";
    echo "<th style='border: 1px solid #ddd; padding: 10px;'>USN</th>";
    echo "<th style='border: 1px solid #ddd; padding: 10px;'>Name</th>";
    echo "<th style='border: 1px solid #ddd; padding: 10px;'>Sem</th>";
    echo "<th style='border: 1px solid #ddd; padding: 10px;'>SGPA</th>";
    echo "<th style='border: 1px solid #ddd; padding: 10px;'>Department</th>";
    echo "<th style='border: 1px solid #ddd; padding: 10px;'>Phone No</th>";
    echo "<th style='border: 1px solid #ddd; padding: 10px;'>Actions</th>";
    echo "</tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr style='background-color: #fff;'>";
        echo "<td style='border: 1px solid #ddd; padding: 10px;'>". $row["USN"]. "</td>";
        echo "<td style='border: 1px solid #ddd; padding: 10px;'>". $row["NAME"]. "</td>";
        echo "<td style='border: 1px solid #ddd; padding: 10px;'>". $row["SEM"]. "</td>";
        echo "<td style='border: 1px solid #ddd; padding: 10px;'>". $row["SGPA"]. "</td>";
        echo "<td style='border: 1px solid #ddd; padding: 10px;'>". $row["DEPARTMENT"]. "</td>";
        echo "<td style='border: 1px solid #ddd; padding: 10px;'>". $row["PHONE_NO"]. "</td>";
        echo "<td style='border: 1px solid #ddd; padding: 10px;'>";
        echo "<a href='?edit=". $row["USN"]. "' style='text-decoration: none; color: #337ab7;'>Edit</a>";
        echo " | ";
        echo "<a href='?delete=". $row["USN"]. "' style='text-decoration: none; color: #d9534f;'>Delete</a>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No records found";
}
require_once 'edit_record.php';
require_once 'sorting.php';
echo "<br></br>";
echo "<br></br>";


require_once 'footer.php';
?>