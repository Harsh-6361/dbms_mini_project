<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Airline Reservation System - Flight Details</title>
    <style>
        body {
           
            font-family: 'Yantramanav', sans-serif;
            margin: 100px 150px;
        }
        h1 {
            color: #2C3E50;
            font-family: verdana;
            font-size: 2em;
            text-align: center;
            text-decoration: underline;
        }
        h2 {
            color: #013243;
            font-family: verdana;
            font-size: 1.8em;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        .button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 5px 10px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            margin: 2px;
            cursor: pointer;
            border-radius: 4px;
        }
        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<form action="adminhome.php">
        <button type="submit" class="button">Back</button>
    </form>
    <h1>AIRLINE RESERVATION SYSTEM</h1>

    <h2>Flight Details:</h2>

    <table>
        <thead>
            <tr>
                <th>Flight ID</th>
                <th>Plane Name</th>
                <th>Source</th>
                <th>Destination</th>
                <th>Departure Time</th>
                <th>Arrival Time</th>
                <th>Fare</th>
                <th>Departure Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Database connection
            $con = mysqli_connect("localhost", "root", "", "airline", 3307);
            if (mysqli_connect_errno()) {
                echo "Failed to connect to MySQL: " . mysqli_connect_error();
                exit();
            }
            
            $query = "SELECT * FROM aircraft";
            $result = mysqli_query($con, $query);

            while ($row = mysqli_fetch_array($result)) {
                echo "<tr>";
                echo "<td>".$row['Flight_ID']."</td>";
                echo "<td>".$row['Plane_Name']."</td>";
                echo "<td>".$row['Src']."</td>";
                echo "<td>".$row['Dstn']."</td>";
                echo "<td>".$row['Dep_Time']."</td>";
                echo "<td>".$row['Arr_Time']."</td>";
                echo "<td>".$row['Fare']."</td>";
                echo "<td>".$row['Dep_Date']."</td>";
                echo '<td><a href="deleteflights.php?Flight_ID='.$row['Flight_ID'].'"><button class="button">Delete</button></a></td>';
                echo "</tr>";
            }

            mysqli_close($con);
            ?>
        </tbody>
    </table>

 

</body>
</html>
