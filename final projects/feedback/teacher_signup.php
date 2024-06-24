<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Registration</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            font-family: Arial, sans-serif;
        }

        #vanta-bg {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: -1; /* Send to back */
        }

        .container {
            background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent white */
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            position: relative;
            z-index: 1; /* Bring to front */
            margin: auto;
            top: 50%;
            transform: translateY(-50%);
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"],
        input[type="tel"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div id="vanta-bg"></div>
    <div class="container">
        <h2>Teacher Registration</h2>
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br>
            <label for="department">Department:</label>
            <input type="text" id="department" name="department" required><br>
            <label for="phone">Phone:</label>
            <input type="tel" id="phone" name="phone" required><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required><br>
            <input type="submit" value="Register">
        </form>
    </div>

    <?php
    session_start();

    // Connect to database
    $conn = mysqli_connect("localhost", "root", "", "feedback", 3307);

    // Check connection
    if (!$conn) {
        die("Connection failed: ". mysqli_connect_error());
    }

    if (isset($_POST['name']) && isset($_POST['username']) && isset($_POST['department']) && isset($_POST['phone']) && isset($_POST['password']) && isset($_POST['confirm_password'])) {
        $name = $_POST['name'];
        $username = $_POST['username'];
        $department = $_POST['department'];
        $phone = $_POST['phone'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Validate password and confirm password
        if ($password !== $confirm_password) {
            echo "Passwords do not match.";
            exit;
        }

        // Query to check if username already exists
        $query = "SELECT * FROM teachers WHERE username = '$username'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            echo "Username already exists.";
            exit;
        }

        // Query to insert new teacher
        $query = "INSERT INTO teachers (name, username, department, phone, password) VALUES ('$name', '$username', '$department', '$phone', '$password')";
        mysqli_query($conn, $query);

        echo "Registration successful!";
        header("Location: login.php");
        exit;
    }
    ?>

    <!-- Include the required scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r121/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.birds.min.js"></script>
    <script>
        VANTA.BIRDS({
            el: "#vanta-bg",
            mouseControls: true,
            touchControls: true,
            gyroControls: false,
            minHeight: 200.00,
            minWidth: 200.00,
            scale: 1.00,
            scaleMobile: 1.00,
            backgroundColor: 0xe0c4c4,
            color1: 0xff00c3,
            color2: 0xc5ff,
            colorMode: "lerpGradient",
            birdSize: 1.0,
            wingSpan: 40.00,
            speedLimit: 6.00,
            separation: 100.00,
            backgroundAlpha: 0.70
        });
    </script>
</body>
</html>
