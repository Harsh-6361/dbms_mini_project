<?php
session_start();

// Connect to database
$conn = mysqli_connect("localhost", "root", "", "feedback", 3307);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize error variables
$teacher_error = '';
$student_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['teacher_username']) && isset($_POST['teacher_password'])) {
        $username = $_POST['teacher_username'];
        $password = $_POST['teacher_password'];

        // Query to check if teacher exists
        $query_teacher = "SELECT * FROM teachers WHERE username = '$username' AND password = '$password'";
        $result_teacher = mysqli_query($conn, $query_teacher);

        if (mysqli_num_rows($result_teacher) > 0) {
            $teacher_data = mysqli_fetch_assoc($result_teacher);
            $_SESSION['user_id'] = $teacher_data['id'];
            $_SESSION['user_type'] = 'teacher';
            header('Location: view_feedback_forms.php');
            exit(); // Ensure no further code execution after redirection
        } else {
            $teacher_error = 'Invalid teacher username or password';
        }
    }

    if (isset($_POST['student_username']) && isset($_POST['student_password'])) {
        $username = $_POST['student_username'];
        $password = $_POST['student_password'];

        // Query to check if student exists
        $query_student = "SELECT * FROM students WHERE username = '$username' AND password = '$password'";
        $result_student = mysqli_query($conn, $query_student);

        if (mysqli_num_rows($result_student) > 0) {
            $student_data = mysqli_fetch_assoc($result_student);
            $_SESSION['user_id'] = $student_data['id'];
            $_SESSION['user_type'] = 'student';
            header('Location: student.php');
            exit(); // Ensure no further code execution after redirection
        } else {
            $student_error = 'Invalid student username or password';
        }
    }
}

// Close database connection
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
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
        .content {
            position: relative;
            z-index: 1;
        }
        .container {
            width: 800px;
            background-color: rgba(255, 255, 255, 0.7); /* Semi-transparent white */
            margin: 100px auto;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333; /* Adjust color for better blending */
        }
        .login-form {
            width: 45%;
            margin: 20px;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent white */
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            margin: 10px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #45a049;
        }
        .signup {
            text-align: center;
            margin-top: 20px;
        }
        a {
            color: #333;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .error {
            color: red;
            font-size: 12px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div id="vanta-bg"></div>
    <div class="content">
        <h2>Feedback Form Login</h2>
        <div class="container">
            <div class="login-form">
                <h3>Teacher Login</h3>
                <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                    <label for="teacher-username">Username:</label>
                    <input type="text" id="teacher-username" name="teacher_username" required>
                    <label for="teacher-password">Password:</label>
                    <input type="password" id="teacher-password" name="teacher_password" required>
                    <?php if ($teacher_error) { ?>
                        <p class="error"><?php echo $teacher_error; ?></p>
                    <?php } ?>
                    <button type="submit">Login as Teacher</button>
                </form>
                <div class="signup">
                    <p>Don't have a teacher account? <a href="teacher_signup.php">Sign up as Teacher</a></p>
                </div>
            </div>
            <div class="login-form">
                <h3>Student Login</h3>
                <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                    <label for="student-username">Username:</label>
                    <input type="text" id="student-username" name="student_username" required>
                    <label for="student-password">Password:</label>
                    <input type="password" id="student-password" name="student_password" required>
                    <?php if ($student_error) { ?>
                        <p class="error"><?php echo $student_error; ?></p>
                    <?php } ?>
                    <button type="submit">Login as Student</button>
                </form>
                <div class="signup">
                    <p>Don't have a student account? <a href="student_signup.php">Sign up as Student</a></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Include the required scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r121/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.birds.min.js"></script>
    <script>
        var setVanta = ()=>{
            if (window.VANTA) window.VANTA.BIRDS({
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
                birdSize: 1.00,
                wingSpan: 40.00,
                speedLimit: 6.00,
                separation: 100.00,
                backgroundAlpha: 0.70
            })
        }
        setVanta();
        window.edit_page.Event.subscribe("Page.beforeNewOneFadeIn", setVanta)
    </script>
</body>
</html>
