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
                <div class="content row container-fluid">
                    <div class="content--thoiTiet content--region">
                        <h1 class="content--title">Điều khiển nhiệt độ motor</h1>
                        <div class="row d-inline-flex container-fluid flex-wrap">
                            <div class="content--item col-6 container-fluid">
                                <!-- <span class="content--item">Nhiệt độ: <p id="nhietDo">0 độ C</p></span> -->
                                <canvas id="myChart" class="myChart container-fluid"></canvas>
                                <h5 class="chart--title">Biểu đồ hiển thị nhiệt độ</h5>
                            </div>
                            <div class="content--item col-6 container-fluid">
                                <!-- <span class="content--item">Độ ẩm: <p id="doAm">0 %</p></span> -->
                                <canvas id="myChart2" class="myChart container-fluid"></canvas>
                                <h5 class="chart--title">Biểu đồ hiển thị độ ẩm</h5>
                            </div>
                        </div>
                        <h5>Nhập ngưỡng nhiệt độ để bật motor <input type="number" class="input__nguong" name="nhietDoThoiThiet" min="0" max="100" id=""> độ C </h5>
                    </div>
                    <div class="content--chuongGa content--region">
                        <h1 class="content--title">Điều khiển nhiệt độ chuồng gà</h1>
                        <div class="row d-inline-flex container-fluid flex-wrap">
                            <div class="content--item col-6 container-fluid">
                                <!-- <span class="content--item">Nhiệt độ: <p id="nhietDo">0 độ C</p></span> -->
                                <canvas id="myChart3" class="myChart container-fluid"></canvas>
                                <h5 class="chart--title">Biểu đồ hiển thị nhiệt độ</h5>
                            </div>
                            <div class="content--item col-6 container-fluid">
                                <!-- <span class="content--item">Độ ẩm: <p id="doAm">0 %</p></span> -->
                                <canvas id="myChart4" class="myChart container-fluid"></canvas>
                                <h5 class="chart--title">Biểu đồ hiển thị độ ẩm</h5>
                            </div>
                        </div>
                        <h5>Nhập ngưỡng nhiệt độ để thông báo <input type="number" class="input__nguong" name="nhietDoChuongGa" min="0" max="100" id=""> độ C </h5>
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

            function sendDataToServer(doC_TT, doH_TT, doC_CG, doH_CG) {
                var nhietDo_TT = doC_TT;
                var doAm_TT = doH_TT;
                var nhietDo_CG = doC_CG;
                var doAm_CG = doH_CG;

                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        console.log(this.responseText);
                    }
                };
                xhttp.open("POST", "update_data.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("nhietDo_TT=" + nhietDo_TT + "&doAm_TT=" + doAm_TT + "&nhietDo_CG=" + nhietDo_CG + "&doAm_CG=" + doAm_CG);
            }

            function onMessageArrived(message) {
                console.log("onMessageArrived:" + message.payloadString);
                JSON.parse(message.payloadString)
                const data = JSON.parse(message.payloadString);
                var doC_TT = Number.parseFloat(data["nhietDoThoiTiet"]).toFixed(0)
                var doH_TT = Number.parseFloat(data["doAmThoiTiet"]).toFixed(0)
                var doC_CG = Number.parseFloat(data["nhietDoChuongGa"]).toFixed(0)
                var doH_CG = Number.parseFloat(data["doAmChuongGa"]).toFixed(0)
                const formattedDate = formatDate();
                chartData.labels.push(`${formattedDate}`);
                chartData.values[0].push(doC_TT);
                chartData.values[1].push(doH_TT);
                chartData.values[2].push(doC_CG);
                chartData.values[3].push(doH_CG);
                console.log(chartData);
                // Vẽ biểu đồ mới
                drawCharts(chartData);
                sendDataToServer(doC_TT, doH_TT, doC_CG, doH_CG);
            }

            function formatDate() {
                const date = new Date();
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const hours = String(date.getHours()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');
                return `${day}-${month} ${hours}:${minutes}`;
            }

            function sendMessage(stMessage) {
                const message = new Paho.MQTT.Message(stMessage);
                message.destinationName = topic;
                client.send(message);
            }
        </script>
        <script>
            var chartData = [];

            // Sử dụng Ajax để gửi yêu cầu tới tệp PHP xử lý dữ liệu
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    chartData = JSON.parse(this.responseText);
                    drawCharts(chartData);
                }
            };
            xhttp.open("GET", "get_data.php", true);
            xhttp.send();
            var myCharts = [];
            function drawCharts(data) {
                var ctxs = document.querySelectorAll(".myChart");
                ctxs.forEach((ctx, i) => {
                    if (myCharts[i]) {
                        myCharts[i].destroy();
                    }
                    ctx = ctx.getContext('2d');
                    myCharts[i] = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.labels[i],
                            datasets: [{
                                label: 'Độ C',
                                data: data.values[i],
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
                });
            }


        </script>
    </div>
    <script src="../js/main.js"></script>
</body>

</html>