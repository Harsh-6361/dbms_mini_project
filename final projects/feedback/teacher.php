<?php
session_start();

// Database connection
$conn = mysqli_connect("localhost", "root", "", "feedback", 3307);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle form submission to create feedback form
if (isset($_POST['create_form'])) {
    $teacher_id = $_SESSION['user_id']; // Assuming teacher_id is stored in session
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $questions = $_POST['questions'];
    $rating_scales = $_POST['rating_scales'];
    
    // Insert feedback form
    $query_insert_form = "INSERT INTO feedback_forms (teacher_id, title, description, created_at) 
                          VALUES ('$teacher_id', '$title', '$description', NOW())";
    mysqli_query($conn, $query_insert_form);
    $form_id = mysqli_insert_id($conn); // Get the ID of the inserted form
    
    // Create a table for storing responses
    $table_name = 'responses_' . $form_id;
    $create_table_query = "CREATE TABLE $table_name (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            student_id INT,
                            question_id INT,
                            response TEXT,
                            comment TEXT,
                            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)";
    
    mysqli_query($conn, $create_table_query);
    
    // Insert each question into feedback_questions
    foreach ($questions as $index => $question) {
        $question_text = mysqli_real_escape_string($conn, $question);
        $rating_scale = mysqli_real_escape_string($conn, $rating_scales[$index]);
        $query_insert_question = "INSERT INTO feedback_questions (form_id, question, rating_scale) 
                                 VALUES ('$form_id', '$question_text', '$rating_scale')";
        mysqli_query($conn, $query_insert_question);
    }

    echo '<script>alert("Feedback form created successfully!");</script>';
    header('Location: view_feedback_forms.php'); // Redirect to view feedback forms page
    exit;
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Feedback Form</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link your CSS file -->
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: auto; /* Allow height to be determined by content */
            overflow: auto; /* Enable scrolling */
            font-family: Arial, sans-serif;
        }
        
        #vanta-bg {
            position: fixed;
            width: 100%;
            height: 100%;
            z-index: -1; /* Send to back */
        }

        .header {
            text-align: right;
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            z-index: 2; /* Ensure the header stays above other content */
            background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent white */
            padding: 10px;
        }
        
        .header a {
            color: #007bff;
            text-decoration: none;
            padding: 10px 20px;
            margin: 0 10px;
            background-color: #fff;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        
        .header a:hover {
            background-color: #f0f0f0;
        }

        .card {
            max-width: 800px;
            margin: 100px auto 40px auto; /* Adjusted margin to allow for the fixed header */
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.1); /* Semi-transparent white */
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1;
            border-radius: 10px;
        }
        
        h2 {
            color: #333;
            margin-top: 0;
        }
        
        label {
            display: block;
            margin-bottom: 10px;
        }
        
        input[type="text"], textarea {
            width: calc(100% - 20px); /* Adjusted for padding and border */
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        
        select {
            width: calc(100% - 20px); /* Adjusted for padding and border */
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        
        button[type="submit"], .add-button, .delete-button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        
        button[type="submit"]:hover, .add-button:hover, .delete-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div id="vanta-bg"></div>
    <div class="header">
        <a href="view_feedback_forms.php">View Feedback Forms</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="card">
        <h2>Create Feedback Form</h2>
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required><br><br>
            <label for="description">Description:</label><br>
            <textarea id="description" name="description" rows="4"></textarea><br><br>
            <label>Questions:</label><br>
            <div id="questions-container">
                <div class="question">
                    <input type="text" name="questions[]" placeholder="Enter question 1" required>
                    <select name="rating_scales[]">
                        <option value="1-5">1-5</option>
                        <option value="Yes-No">Yes-No</option>
                    </select>
                    <button type="button" class="delete-button">Delete</button>
                </div>
            </div>
            <button type="button" class="add-button" id="add-question">Add Question</button><br>
            <button type="submit" name="create_form">Create Form</button>
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
    <script>
        let questionCount = 1;
        const addQuestionButton = document.getElementById('add-question');
        const questionsContainer = document.getElementById('questions-container');
        
        addQuestionButton.addEventListener('click', () => {
            questionCount++;
            const newQuestion = document.createElement('div');
            newQuestion.className = 'question';
            newQuestion.innerHTML = `
                <input type="text" name="questions[]" placeholder="Enter question ${questionCount}" required>
                <select name="rating_scales[]">
                    <option value="1-5">1-5</option>
                    <option value="Yes-No">Yes-No</option>
                </select>
                <button type="button" class="delete-button">Delete</button>
            `;
            questionsContainer.appendChild(newQuestion);
        });
        
        questionsContainer.addEventListener('click', (e) => {
            if (e.target.classList.contains('delete-button')) {
                e.target.parentNode.remove();
                questionCount--;
            }
        });
    </script>
</body>
</html>
