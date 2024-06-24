<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r121/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.clouds.min.js"></script>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            font-family: 'Yantramanav', sans-serif;
            color: #261A15;
        }
        .vanta-background {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
        }
        .registration-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
            box-sizing: border-box;
            overflow-y: auto; /* Enable scrolling on the page */
        }
        .registration-form {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        h1, h2 {
            color: rgb(44, 62, 80);
            font-family: 'Verdana', sans-serif;
            margin-bottom: 20px;
        }
        h1 {
            font-size: 1.5em;
        }
        h2 {
            font-size: 1.2em;
        }
        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .input-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }
        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .button {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            flex: 1;
            margin: 0 5px;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="vanta-background"></div>
    <div class="registration-container">
        <div class="registration-form">
            <h1>AIRLINE RESERVATION SYSTEM</h1>
            <h2>User Registration Form</h2>
            <form action="" method="POST">
                <div class="input-group">
                    <label for="emp">User name:</label>
                    <input type="text" id="emp" name="emp" required/>
                </div>
                <div class="input-group">
                    <label for="emailid">Email Id:</label>
                    <input type="email" id="emailid" name="emailid" required/>
                </div>
                <div class="input-group">
                    <label for="phone">Phone:</label>
                    <input type="text" id="phone" name="phone" required/>
                </div>
                <div class="input-group">
                    <label for="age">Age:</label>
                    <input type="number" id="age" name="age" required/>
                </div>
                <div class="input-group">
                    <label for="emppass">Password:</label>
                    <input type="password" id="emppass" name="emppass" required/>
                </div>
                <div class="input-group">
                    <label for="empconf">Confirm Password:</label>
                    <input type="password" id="empconf" name="empconf" required/>
                </div>
                <div class="button-group">
                    <input class="button" type="submit" value="Register" name="submit" />
                    <input class="button" type="reset" value="Reset" />
                </div>
                <div class="button-group">
                    <a class="button" href="login.php" style="background-color: #6c757d;">Back to Login</a>
                </div>
            </form>
        </div>
    </div>
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
    <?php
    if(isset($_POST["submit"])) {
        if(!empty($_POST['emp']) && !empty($_POST['emailid']) && !empty($_POST['phone']) && !empty($_POST['emppass']) && !empty($_POST['empconf']) && !empty($_POST['age'])) {
            $emp = $_POST['emp'];
            $emailid = $_POST['emailid'];
            $phone = $_POST['phone'];
            $age = $_POST['age'];
            $emppass = $_POST['emppass'];
            $empconf = $_POST['empconf'];

            if ($emppass != $empconf) {
                echo "<script>alert('Error... Passwords do not match');</script>";
                exit;
            }

            $con = mysqli_connect('localhost', 'root', '', 'airline',3307) or die(mysqli_connect_error());

            // Prevent SQL Injection
            $emp = mysqli_real_escape_string($con, $emp);
            $emailid = mysqli_real_escape_string($con, $emailid);
            $phone = mysqli_real_escape_string($con, $phone);
            $emppass = mysqli_real_escape_string($con, $emppass);
            $age = mysqli_real_escape_string($con, $age);

            // Check if username already exists
            $query = "SELECT * FROM users WHERE User_Name='$emp'";
            $result = mysqli_query($con, $query);
            $numrows = mysqli_num_rows($result);

            if ($numrows == 0) {
                $sql = "INSERT INTO users (User_Name, Pswd, Email, Phone, Age) VALUES ('$emp', '$emppass', '$emailid', '$phone', '$age')";
                if (mysqli_query($con, $sql)) {
                    echo "<script>alert('User Account Successfully Created. Please login.'); window.location = 'login.php';</script>";
                } else {
                    echo "<script>alert('Failure! Please try again later.');</script>";
                }
            } else {
                echo "<script>alert('That username already exists! Please try again with another.');</script>";
            }

            mysqli_close($con);
        } else {
            echo "<script>alert('All fields are required!');</script>";
        }
    }
    ?>
</body>
</html>
