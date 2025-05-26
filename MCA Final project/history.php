<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agro Farmers History Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f4f4f9;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        .wrapper {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #003366;
        }
        .grid-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        .chart-container {
            width: 90%;
            height: 250px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            padding: 10px;
        }
        canvas {
            width: 100% !important;
            height: 180px !important;
        }
        .system-chart-container {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    margin-top: 20px;
    margin-left: 210px; /* Adjust this value to move it slightly */
}

.system-chart-container .chart-container {
    width: 250px;
    text-align: center;
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
    <div class="wrapper">
        <h2>Agro Farmers History Analysis</h2>
        <a href="agro.php" class="home-btn">Home</a>
        <div class="grid-container">
            <div class="chart-container">
                <h4>Pump ON/OFF</h4>
                <canvas id="pumpChart"></canvas>
            </div>

            <div class="chart-container">
                <h4>Fan ON/OFF</h4>
                <canvas id="fanChart"></canvas>
            </div>

            <div class="chart-container">
                <h4>Temperature Trends</h4>
                <canvas id="temperatureChart"></canvas>
            </div>

            <div class="chart-container">
                <h4>Moisture Trends</h4>
                <canvas id="moistureChart"></canvas>
            </div>

            <div class="system-chart-container">
             <div class="chart-container">
             <h4>System ON/OFF</h4>
             <canvas id="systemChart"></canvas>
             </div>
            </div>



        </div>
    </div>

    <script>
        function fetchHistoryData() {
            fetch('fetch_history.php')
                .then(response => response.json())
                .then(data => {
                    updateBarChart('pumpChart', ['ON', 'OFF'], [data.pump_on, data.pump_off], 'Pump Status');
                    updateBarChart('fanChart', ['ON', 'OFF'], [data.fan_on, data.fan_off], 'Fan Status');
                    updateLineChart('temperatureChart', data.timestamps, data.max_temp, data.min_temp, 'Temperature (°C)');
                    updateLineChart('moistureChart', data.timestamps, data.max_moisture, data.min_moisture, 'Moisture (%)');
                    updatePieChart('systemChart', ['All ON', 'Mixed', 'All OFF'], [data.all_on, data.mixed, data.all_off], 'System Status');
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        function updateBarChart(chartId, labels, data, label) {
            new Chart(document.getElementById(chartId), {
                type: 'bar',
                data: { labels, datasets: [{ label, data, backgroundColor: ['#28a745', '#dc3545'] }] },
            });
        }

        function updateLineChart(chartId, labels, maxData, minData, label) {
            new Chart(document.getElementById(chartId), {
                type: 'line',
                data: {
                    labels,
                    datasets: [
                        { label: 'Max ' + label, data: maxData, borderColor: 'red', fill: false },
                        { label: 'Min ' + label, data: minData, borderColor: 'blue', fill: false }
                    ]
                }
            });
        }

        function updatePieChart(chartId, labels, data, label) {
            new Chart(document.getElementById(chartId), {
                type: 'pie',
                data: {
                    labels,
                    datasets: [{ label, data, backgroundColor: ['green', 'orange', 'red'] }]
                }
            });
        }

        fetchHistoryData();
    </script>
</body>
</html>
