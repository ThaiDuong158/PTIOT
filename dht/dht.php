<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include '../TrangMau/link.php'; ?>
    <link rel="stylesheet" href="../css/dht.css">
    <title>Document</title>
</head>

<body>
    <div class="main container-fluid">
        <?php include '../TrangMau/header.php'; ?>

        <div class="row">
            <?php include '../TrangMau/sidebar.php'; ?>
            <div class="col bg-light d-flex flex-column justify-content-between">
                <div class="content row container-fluid" style="height: 100px;">
                    <div class="content--thoiTiet content--region">
                        <h1 class="content--title">Điều khiển nhiệt độ motor</h1>
                        <div class="row">
                            <div class="col-6">
                                <!-- <span class="content--item">Nhiệt độ: <p id="nhietDo">0 độ C</p></span> -->
                                <canvas id="myChart" class="container-fluid"></canvas>
                            </div>
                            <div class="col">
                                <!-- <span class="content--item">Độ ẩm: <p id="doAm">0 %</p></span> -->
                                <canvas id="myChart2" class="container-fluid"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="content--chuongGa content--region">
                        <h1 class="content--title">Điều khiển nhiệt độ chuồng gà</h1>
                    </div>
                </div>
                <?php include '../TrangMau/footer.php'; ?>
                <?php include '../TrangMau/hideSidebar.php'; ?>
            </div>
        </div>

        <script>
            const topic = "Buoi4";
            const client = new Paho.MQTT.Client("broker.hivemq.com", Number(8000), "0000");

            client.onConnectionLost = onConnectionLost;
            client.onMessageArrived = onMessageArrived;

            client.connect({ onSuccess: onConnect });

            function onConnect() {
                console.log("onConnect");
                client.subscribe(topic);
            }

            function onConnectionLost(responseObject) {
                if (responseObject.errorCode !== 0) {
                    console.log("onConnectionLost:" + responseObject.errorMessage);
                }
            }

            function sendDataToServer(doC, doH) {
                var nhietDo = doC;
                var doAm = doH;

                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        console.log(this.responseText);
                    }
                };
                xhttp.open("POST", "update_data.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("nhietDo=" + nhietDo + "&doAm=" + doAm);
            }

            function onMessageArrived(message) {
                console.log("onMessageArrived:" + message.payloadString);
                JSON.parse(message.payloadString)
                const data = JSON.parse(message.payloadString);
                var doC = Number.parseFloat(data["nhietDo"]).toFixed(0)
                var doH = Number.parseFloat(data["doAm"]).toFixed(0)
                const date = new Date();
                const formattedDate = `${date.getDate()}-${date.getMonth() + 1}-${date.getFullYear()}`;
                chartData.labels.push(`${formattedDate}`);
                chartData.values.push(doC);
                // Vẽ biểu đồ mới
                drawChart(chartData);
                sendDataToServer(doC, doH);
            }

            function sendMessage(stMessage) {
                const message = new Paho.MQTT.Message(stMessage);
                message.destinationName = topic;
                client.send(message);
            }
        </script>
        <script>
            var ctx = document.getElementById('myChart').getContext('2d');
            var chartData = [];

            // Sử dụng Ajax để gửi yêu cầu tới tệp PHP xử lý dữ liệu
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    chartData = JSON.parse(this.responseText);
                    drawChart(chartData);
                }
            };
            xhttp.open("GET", "get_data.php", true);
            xhttp.send();
            var myChart;

            function drawChart(data) {
                // Hủy biểu đồ cũ nếu tồn tại
                if (myChart) {
                    myChart.destroy();
                }

                // Khởi tạo biểu đồ mới
                var ctx = document.getElementById('myChart').getContext('2d');
                myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Độ C',
                            data: data.values,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        </script>
    </div>
    <script src="../js/main.js"></script>
</body>

</html>