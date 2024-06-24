<?php
session_start();

// Database connection
$conn = mysqli_connect("localhost", "root", "", "feedback", 3307);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch available feedback forms
$query_fetch_forms = "SELECT * FROM feedback_forms";
$result_forms = mysqli_query($conn, $query_fetch_forms);

// Handle form selection
if (isset($_POST['select_form'])) {
    $_SESSION['selected_form_id'] = $_POST['form_id'];
}

// Handle form submission to submit feedback responses
if (isset($_POST['submit_feedback'])) {
    $student_id = $_SESSION['user_id']; // Assuming student_id is stored in session
    $form_id = mysqli_real_escape_string($conn, $_POST['form_id']);
    
    // Check if the student has already completed this form
    $query_check_completed = "SELECT * FROM feedback_forms_completed WHERE form_id = '$form_id' AND student_id = '$student_id'";
    $result_completed = mysqli_query($conn, $query_check_completed);

    if (mysqli_num_rows($result_completed) === 0) {
        // Check if the student exists in the students table
        $query_check_student = "SELECT * FROM students WHERE id = '$student_id'";
        $result_student = mysqli_query($conn, $query_check_student);

        if (mysqli_num_rows($result_student) > 0) {
            // Create the response table if it doesn't exist
            $table_name = 'responses_' . $form_id;
            $create_table_query = "CREATE TABLE IF NOT EXISTS $table_name (
                id INT AUTO_INCREMENT PRIMARY KEY,
                student_id INT,
                question_id INT,
                response TEXT,
                comment TEXT,
                submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            
            mysqli_query($conn, $create_table_query);

            // Insert responses for each question
            if (isset($_POST['question']) && !empty($_POST['question'])) {
                foreach ($_POST['question'] as $question_id => $response) {
                    $response = mysqli_real_escape_string($conn, $response);
                    $comment = mysqli_real_escape_string($conn, $_POST['comments'][$question_id]);

                    // Insert student response into the table
                    $query_insert_response = "INSERT INTO $table_name (student_id, question_id, response, comment)
                                              VALUES ('$student_id', '$question_id', '$response', '$comment')";
                    mysqli_query($conn, $query_insert_response);
                }

                // Mark the form as completed for this student
                $query_mark_completed = "INSERT INTO feedback_forms_completed (form_id, student_id, completed_at)
                                         VALUES ('$form_id', '$student_id', NOW())";
                mysqli_query($conn, $query_mark_completed);

                // Unset the selected form session variable
                unset($_SESSION['selected_form_id']);

                echo '<script>alert("Feedback submitted successfully!");</script>';
            } else {
                echo '<script>alert("Please answer all questions!");</script>';
            }
        } else {
            echo '<script>alert("Student does not exist!");</script>';
        }
    } else {
        echo '<script>alert("You have already completed this feedback form.");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Answer Feedback Questions</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link your CSS file -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0; /* Reset default margin */
            padding: 0; /* Reset default padding */
        }
        
        .container {
            max-width: 400px;
            margin: 40px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.4); /* Make the container slightly transparent */
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px; /* Add rounded corners */
        }
        
        h2 {
            color: #333;
            margin-top: 0;
        }
        
        .form-card {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px; /* Add rounded corners */
        }
        
        .form-card h3 {
            margin-top: 0;
        }
        
        .form-card .completed {
            color: green;
            font-weight: bold;
        }
        
        .form-card button {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .form-card button:hover {
            background-color: #3e8e41;
        }
        
        /* Background styling */
        #vanta-bg {
            position: fixed;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
    </style>
</head>
<body>
    <!-- Background -->
    <div id="vanta-bg"></div>

    <!-- Logout and Timepass Button -->
    <div style="position: absolute; top: 10px; right: 10px; z-index: 10;">
        <form action="logout.php" method="post">
            <button type="submit">Logout</button>
        </form>
        <br>
        
    </div>

    <!-- Main content -->
    <div class="container">
        <h2>Available Feedback Forms</h2>
        <?php if (mysqli_num_rows($result_forms) > 0) :?>
            <?php while ($form = mysqli_fetch_assoc($result_forms)) :
                // Check if the student has completed this form
                $form_id = $form['id'];
                $student_id = $_SESSION['user_id'];
                $query_check_completed = "SELECT * FROM feedback_forms_completed WHERE form_id = '$form_id' AND student_id = '$student_id'";
                $result_completed = mysqli_query($conn, $query_check_completed);
                $completed = mysqli_num_rows($result_completed) > 0;
            ?>
                <div class="form-card">
                    <h3><?php echo $form['title']; ?></h3>
                    <p><?php echo $form['description']; ?></p>
                    <?php if ($completed) : ?>
                        <p class="completed">Completed</p>
                    <?php else : ?>
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <input type="hidden" name="form_id" value="<?php echo $form['id']; ?>">
                            <button type="submit" name="select_form">Take this form</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <p>No feedback forms available.</p>
        <?php endif; ?>
    </div>
    
    <?php 
    $selected_form_id = $_SESSION['selected_form_id'] ?? null;
    if ($selected_form_id):
        // Fetch questions for selected form
        $query_fetch_questions = "SELECT * FROM feedback_questions WHERE form_id = '$selected_form_id'";
        $result_questions = mysqli_query($conn, $query_fetch_questions);
        
        // Fetch form title from database
        $query_fetch_form_title = "SELECT title FROM feedback_forms WHERE id = '$selected_form_id'";
        $result_form_title = mysqli_query($conn, $query_fetch_form_title);
        
        if (mysqli_num_rows($result_form_title) > 0) {
            $form_title_row = mysqli_fetch_assoc($result_form_title);
            $form_title = $form_title_row['title'];
        } else {
            $form_title = 'No form found';
        }
    ?>
    
    <div class="container">
        <h3>Selected Form: <?php echo $form_title; ?></h3>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <?php while ($question = mysqli_fetch_assoc($result_questions)) : ?>
                <label for="question_<?php echo $question['id']; ?>"><?php echo $question['question']; ?></label><br>
                <?php if ($question['rating_scale'] === '1-5') : ?>
                    <input type="radio" id="rating1_<?php echo $question['id']; ?>" name="question[<?php echo $question['id']; ?>]" value="1" required>
                    <label for="rating1_<?php echo $question['id']; ?>">1</label>
                    <input type="radio" id="rating2_<?php echo $question['id']; ?>" name="question[<?php echo $question['id']; ?>]" value="2">
                    <label for="rating2_<?php echo $question['id']; ?>">2</label>
                    <input type="radio" id="rating3_<?php echo $question['id']; ?>" name="question[<?php echo $question['id']; ?>]" value="3">
                    <label for="rating3_<?php echo $question['id']; ?>">3</label>
                    <input type="radio" id="rating4_<?php echo $question['id']; ?>" name="question[<?php echo $question['id']; ?>]" value="4">
                    <label for="rating4_<?php echo $question['id']; ?>">4</label>
                    <input type="radio" id="rating5_<?php echo $question['id']; ?>" name="question[<?php echo $question['id']; ?>]" value="5">
                    <label for="rating5_<?php echo $question['id']; ?>">5</label>
                <?php elseif ($question['rating_scale'] === 'Yes-No') : ?>
                    <input type="radio" id="yes_<?php echo $question['id']; ?>" name="question[<?php echo $question['id']; ?>]" value="1" required>
                    <label for="yes_<?php echo $question['id']; ?>">Yes</label>
                    <input type="radio" id="no_<?php echo $question['id']; ?>" name="question[<?php echo $question['id']; ?>]" value="0">
                    <label for="no_<?php echo $question['id']; ?>">No</label>
                <?php endif; ?><br>
                <label for="comment_<?php echo $question['id']; ?>">Comment (optional):</label><br>
                <textarea id="comment_<?php echo $question['id']; ?>" name="comments[<?php echo $question['id']; ?>]" rows="2"></textarea><br><br>
            <?php endwhile; ?>

            <input type="hidden" name="form_id" value="<?php echo $selected_form_id; ?>">
            <input type="submit" name="submit_feedback" value="Submit Feedback">
        </form>
    </div>
    <?php endif; ?>
    <!-- Include the required scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r121/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.birds.min.js"></script>
    <script>
        var setVanta = () => {
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
            });
        };
        setVanta();
    </script>
</body>
</html>

<?php mysqli_close($conn); ?>
