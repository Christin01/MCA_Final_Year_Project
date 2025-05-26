<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "smart_agriculture";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get total ON and OFF times
$sql = "SELECT 
            SUM(pump_status = 1) AS pump_on, 
            SUM(pump_status = 0) AS pump_off, 
            SUM(fan_status = 1) AS fan_on, 
            SUM(fan_status = 0) AS fan_off,
            SUM(pump_status = 1 AND fan_status = 1) AS all_on,
            SUM(pump_status = 0 AND fan_status = 0) AS all_off,
            SUM((pump_status = 1 OR fan_status = 1) AND NOT (pump_status = 1 AND fan_status = 1)) AS mixed
        FROM sensor_data";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

// Get max and min temperature and moisture trends
$sql = "SELECT 
            DATE(timestamp) AS date, 
            MAX(temperature) AS max_temp, MIN(temperature) AS min_temp, 
            MAX(moisture) AS max_moisture, MIN(moisture) AS min_moisture 
        FROM sensor_data GROUP BY DATE(timestamp)";
$result = $conn->query($sql);

$timestamps = [];
$max_temp = [];
$min_temp = [];
$max_moisture = [];
$min_moisture = [];

while ($data = $result->fetch_assoc()) {
    $timestamps[] = $data['date'];
    $max_temp[] = $data['max_temp'];
    $min_temp[] = $data['min_temp'];
    $max_moisture[] = $data['max_moisture'];
    $min_moisture[] = $data['min_moisture'];
}

// Merge data
$row['timestamps'] = $timestamps;
$row['max_temp'] = $max_temp;
$row['min_temp'] = $min_temp;
$row['max_moisture'] = $max_moisture;
$row['min_moisture'] = $min_moisture;

header('Content-Type: application/json');
echo json_encode($row);

$conn->close();
?>
