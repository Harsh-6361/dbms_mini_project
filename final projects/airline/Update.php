<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Airline Reservation System</title>
    <style>
        body {
            background-color: #f0f0f0; /* Light gray background */
            margin: 50px; /* Adjust margin for spacing */
            color: #261A15;
            font-family: 'Yantramanav', sans-serif;
            font-size: 16px;
        }
        .container {
            max-width: 800px; /* Adjust container width */
            margin: auto; /* Center align container */
            padding: 20px;
            background-color: #fff; /* White background */
            box-shadow: 0 0 10px rgba(0,0,0,0.1); /* Subtle shadow */
        }
        h1 {
            color: #2C3E50;
            font-family: verdana;
            font-size: 28px;
            text-align: center;
            text-decoration: underline;
            margin-bottom: 20px;
        }
        h2 {
            color: #013243;
            font-family: verdana;
            font-size: 22px;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd; /* Light gray border bottom */
        }
        th {
            background-color: #4CAF50; /* Green header background */
            color: white;
        }
        tr:hover {
            background-color: #f2f2f2; /* Light gray background on hover */
        }
        .button {
            background-color: #4CAF50; /* Green button background */
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 10px 0;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .button:hover {
            background-color: #45a049; /* Darker green on hover */
        }
        .center {
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>AIRLINE RESERVATION SYSTEM</h1>

    <div class="center">
        <h2>Update Date and Time:</h2>
        <form action="Updatetimings.php" method="POST">
            <table>
                <thead>
                    <tr>
                        <th>Flight ID</th>
                        <th>Plane Name</th>
                        <th>Departure Date</th>
                        <th>Departure Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    session_start();
                    $con = mysqli_connect('localhost', 'root', '', 'airline', 3307) or die(mysql_error());
                    
                    if (!isset($_POST["DepartureTime"])) { 
                        echo "
                        <tr>
                            <td>Edit Departure Date and Time:</td>
                        </tr>
                        <tr>
                            <td>Flight ID</td>
                            <td>Departure Date</td>
                            <td>Departure Time</td>
                            <td>Submit</td>
                        </tr>
                        <tr>
                            <td>".$_GET["Flight_ID"]."</td>
                            <td><input type=\"date\" name=\"DepartureDate\" required></td>
                            <td><input type=\"time\" name=\"DepartureTime\" required></td>
                            <td><input type=\"submit\" value=\"Update\"></td>
                        </tr>";
                    } else {
                        $sql = "UPDATE `Aircraft` SET `Dep_Time`='".$_POST["DepartureTime"]."' ,`Dep_Date`='".$_POST["DepartureDate"]."' WHERE Flight_ID='".$_GET["id"]."'";
                        
                        if ($con->query($sql) === TRUE) {
                            echo "<tr><td colspan=\"5\" class=\"center\">Departure Date and Time updated successfully</td></tr>";
                        } else {
                            echo "<tr><td colspan=\"5\" class=\"center\">Error: ".$con->error."</td></tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </form>

        <a href="adminhome.php" class="button">Back to Admin Menu</a>
    </div>
</div>

</body>
</html>
