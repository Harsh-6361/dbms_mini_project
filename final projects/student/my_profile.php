<?php
session_start(); // Start the session

// Connect to the database
include_once 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo "You are not logged in";
    exit;
}

// Retrieve the user's profile data
$username = $_SESSION['username'];
$sql = "SELECT * FROM teacher WHERE Username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
} else {
    echo "No user data found";
}

// Display the user's profile data
?>

<style>
    body {
        background-color: #f0f0f0; /* dull gray background */
    }
    table {
        margin: 0 auto; /* center the table */
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid #ccc; /* dull gray border */
        padding: 10px;
    }
</style>
<center>
<h1>Your Profile</h1>
</center>
<h2>Welcome, <?php echo $username; ?>!</h2>

<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
    <table >
       <!-- <tr>
            <th>Username</th>
            <td><?php echo $user_data['Username'];?></td>
        </tr>-->
        <tr>
            <th>First Name</th>
            <td><input type="text" name="first_name" value="<?php echo $user_data['FirstName'];?>"></td>
        </tr>
        <tr>
            <th>Last Name</th>
            <td><input type="text" name="last_name" value="<?php echo $user_data['LastName'];?>"></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><input type="email" name="email" value="<?php echo $user_data['Email'];?>"></td>
        </tr>
        <tr>
            <th>Phone</th>
            <td><input type="tel" name="phone" value="<?php echo $user_data['Phone'];?>"></td>
        </tr>
        <tr>
            <th>Department</th>
            <td><input type="text" name="department" value="<?php echo $user_data['Department'];?>"></td>
        </tr>
    </table>
  <center>  <input type="submit" name="submit" value="Update Profile"></center>
</form>

<?php
if (isset($_POST['submit'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $department = $_POST['department'];

    // Insert or update the user's profile data in the database
    $sql = "UPDATE teacher SET FirstName = '$first_name', LastName = '$last_name', Email = '$email', Phone = '$phone', Department = '$department' WHERE Username = '$username'";
    $result = $conn->query($sql);
    if ($result) {
        $message = "Profile updated successfully!";
        header("Location: ".$_SERVER['PHP_SELF']);
        
    } else {
        $message = "Error updating profile: ". $conn->error;
        header("Location: ".$_SERVER['PHP_SELF']);
        
    }
}


$conn->close();
?>
<style>
  .back-button {
    position: absolute;
    top: 10px; /* adjust this value to position the button at the top */
    left: 10px; /* adjust this value to position the button at the left */
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

<a href="#" class="back-button" onclick="goBack()">Back</a>

<script>
  function goBack() {
    window.history.back();
  }
</script>
<?php
require_once 'footer.php';
?>