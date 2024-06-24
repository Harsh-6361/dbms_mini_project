<?php
// Connect to the database
include_once 'config.php';

// Check if the form has been submitted
if (isset($_POST['submit'])) {
  // Get the form values
  $username = $_POST['username'];
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];
  $first_name = $_POST['first_name'];
  $last_name = $_POST['last_name'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $department = $_POST['department'];

  // Check if the passwords match
  if ($password!= $confirm_password) {
    echo '<div class="alert alert-error">Passwords do not match</div>';
  } else {
    // Check if the username and email are already taken
    $sql = "SELECT * FROM teacher WHERE Username = '$username' OR Email = '$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      echo '<center><div class="alert alert-error">Username or email already taken</div></center>';
    } else {
      // Insert the values into the database
      $sql = "INSERT INTO teacher (Username,password, FirstName, LastName, Email, Phone, Department) VALUES ('$username', '$password', '$first_name', '$last_name', '$email', '$phone', '$department')";
      $result = $conn->query($sql);
      if ($result) {
        echo '<div class="alert">Thank you for signing up!</div>';
        header("Location: login.php");
      } else {
        echo "Error creating account: ". $conn->error;
      }
    }
  }
}
?>
<style>
.btn-login {
  float: right;
  margin-top: 10px;
  background-color: #337ab7;
  color: #fff;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.btn-login:hover {
  background-color: #23527c;
}</style>
<div class="header">
  <a href="login.php" class="btn-login">Login</a>
</div>

<style>


  body {
    background-color: #f0f0f0;
    font-family: Arial, sans-serif;
  }
  
.container {
    width: 50%;
    margin: 40px auto;
    background-color: #fff;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }
  
.header {
    background-color: #337ab7;
    color: #fff;
    
    text-align: center;
    border-radius: 10px 10px 0 0;
  }
  
.header h2 {
    margin: 0;
  }
  
.form-group {
    margin-bottom: 20px;
  }
  
.form-group label {
    display: block;
    margin-bottom: 10px;
    color: #337ab7;
  }
  
.form-group input[type="text"],.form-group input[type="password"],.form-group input[type="email"],.form-group input[type="tel"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
  }
  
.form-group input[type="submit"] {
    background-color: #337ab7;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }
  
.form-group input[type="submit"]:hover {
    background-color: #23527c;
  }
  
.alert {
    padding: 10px;
    background-color: #f0f0f0;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin-bottom: 20px;
  }
  
.alert-error {
    background-color: #ff9999;
    border-color: #ff0000;
  }
</style>

<div class="container">
  <div class="header">
    <h2>Sign up</h2>
  </div>
  <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
    <div class="form-group">
      <label for="username">Username:</label>
      <input type="text" id="username" name="username" required>
    </div>
    <div class="form-group">
      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required>
    </div>
    <div class="form-group">
      <label for="confirm_password">Confirm Password:</label>
      <input type="password" id="confirm_password" name="confirm_password" required>
    </div>
    <div class="form-group">
      <label for="first_name">First Name:</label>
      <input type="text" id="first_name" name="first_name" required>
    </div>
    <div class="form-group">
      <label for="last_name">Last Name:</label>
      <input type="text" id="last_name" name="last_name" required>
    </div>
    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required>
    </div>
    <div class="form-group">
      <label for="phone">Phone:</label>
      <input type="tel" id="phone" name="phone" required>
    </div>
    <div class="form-group">
      <label for="department">Department:</label>
      <input type="text" id="department" name="department" required>
    </div>
    <div class="form-group">
      <input type="submit" name="submit" value="Sign up">
    </div>
  </form>
</div>
<?php
require_once 'footer.php';
?>