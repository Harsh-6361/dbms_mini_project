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

// Fetch the total number of students
$query_total_students = "SELECT COUNT(*) as total_students FROM students";
$result_total_students = mysqli_query($conn, $query_total_students);
$total_students = mysqli_fetch_assoc($result_total_students)['total_students'];

// Fetch the number of students who have completed the form
$query_completed_students = "SELECT COUNT(DISTINCT student_id) as completed_students FROM responses_$form_id";
$result_completed_students = mysqli_query($conn, $query_completed_students);
$completed_students = mysqli_fetch_assoc($result_completed_students)['completed_students'];

// Calculate the number of students who have not taken the form
$not_taken_students = $total_students - $completed_students;

// Fetch the questions for the feedback form
$query_questions = "SELECT * FROM feedback_questions WHERE form_id = '$form_id'";
$result_questions = mysqli_query($conn, $query_questions);

// Prepare an array to store responses for each question
$question_responses = [];
while ($question = mysqli_fetch_assoc($result_questions)) {
    $question_id = $question['id'];
    $question_text = $question['question'];
    $rating_scale = $question['rating_scale'];
    
    // Initialize response data
    $responses = [];
    if ($rating_scale == '1-5') {
        for ($i = 1; $i <= 5; $i++) {
            $query_response_count = "SELECT COUNT(*) as count FROM responses_$form_id WHERE question_id = '$question_id' AND response = '$i'";
            $result_response_count = mysqli_query($conn, $query_response_count);
            $responses[$i] = mysqli_fetch_assoc($result_response_count)['count'];
        }
    } else if ($rating_scale == 'Yes-No') {
        $query_yes_count = "SELECT COUNT(*) as count FROM responses_$form_id WHERE question_id = '$question_id' AND response = '1'";
        $result_yes_count = mysqli_query($conn, $query_yes_count);
        $responses['Yes'] = mysqli_fetch_assoc($result_yes_count)['count'];
        
        $query_no_count = "SELECT COUNT(*) as count FROM responses_$form_id WHERE question_id = '$question_id' AND response = '0'";
        $result_no_count = mysqli_query($conn, $query_no_count);
        $responses['No'] = mysqli_fetch_assoc($result_no_count)['count'];
    }
    
    $question_responses[] = [
        'question' => $question_text,
        'rating_scale' => $rating_scale,
        'responses' => $responses
    ];
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Responses Graph</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
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

        .chart-container {
            margin-bottom: 40px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Graph of Students' Responses</h2>
            <div>
                <a href="teacher.php">Create Form</a>
                <a href="view_feedback_forms.php">Back</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="completionChart" width="400" height="200"></canvas>
        </div>

        <?php foreach ($question_responses as $index => $question_response) : ?>
            <div class="chart-container">
                <h3><?php echo htmlspecialchars($question_response['question']); ?></h3>
                <canvas id="questionChart<?php echo $index; ?>" width="400" height="200"></canvas>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        var ctx = document.getElementById('completionChart').getContext('2d');
        var completionChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Completed', 'Not Completed'],
                datasets: [{
                    label: 'Responses',
                    data: [<?php echo $completed_students; ?>, <?php echo $not_taken_students; ?>],
                    backgroundColor: ['#4CAF50', '#FF0000'],
                    borderColor: ['#4CAF50', '#FF0000'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Students who have taken the form vs those who have not'
                    }
                }
            }
        });

        <?php foreach ($question_responses as $index => $question_response) : ?>
            var ctx = document.getElementById('questionChart<?php echo $index; ?>').getContext('2d');
            var questionChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode(array_keys($question_response['responses'])); ?>,
                    datasets: [{
                        label: 'Responses',
                        data: <?php echo json_encode(array_values($question_response['responses'])); ?>,
                        backgroundColor: '#007bff',
                        borderColor: '#007bff',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false,
                        },
                        title: {
                            display: true,
                            text: 'Responses for "<?php echo htmlspecialchars($question_response['question']); ?>"'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        <?php endforeach; ?>
    </script>
</body>
</html>
