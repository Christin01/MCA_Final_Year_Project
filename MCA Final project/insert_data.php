<?php
$servername = "localhost"; // Change if needed
$username = "root"; // Default for XAMPP
$password = ""; // Default for XAMPP
$database = "smart_agriculture";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Read data from ESP32
$temperature = $_POST['temperature'];
$humidity = $_POST['humidity'];
$moisture = $_POST['moisture'];
$pump_status = $_POST['pump_status'];
$fan_status = $_POST['fan_status'];
$buzzer_status = $_POST['buzzer_status'];

// SQL query to insert data
$sql = "INSERT INTO sensor_data (temperature, humidity, moisture, pump_status, fan_status, buzzer_status) 
        VALUES ('$temperature', '$humidity', '$moisture', '$pump_status', '$fan_status', '$buzzer_status')";

if ($conn->query($sql) === TRUE) {
    echo "Data inserted successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
