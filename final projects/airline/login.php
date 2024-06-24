<?php
    // Start the session before any output
    session_start();

    // PHP code for handling login
    $con = mysqli_connect("localhost", "root", "", "airline", 3307);

    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit();
    }

    if(isset($_POST['submit'])) {
        $email = $_POST['email'];
        $pass = $_POST['pass'];

        // Prepare statement
        $sql = "SELECT * FROM users WHERE User_Name=? AND Pswd=?";
        $stmt = mysqli_prepare($con, $sql);

        // Bind parameters
        mysqli_stmt_bind_param($stmt, "ss", $email, $pass);
        mysqli_stmt_execute($stmt);

        // Get result
        $result = mysqli_stmt_get_result($stmt);

        // Check number of rows
        $numrows = mysqli_num_rows($result);

        if($numrows != 0) {
            // Fetch data
            while($row = mysqli_fetch_assoc($result)) {
                $dbename = $row['User_Name'];
                $dbpassword = $row['Pswd'];
            }

            // Verify credentials
            if($email == $dbename && $pass == $dbpassword) {
                $_SESSION['sess_user'] = $email;

                /* Redirect browser */
                header("Location: userdashboard.php");
                exit(); // Ensure that script stops execution after header redirection
            }
        } else {
            echo "Invalid username or password!";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <!-- Include Vanta.js and Three.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r121/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.clouds.min.js"></script>
    <style>
        body {
            margin: 0;
            overflow: hidden;
            font-family: Arial, sans-serif;
        }
        .vanta-background {
            position: fixed;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        .overlay {
            position: fixed;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.5); /* Semi-transparent white overlay */
            z-index: 0;
        }
        .loginBox {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            z-index: 1;
        }
        .loginBox h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .loginBox p {
            margin: 10px 0 0;
            font-weight: bold;
            color: #333;
        }
        .loginBox input[type="text"],
        .loginBox input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }
        .loginBox input[type="submit"] {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .loginBox input[type="submit"]:hover {
            background-color: #45a049;
        }
        .loginBox a {
            color: #007BFF;
            text-decoration: none;
            font-size: 14px;
        }
        .right {
            text-align: right;
            margin-top: 10px;
        }
        .button {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <!-- Vanta.js Clouds Background -->
    <div class="vanta-background"></div>
    <!-- Semi-transparent overlay -->
    <div class="overlay"></div>

    <div class="loginBox">
        <h2>Log In Here</h2>
        <form action="login.php" method="post">
            <p>User Name</p>
            <input type="text" name="email" placeholder="Enter User Name">
            <p>Password</p>
            <input type="password" name="pass" placeholder=".....">
            <input type="submit" name="submit" value="Sign In">
            <a href="signup.php">Signup</a><br /><br />
            <div class="right">
                <button class="button">
                    <a href="index.php" style="color:white">Back</a>
                </button>
            </div>
        </form>
    </div>

    <!-- Script for Vanta.js Clouds Background and Three.js Airplane -->
    <script>
        var setVanta = () => {
            if (window.VANTA) {
                window.VANTA.CLOUDS({
                    el: ".vanta-background",
                    mouseControls: true,
                    touchControls: true,
                    gyroControls: false,
                    minHeight: 200.00,
                    minWidth: 200.00,
                    skyColor: 0x589eb8,
                    cloudColor: 0xc0c0d9,
                    cloudShadowColor: 0x38749d,
                    sunColor: 0xff6100,
                    sunGlareColor: 0xff7200,
                    sunlightColor: 0xffffff,
                    speed: 0.70
                });
            } else {
                console.error('VANTA is not defined');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            setVanta();
        });
    </script>
</body>
</html>
