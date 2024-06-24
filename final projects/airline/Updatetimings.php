<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Airline Reservation System</title>
    <style>
        body {
            background-color: #f2f2f2; /* Light gray background */
            margin: 0;
            padding: 0;
            color: #2c3e50; /* Dark blue text */
            font-family: 'Yantramanav', sans-serif;
            font-size: 16px;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff; /* White background */
            box-shadow: 0 0 10px rgba(0,0,0,0.1); /* Subtle shadow */
            border-radius: 8px; /* Rounded corners */
        }
        h1 {
            color: #2980b9; /* Blue header text */
            font-family: 'Verdana', sans-serif;
            font-size: 28px;
            text-align: center;
            text-decoration: underline;
            margin-bottom: 20px;
        }
        h2 {
            color: #3498db; /* Dark blue subheader text */
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
            border-bottom: 1px solid #ddd; /* Light gray border bottom */
        }
        th {
            background-color: #4CAF50; /* Green header background */
            color: white;
            cursor: pointer; /* Cursor pointer for sorting */
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
        /* Dropdown styling */
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
        // Function to sort table by column index
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

        // Function to handle sorting option change
        function handleSortChange() {
            const sortField = document.getElementById("sortField").value;
            const sortOrder = document.getElementById("sortOrder").value;
            let columnIndex;

            switch (sortField) {
                case "Flight_ID":
                    columnIndex = 0;
                    break;
                case "Plane_Name":
                    columnIndex = 1;
                    break;
                case "Dep_Time":
                    columnIndex = 2;
                    break;
                case "Dep_Date":
                    columnIndex = 3;
                    break;
                default:
                    columnIndex = 0;
            }

            sortTable(columnIndex, sortOrder);
        }
    </script>
    <script>
        function navigateBack() {
            window.location.href = "adminhome.php";
        }
    </script>
</head>
<body>

<div class="container">
<button class="button" onclick="navigateBack()">Back</button>
    <h1>AIRLINE RESERVATION SYSTEM</h1>

    <div class="center">
        <h2>Update Date and Time:</h2>
        <label for="sortField">Sort by:</label>
        <select id="sortField" onchange="handleSortChange()">
            <option value="Flight_ID">Flight ID</option>
            <option value="Plane_Name">Plane Name</option>
            <option value="Dep_Time">Departure Time</option>
            <option value="Dep_Date">Departure Date</option>
        </select>
        <select id="sortOrder" onchange="handleSortChange()">
            <option value="asc">Ascending</option>
            <option value="desc">Descending</option>
        </select>
        <table>
            <thead>
                <tr>
                    <th onclick="sortTable(0, 'asc')">Flight ID</th>
                    <th onclick="sortTable(1, 'asc')">Plane Name</th>
                    <th onclick="sortTable(2, 'asc')">Departure Time</th>
                    <th onclick="sortTable(3, 'asc')">Departure Date</th>
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
                
                $query = "SELECT Flight_ID, Plane_Name, Dep_Time, Dep_Date FROM Aircraft";
                $result = mysqli_query($con, $query);

                while ($row = mysqli_fetch_array($result)) {
                    echo "<tr>";
                    echo "<td>".$row['Flight_ID']."</td>";
                    echo "<td>".$row['Plane_Name']."</td>";
                    echo "<td>".$row['Dep_Time']."</td>";
                    echo "<td>".$row['Dep_Date']."</td>";
                    echo '<td><a href="Update.php?Flight_ID='.$row['Flight_ID'].'"><button class="button">Update</button></a></td>';
                    echo "</tr>";
                }

                mysqli_close($con);
                ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
