<?php
session_start();

// Connect to database
$conn = mysqli_connect("localhost", "root", "", "feedback", 3307);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['name']) && isset($_POST['username']) && isset($_POST['department']) && isset($_POST['semester']) && isset($_POST['phone']) && isset($_POST['password'])) {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $department = $_POST['department'];
    $semester = $_POST['semester'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    // Query to insert new user
    $query = "INSERT INTO students (name, username, department, semester, phone, password) VALUES ('$name', '$username', '$department', '$semester', '$phone', '$password')";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Registration successful!');</script>";
        header('Location: login.php');
        exit;
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            background-color: #f4f4f4;
        }

        .vanta-container {
            position: fixed;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .container {
            width: 90%;
            max-width: 400px;
            background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent white */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            margin: auto;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        label {
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        input[type="number"],
        input[type="tel"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 14px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 16px;
            margin: 15px 0;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="vanta-container" id="vanta-bg"></div>
    <div class="container">
        <h2>Register</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br>
            <label for="department">Department:</label>
            <input type="text" id="department" name="department" required><br>
            <label for="semester">Semester:</label>
            <input type="number" id="semester" name="semester" required><br>
            <label for="phone">Phone:</label>
            <input type="tel" id="phone" name="phone" required><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>
            <input type="submit" value="Register">
        </form>
    </div>

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
