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

// Get the form_id from the URL
$form_id = isset($_GET['form_id']) ? intval($_GET['form_id']) : 0;

if ($form_id <= 0) {
    echo '<p>Invalid form ID.</p>';
    exit;
}

// Fetch the feedback form details
$query_form = "SELECT * FROM feedback_forms WHERE id = '$form_id'";
$result_form = mysqli_query($conn, $query_form);

if (mysqli_num_rows($result_form) == 0) {
    echo '<p>Feedback form not found.</p>';
    exit;
}

$form = mysqli_fetch_assoc($result_form);

// Fetch the questions for the feedback form
$query_questions = "SELECT * FROM feedback_questions WHERE form_id = '$form_id'";
$result_questions = mysqli_query($conn, $query_questions);

// Prepare an array to store average ratings
$average_ratings = [];

// Calculate the average rating for each question
while ($question = mysqli_fetch_assoc($result_questions)) {
    $question_id = $question['id'];
    $rating_scale = $question['rating_scale'];

    if ($rating_scale == '1-5') {
        // Calculate average rating for 1-5 scale questions
        $query_avg_rating = "SELECT AVG(response) AS avg_rating FROM responses_$form_id WHERE question_id = '$question_id'";
        $result_avg_rating = mysqli_query($conn, $query_avg_rating);
        $avg_rating = mysqli_fetch_assoc($result_avg_rating)['avg_rating'];
        $average_ratings[$question_id] = round($avg_rating, 2);
    } else if ($rating_scale == 'Yes-No') {
        // Calculate average rating for Yes-No questions (1 for Yes, 0 for No)
        $query_avg_rating = "SELECT AVG(response) AS avg_rating FROM responses_$form_id WHERE question_id = '$question_id'";
        $result_avg_rating = mysqli_query($conn, $query_avg_rating);
        $avg_rating = mysqli_fetch_assoc($result_avg_rating)['avg_rating'];
        $average_ratings[$question_id] = round($avg_rating * 100, 2) . '% Yes';
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Form Responses</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link your CSS file -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
            margin: 0;
            overflow: hidden;
        }

        .vanta-container {
            position: fixed;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            margin-top: 0;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
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

        .question {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 15px;
            margin-bottom: 20px;
        }

        .question h3 {
            margin-top: 0;
        }

        .question p {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="vanta-container" id="vanta-bg"></div>
    <div class="container">
        <div class="header">
            <h2>Average Ratings for: <?php echo htmlspecialchars($form['title']); ?></h2>
            <a href="view_form_graph.php?form_id=<?php echo $form_id; ?>">View Graph</a>
        </div>

        <?php if (mysqli_num_rows($result_questions) > 0) : ?>
            <?php mysqli_data_seek($result_questions, 0); // Reset result set pointer ?>
            <?php while ($question = mysqli_fetch_assoc($result_questions)) : ?>
                <div class="question">
                    <h3>Question: <?php echo htmlspecialchars($question['question']); ?></h3>
                    <p>Average Rating: <?php echo $average_ratings[$question['id']]; ?></p>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <p>No questions found for this feedback form.</p>
        <?php endif; ?>
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
