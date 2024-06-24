
<nav style="background-color: #337ab7; padding: 10px; text-align: right;">
 <button style="background-color: #337ab7; color: #fff; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer;"><a href="sgpa.html" style="color: #fff; text-decoration: none;">SGPA Calculator</a></button>
 <button style="background-color: #337ab7; color: #fff; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer;"><a href="sign_up.php" style="color: #fff; text-decoration: none;">Sign Up</a></button>
 </nav>
<?php
// login.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";  
$conn = new mysqli($servername, $username, $password, $dbname, 3307);
if($conn->connect_error){
    die("Connection failed".$conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM teacher WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Credentials are valid, start the session and redirect to index.php
        session_start();
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit;
    } else {
        // Display an alert box with an error message
        echo "<script>alert('Invalid username or password');</script>";
    }
}

$conn->close();
?>

<!-- Login form -->
<form id="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <h1>Login</h1>
    <h3>Please enter your username and password.</h3>
    <hr>

    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <input type="submit" value="Login">
   
</form>
<?php
require_once 'footer.php';
?>
<style>
    form {
        border: 1px solid #ccc;
        padding: 20px;
        width: 30%;
        margin: 40px auto;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        margin-bottom: 10px;
    }

    input[type="text"], input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
    }

    input[type="submit"] {
        background-color: #4CAF50;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #3e8e41;
    }
</style>
