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

// Initialize variables to avoid undefined variable warnings
$form = array();
$result_questions = null;

// Get the form_id from the URL
$form_id = isset($_GET['form_id']) ? intval($_GET['form_id']) : 0;

if ($form_id <= 0) {
    echo '<p>Invalid form ID.</p>';
    exit;
}

// Handle form update
if (isset($_POST['update_form'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $questions = isset($_POST['questions']) ? $_POST['questions'] : array();
    $rating_scales = isset($_POST['rating_scales']) ? $_POST['rating_scales'] : array();
    
    // Update feedback form details
    $query_update_form = "UPDATE feedback_forms SET title = '$title', description = '$description' WHERE id = '$form_id'";
    mysqli_query($conn, $query_update_form);
    
    // Delete existing questions
    $query_delete_questions = "DELETE FROM feedback_questions WHERE form_id = '$form_id'";
    mysqli_query($conn, $query_delete_questions);
    
    // Insert updated questions
    foreach ($questions as $index => $question) {
        $question_text = mysqli_real_escape_string($conn, $question);
        $rating_scale = mysqli_real_escape_string($conn, $rating_scales[$index]);
        $query_insert_question = "INSERT INTO feedback_questions (form_id, question, rating_scale) 
                                 VALUES ('$form_id', '$question_text', '$rating_scale')";
        mysqli_query($conn, $query_insert_question);
    }
    
    echo '<script>alert("Feedback form updated successfully!"); window.location.href="teacher.php";</script>';
}

// Fetch the feedback form details
$query_form = "SELECT * FROM feedback_forms WHERE id = '$form_id'";
$result_form = mysqli_query($conn, $query_form);

if (mysqli_num_rows($result_form) > 0) {
    $form = mysqli_fetch_assoc($result_form);
} else {
    echo '<p>Feedback form not found.</p>';
    exit;
}

// Fetch the questions for the feedback form
$query_questions = "SELECT * FROM feedback_questions WHERE form_id = '$form_id'";
$result_questions = mysqli_query($conn, $query_questions);

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Feedback Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0; /* Reset default margin */
            overflow: hidden; /* Hide scrollbars */
        }

        .container {
            position: relative;
            z-index: 1;
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
        }

        .header h2 {
            color: #333;
            margin-top: 0;
        }

        .header .buttons {
            display: flex;
        }

        .header .buttons button {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
        }

        .header .buttons button:hover {
            background-color: #3e8e41;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input[type="text"], textarea {
            width: calc(100% - 22px); /* Adjust width to accommodate scrollbar width */
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
        }

        button[type="submit"], button[type="button"], .delete-question {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        button[type="submit"]:hover, button[type="button"]:hover, .delete-question:hover {
            background-color: #3e8e41;
        }

        /* Vanta.js background */
        #vanta-bg {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: -1; /* Ensure it stays behind other content */
        }
    </style>
</head>
<body>
    <!-- Vanta.js background -->
    <div id="vanta-bg"></div>

    <!-- Main content container -->
    <div class="container">
        <div class="header">
            <h2>Edit Feedback Form</h2>
            <div class="buttons">
                <button onclick="window.location.href='teacher.php'">Back</button>
                <button onclick="window.location.href='logout.php'">Logout</button>
            </div>
        </div>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?form_id=' . $form_id); ?>" method="post">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($form['title']); ?>" required><br><br>
            <label for="description">Description:</label><br>
            <textarea id="description" name="description" rows="4"><?php echo htmlspecialchars($form['description']); ?></textarea><br><br>
            <label>Questions:</label><br>
            <div id="questions-container">
                <?php
                if ($result_questions) {
                    while ($question = mysqli_fetch_assoc($result_questions)) {
                        ?>
                        <div class="question">
                            <input type="text" name="questions[]" value="<?php echo htmlspecialchars($question['question']); ?>" required>
                            <select name="rating_scales[]">
                                <option value="1-5" <?php if ($question['rating_scale'] == '1-5') echo 'selected'; ?>>1-5</option>
                                <option value="Yes-No" <?php if ($question['rating_scale'] == 'Yes-No') echo 'selected'; ?>>Yes-No</option>
                            </select>
                            <button type="button" class="delete-question">Delete</button>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            <button type="button" id="add-question">Add Question</button><br><br>
            <button type="submit" name="update_form">Update Form</button>
        </form>
    </div>

    <!-- Include Vanta.js library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r121/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.birds.min.js"></script>
    <script>
        // Initialize Vanta.js background
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

        // Add question button functionality
        document.getElementById('add-question').addEventListener('click', function() {
            const newQuestion = document.createElement('div');
            newQuestion.className = 'question';
            newQuestion.innerHTML = `
                <input type="text" name="questions[]" placeholder="Enter new question" required>
                <select name="rating_scales[]">
                    <option value="1-5">1-5</option>
                    <option value="Yes-No">Yes-No</option>
                </select>
                <button type="button" class="delete-question">Delete</button>
            `;
            document.getElementById('questions-container').appendChild(newQuestion);
        });

        // Delete question button functionality (for dynamically added elements)
        document.getElementById('questions-container').addEventListener('click', function(event) {
            if (event.target.classList.contains('delete-question')) {
                event.target.parentNode.remove();
            }
        });
    </script>
