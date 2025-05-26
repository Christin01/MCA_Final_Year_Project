<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "smart_agriculture";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM sensor_data ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Agriculture Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        h2 {
            text-align: center;
            color:rgb(40, 53, 167);
            font-size: 24px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background:rgb(40, 49, 167);
            color: white;
        }
        tr:nth-child(even) {
            background: #f2f2f2;
        }
        .on {
            color: green;
            font-weight: bold;
        }
        .off {
            color: red;
            font-weight: bold;
        }
        .home-btn {
        display: inline-block;
        padding: 10px 15px;
        background-color: rgb(40, 49, 167);
        color: white;
        text-decoration: none;
        font-size: 16px;
        border-radius: 5px;
        transition: background 0.3s ease;
        position: absolute;
        top: 20px;
        left: 20px;
    }

    .home-btn:hover {
        background-color:rgb(17, 42, 70);
    }
    </style>
</head>
<body>
    <h2>Smart Agriculture Monitoring Data</h2>
    <a href="agro.php" class="home-btn">Home</a>
    <table>
        <tr>
            <th>ID</th>
            <th>Temperature (°C)</th>
            <th>Humidity (%)</th>
            <th>Moisture (%)</th>
            <th>Pump Status</th>
            <th>Fan Status</th>
            <th>Buzzer Status</th>
            <th>Timestamp</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['temperature'] . "</td>";
                echo "<td>" . $row['humidity'] . "</td>";
                echo "<td>" . $row['moisture'] . "</td>";
                echo "<td class='" . ($row['pump_status'] ? "on" : "off") . "'>" . ($row['pump_status'] ? "ON" : "OFF") . "</td>";
                echo "<td class='" . ($row['fan_status'] ? "on" : "off") . "'>" . ($row['fan_status'] ? "ON" : "OFF") . "</td>";
                echo "<td class='" . ($row['buzzer_status'] ? "on" : "off") . "'>" . ($row['buzzer_status'] ? "ON" : "OFF") . "</td>";
                echo "<td>" . $row['timestamp'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No data available</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>