<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "airline";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, 3307);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define the cities
$cities = array('Bangalore', 'Mumbai', 'Delhi', 'Chennai');

// Define the airlines
$airlines = array('AirIndia', 'Emirates', 'Indigo', 'Kingfisher', 'SpiceJet');

// Define the start and end dates
$start_date = '2024-07-08';
$end_date = date('Y-m-d', strtotime($start_date . ' + 3 days'));

$unique_id = 1; // Initialize a unique identifier

for ($current_date = $start_date; $current_date <= $end_date; $current_date = date('Y-m-d', strtotime($current_date . ' +1 day'))) {
    // Loop through each possible route combination
    foreach ($cities as $src) {
        foreach ($cities as $dstn) {
            if ($src != $dstn) {
                // Insert 32 flights for this route combination
                for ($i = 1; $i <= 16; $i++) {
                    $dep_hour = rand(8, 19);
                    $arr_hour = $dep_hour + 2;
                    $plane_name = $airlines[rand(0, 4)];
                    $fare = round(rand(2000, 7000) / 100, 2);

                    // Check if plane data already exists in planes table
                    $sql = "SELECT * FROM planes WHERE Plane_Name = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $plane_name);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows == 0) {
                        // Insert plane data into planes table if it doesn't exist
                        $sql = "INSERT INTO planes (Plane_Name) VALUES (?)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("s", $plane_name);
                        $stmt->execute();
                    }

                    // Generate a unique Flight_ID
                    $flight_id = "FL" . str_pad($unique_id, 6, '0', STR_PAD_LEFT);

                    // Check if flight already exists
                    $sql = "SELECT * FROM aircraft WHERE Flight_ID = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $flight_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows == 0) {
                        // Insert flight data into aircraft table
                        $dep_time = $current_date . " " . str_pad($dep_hour, 2, '0', STR_PAD_LEFT) . ":00:00";
                        $arr_time = $current_date . " " . str_pad($arr_hour, 2, '0', STR_PAD_LEFT) . ":00:00";
                        $sql = "INSERT INTO aircraft (Flight_ID, Dep_Time, Arr_Time, Plane_Name, Src, Dstn, Fare, Dep_Date, Flight_Status)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Scheduled')";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ssssssds", $flight_id, $dep_time, $arr_time, $plane_name, $src, $dstn, $fare, $current_date);
                        $stmt->execute();
                        $unique_id++; // Increment the unique identifier
                    }
                }
            }
        }
    }
}

$conn->close();
?>
