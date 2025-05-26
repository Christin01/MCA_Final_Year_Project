<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "smart_agriculture";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM sensor_data ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);
$data = $result->fetch_assoc();

header('Content-Type: application/json');
echo json_encode($data);

$conn->close();
?>
