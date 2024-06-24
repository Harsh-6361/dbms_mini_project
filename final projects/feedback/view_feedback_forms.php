<?php
session_start();

// Check if the user is logged in and has a valid teacher_id in the session
if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "feedback", 3307);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the list of feedback forms created by the teacher
$teacher_id = $_SESSION['user_id'];
$query_get_forms = "SELECT * FROM feedback_forms WHERE teacher_id = '$teacher_id'";
$result = mysqli_query($conn, $query_get_forms);

// Handle form deletion
if (isset($_POST['delete_form'])) {
    $form_id_to_delete = intval($_POST['form_id']);
    $query_delete_form = "DELETE FROM feedback_forms WHERE id = '$form_id_to_delete'";
    mysqli_query($conn, $query_delete_form);

    // Optionally, delete associated questions and responses
    $query_delete_questions = "DELETE FROM feedback_questions WHERE form_id = '$form_id_to_delete'";
    mysqli_query($conn, $query_delete_questions);

    $query_delete_responses = "DROP TABLE IF EXISTS responses_$form_id_to_delete";
    mysqli_query($conn, $query_delete_responses);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link your CSS file -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0; /* Reset default margin */
            overflow: hidden; /* Hide scrollbars */
            position: relative; /* Ensure correct stacking order */
        }

        .vanta-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1; /* Place behind other content */
        }

        .content-wrapper {
            position: relative; /* Ensure content is on top of background */
            z-index: 1; /* Ensure content is above the background */
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            color: #fff; /* Ensure header is readable on background */
        }

        .header a {
            background-color: #007bff;
            color: #fff;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
        }

        .header a:hover {
            background-color: #0056b3;
        }

        .card-container {
            display: flex;
            
            flex-wrap: wrap;
            gap: 20px;
        }

        .card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.4);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 250px;
            text-align: center;
            color: #333; /* Ensure text is readable on card */
        }

        .card h3 {
            margin: 0;
            margin-bottom: 10px;
            color: #007bff;
        }

        .card p {
            margin: 0;
            margin-bottom: 15px;
            color: #666;
        }

        .card a, .card form {
            display: inline-block;
            margin-top: 10px;
        }

        .card a {
            background-color: #007bff;
            color: #fff;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
        }

        .card a:hover {
            background-color: #0056b3;
        }

        .card form button {
            background-color: #ff0000;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .card form button:hover {
            background-color: #cc0000;
        }
    </style>
</head>
<body>
    <!-- Vanta Birds background container -->
    <div class="vanta-container" id="vanta-bg"></div>

    <!-- Content wrapper -->
    <div class="content-wrapper">
        <div class="header">
        <h2 style="color: #000000">Teacher Dashboard</h2>

            <div>
                <a href="teacher.php">Create New Form</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
        <div class="card-container">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($form = mysqli_fetch_assoc($result)) {
                    echo '<div class="card">';
                    echo '<h3>' . htmlspecialchars($form['title']) . '</h3>';
                    echo '<p>' . htmlspecialchars($form['description']) . '</p>';
                    echo '<a href="view_feedback_form.php?form_id=' . $form['id'] . '">View Form</a>';
                    echo '<a href="edit_feedback_form.php?form_id=' . $form['id'] . '">Edit Form</a>';
                    echo '<a href="view_form_responses.php?form_id=' . $form['id'] . '">View Responses</a>';
                    echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
                    echo '<input type="hidden" name="form_id" value="' . $form['id'] . '">';
                    echo '<button type="submit" name="delete_form" onclick="return confirm(\'Are you sure you want to delete this form?\')">Delete Form</button>';
                    echo '</form>';
                    echo '</div>';
                }
            } else {
                echo '<p>No feedback forms created yet.</p>';
            }
            mysqli_close($conn);
            ?>
        </div>
    </div>

    <!-- Include the required scripts for Vanta.js and Vanta Birds -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r121/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.birds.min.js"></script>
    <script>
        // Initialize Vanta Birds background
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
