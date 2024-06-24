<?php
session_start();

require_once 'config.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

?>

<nav style="background-color: #337ab7; padding: 10px; text-align: right;">
  <button style="background-color: #337ab7; color: #fff; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer;"><a href="my_profile.php" style="color: #fff; text-decoration: none;">My Profile</a></button>
  <button style="background-color: #337ab7; color: #fff; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer;"><a href="display_records.php" style="color: #fff; text-decoration: none;">View Inserted Records</a></button> 
  <button style="background-color: #337ab7; color: #fff; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer;"><a href="sgpa.html" style="color: #fff; text-decoration: none;">SGPA Calculator</a></button>
  <button style="background-color: #337ab7; color: #fff; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer;"><a href="logout.php" style="color: #fff; text-decoration: none;">Logout</a></button> 
</nav>

<center>
<h1 style='color: #337ab7; font-weight: bold;'>
  <marquee behavior="scroll" direction="left">Student Records Management System</marquee>
</h1>
<hr>
<h2>ENTER THE DETAILS</h2>
<div style='background-color: #f9f9f9; padding: 20px; width: 500px; background-color:light_black; height:250px; border: 5px solid #ddd;'>
  <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
    <label for="usn" style='color: #666;'>USN:</label><!--pattern="[1-9][A-Z][A-Z][0-9][0-9][A-Z][A-Z][0-9][0-9][0-9]"-->
    <input type="text" id="usn" name="usn" title="Enter USN 1SP" style='width: 30%; height: 20px; border: 1px solid #ccc; padding: 5px; text-transform: uppercase;' required><br><br> 
    <label for="name" style='color: #666;'>Name:</label>
    <input type="text" id="name" name="name" style='width: 30%; height: 20px; border: 1px solid #ccc; padding: 5px;'><br><br>
    <label for="sem" style='color: #666;'>Sem:</label>
    <input type="number" id="sem" name="sem" min="1" max="8" style='width: 30%; height: 20px; border: 1px solid #ccc; padding: 5px;' required><br><br>
    <label for="sgpa" style='color: #666;'>SGPA:</label>
<input type="number" id="sgpa" name="sgpa" min="0.00" max="10.00" step="0.01" style='width: 30%; height: 20px; border: 1px solid #ccc; padding: 5px;'><br><br><label for="department" style='color: #666;'>Department:</label>
    <input type="text" id="department" name="department" style='width: 27%; height: 20px; border: 1px solid #ccc; padding: 5px;'><br><br>
    <label for="phone_no" style='color: #666;'>Phone No:</label>
    <input type="number" id="phone_no" name="phone_no" style='width: 30%; height: 20px; border: 1px solid #ccc; padding: 5px;'><br><br>
    <input type="submit" name="submit" value="Submit" style='background-color: #337ab7; color: #fff; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer;'>
  </form>
</div>

</center>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'insert_record.php';
}
?>
<?php
require_once 'footer.php';
?>