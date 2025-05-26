<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agro Farmers Monitor</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>

body {
        background: -webkit-linear-gradient(left, #003366,#004080,#0059b3, #0073e6);
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }
    .wrapper {
        background-color: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        max-width: 1200px;
        margin: 20px auto;
    }
    .data-table {
        background-color:rgba(255, 255, 255, 0.88);
    }
    .data-table th {
        background-color: #003366;
    }

        .main-heading {
            text-align: center;
            font-size: 28px;
            font-weight: 700;
            color: #003366;
            margin-bottom: 20px;
        }
        .wrapper {
            text-align: center;
        }
        .dashboard {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .chart-container {
            width: 45%;
            margin: 20px;
        }
        .data-table {
            margin: 20px auto;
            width: 80%;
            border-collapse: collapse;
        }
        .data-table th, .data-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        .data-table th {
            background-color: #003366;
            color: white;
        }
        .main-heading {
            text-align: center;
            font-size: 32px;
            font-weight: 700;
            color: #003366;
            margin-bottom: 20px;
        }
        .canvas-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 30px;
        }
        .canvas-container a {
            text-decoration: none;
            color: white;
            font-size: 24px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 15px;
            background-color: #003366;
            padding: 10px 70px;
            border-radius: 15px;
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.3);
        }
        .canvas-container img {
            width: 120px;
            height: 120px;
            border-radius: 15px;
            
        }
        .canvas-container p {
            color: red;
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
        }
        .instruction {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            color: red;
            margin: 20px 0;
        }
        .history-btn {
    display: inline-block;
    padding: 10px 20px;
    background-color:#003366;
    color: white;
    text-decoration: none;
    font-size: 16px;
    border-radius: 5px;
    transition: background 0.3s ease;
}

.history-btn:hover {
    background-color:#003366;
}

        
    </style>
</head>
<body>
    <div class="wrapper">
        <h2 class="main-heading">Agro Farmers Monitor</h2>

        <div class="canvas-container">
        <a href="http://localhost/MCA_Project/Tabledata.php">
            <img src="smart_agriculture_icon.png" alt="Smart Agriculture Icon">
            Click here to see data
        </a>
        <p>Click above for Smart Agricultural Data<span class="hand-icon">👆</span></p>
         </div>

         <div class="instruction">
         
         <div id="warningSection">Loading warnings...</div>

<script>
function fetchWarnings() {
    fetch('fetch_warnings.php')
        .then(response => response.json())
        .then(data => {
            let warningSection = document.getElementById("warningSection");
            if (data.warnings && data.warnings.length > 0) {
                warningSection.innerHTML = data.warnings.map(warning => `<p>${warning}</p>`).join("");
            } else {
                warningSection.innerHTML = "<p>No warnings.</p>";
            }
        })
        .catch(error => {
            console.error('Error fetching warnings:', error);
            document.getElementById("warningSection").innerHTML = "<p>Error loading warnings.</p>";
        });
}

// Fetch warnings every 5 seconds
setInterval(fetchWarnings, 5000);
fetchWarnings();
</script>


    </div>

        <h3>Sensor Data & Status</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Temperature</th>
                    <th>Humidity</th>
                    <th>Moisture</th>
                    <th>Pump Status</th>
                    <th>Fan Status</th>
                </tr>
            </thead>
            <tbody id="sensorData">
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
if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    echo "<tr>
            <td>{$data['temperature']}°C</td>
            <td>{$data['humidity']}%</td>
            <td>{$data['moisture']}%</td>
            <td class='" . ($data['pump_status'] == 1 ? "on" : "off") . "'>" . ($data['pump_status'] == 1 ? "ON" : "OFF") . "</td>
            <td class='" . ($data['fan_status'] == 1 ? "on" : "off") . "'>" . ($data['fan_status'] == 1 ? "ON" : "OFF") . "</td>
          </tr>";
}
$conn->close();
?>


            </tbody>
        </table>
        <a href="history.php" class="history-btn">Click here to navigate to Agro History Analysis</a>
        <div class="dashboard">
            <div class="chart-container"><canvas id="tempChart"></canvas></div>
            <div class="chart-container"><canvas id="humidityChart"></canvas></div>
            <div class="chart-container"><canvas id="moistureChart"></canvas></div>
            <div class="chart-container"><canvas id="pumpChart"></canvas></div>
            <div class="chart-container"><canvas id="fanChart"></canvas></div>
        </div>
        
        
    </div>
    

    <script>
        let tempChart, humidityChart, moistureChart, pumpChart, fanChart;

        function fetchData() {
            fetch('fetch_data.php')
                .then(response => response.json())
                .then(data => {
                    updateChart(tempChart, data.temperature);
                    updateChart(humidityChart, data.humidity);
                    updateChart(moistureChart, data.moisture);
                    updateChart(pumpChart, data.pump_status);
                    updateChart(fanChart, data.fan_status);

                    document.getElementById("sensorData").innerHTML = `
                        <tr>
    <td>${data['temperature']}°C</td>
    <td>${data['humidity']}%</td>
    <td>${data['moisture']}%</td>
    <td class="${data['pump_status'] == 1 ? 'on' : 'off'}">${data['pump_status'] == 1 ? 'ON' : 'OFF'}</td>
    <td class="${data['fan_status'] == 1 ? 'on' : 'off'}">${data['fan_status'] == 1 ? 'ON' : 'OFF'}</td>
</tr>`;
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        function createChart(ctx, label, color) {
            return new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: label,
                        data: [],
                        borderColor: color,
                        fill: false
                    }]
                }
            });
        }

        function updateChart(chart, value) {
            let now = new Date().toLocaleTimeString();
            chart.data.labels.push(now);
            chart.data.datasets[0].data.push(value);
            if (chart.data.labels.length > 10) {
                chart.data.labels.shift();
                chart.data.datasets[0].data.shift();
            }
            chart.update();
        }

        document.addEventListener("DOMContentLoaded", function() {
            tempChart = createChart(document.getElementById('tempChart').getContext('2d'), 'Temperature', 'red');
            humidityChart = createChart(document.getElementById('humidityChart').getContext('2d'), 'Humidity', 'blue');
            moistureChart = createChart(document.getElementById('moistureChart').getContext('2d'), 'Moisture', 'green');
            pumpChart = createChart(document.getElementById('pumpChart').getContext('2d'), 'Pump Status', 'purple');
            fanChart = createChart(document.getElementById('fanChart').getContext('2d'), 'Fan Status', 'orange');
            setInterval(fetchData, 3000);
        });
    </script>
    

    
</body>
</html>