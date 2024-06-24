<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Bookings</title>
    <style>
        body {
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            color: #2c3e50;
            font-family: 'Yantramanav', sans-serif;
            font-size: 16px;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        h1 {
            color: #2980b9;
            font-family: 'Verdana', sans-serif;
            font-size: 28px;
            text-align: center;
            text-decoration: underline;
            margin-bottom: 20px;
        }
        h2 {
            color: #3498db;
            font-family: 'Verdana', sans-serif;
            font-size: 22px;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }
        tr:hover {
            background-color: #f2f2f2;
        }
        .button {
            background-color: #4CAF50;
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
            background-color: #45a049;
        }
        .center {
            text-align: center;
        }
        select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-right: 10px;
            background-color: white;
            font-size: 16px;
        }
    </style>
     <script>
        function navigateBack() {
            window.location.href = "adminhome.php";
        }
    </script>
    <script>
        function sortTable(columnIndex, order) {
            let table, rows, switching, i, x, y, shouldSwitch;
            table = document.querySelector("table");
            switching = true;
            while (switching) {
                switching = false;
                rows = table.rows;
                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("td")[columnIndex];
                    y = rows[i + 1].getElementsByTagName("td")[columnIndex];
                    if (order === "asc" ? x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase() : x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                }
                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                }
            }
        }

        function handleSortChange() {
            const sortField = document.getElementById("sortField").value;
            const sortOrder = document.getElementById("sortOrder").value;
            let columnIndex;

            switch (sortField) {
                case "Booking_ID":
                    columnIndex = 0;
                    break;
                case "Passenger_Name":
                    columnIndex = 1;
                    break;
                case "Flight_ID":
                    columnIndex = 2;
                    break;
                case "Dep_Time":
                    columnIndex = 3;
                    break;
                default:
                    columnIndex = 0;
            }

            sortTable(columnIndex, sortOrder);
        }

        function cancelBooking(bookingId) {
            if (confirm("Are you sure you want to cancel this booking?")) {
                window.location.href = `cancelBooking.php?Booking_ID=${bookingId}`;
            }
        }

        function cancelPassenger(passengerId) {
            if (confirm("Are you sure you want to cancel this passenger?")) {
                window.location.href = `cancelPassenger.php?P_ID=${passengerId}`;
            }
        }
    </script>
</head>
<body>
<div class="container">
<button class="button" onclick="navigateBack()">Back</button>
    <h1>Manage Bookings</h1>

    <div class="center">
        <h2>View and Manage All Bookings:</h2>
        <label for="sortField">Sort by:</label>
        <select id="sortField" onchange="handleSortChange()">
            <option value="Booking_ID">Booking ID</option>
            <option value="Name">Passenger Name</option>
            <option value="Flight_ID">Flight ID</option>
            <option value="Dep_Time">Departure Time</option>
        </select>
        <select id="sortOrder" onchange="handleSortChange()">
            <option value="asc">Ascending</option>
            <option value="desc">Descending</option>
        </select>
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Passenger Name</th>
                    <th>Flight ID</th>
                    <th>Departure Time</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Age</th>
                    <th>Gender</th>
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

                $query = "SELECT * FROM bookings";
                $result = mysqli_query($con, $query);

                while ($row = mysqli_fetch_array($result)) {
                    echo "<tr>";
                    echo "<td>".$row['Booking_ID']."</td>";
                    echo "<td>".$row['Name']."</td>";
                    echo "<td>".$row['Flight_ID']."</td>";
                    echo "<td>".$row['Dep_Time']."</td>";
                    echo "<td>".$row['Email']."</td>";
                    echo "<td>".$row['Phone']."</td>";
                    echo "<td>".$row['Age']."</td>";
                    echo "<td>".$row['Gender']."</td>";
                    echo '<td><button class="button" onclick="cancelBooking('.$row['Booking_ID'].')">Cancel Booking</button></td>';
                    echo "</tr>";

                    $passengerQuery = "SELECT * FROM passenger WHERE Flight_ID = '".$row['Flight_ID']."' AND Dep_Time = '".$row['Dep_Time']."' AND User_ID = '".$row['User_ID']."'";
                    $passengerResult = mysqli_query($con, $passengerQuery);

                    while ($passengerRow = mysqli_fetch_array($passengerResult)) {
                        echo "<tr style='background-color: #e7f4ff;'>";
                        echo "<td colspan='2'></td>";
                        echo "<td>Passenger: ".$passengerRow['Name']."</td>";
                        echo "<td>".$passengerRow['Dep_Time']."</td>";
                        echo "<td colspan='3'></td>";
                        echo '<td><button class="button" onclick="cancelPassenger('.$passengerRow['P_ID'].')">Cancel Passenger</button></td>';
                        echo "</tr>";
                    }
                }

                mysqli_close($con);
                ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
