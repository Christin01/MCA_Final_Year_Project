<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$database = "smart_agriculture";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed"]);
    exit;
}

$sql = "SELECT * FROM sensor_data ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);
$data = $result->fetch_assoc();

$warnings = [];
if ($data) {
    if ($data['moisture'] < 30) {
        $warnings[] = "WARNING! Moisture is less, ensure the proper water content of crop. Water pump is running ON.";
    }
    if ($data['temperature'] > 29) {
        $warnings[] = "WARNING! Make sure there is proper air for crops. The temperature is increasing.";
    }
    if ($data['moisture'] > 50) {
        $warnings[] = "INFORMATION: You can use good fertilizers because your crop soil moisture is good.";
    }
    if ($data['moisture'] > 70) {
        $warnings[] = "CAUTION! Water content in soil is increasing. Your water pump may turn OFF.";
    }
}

$conn->close();
echo json_encode(["warnings" => $warnings]);
?>
