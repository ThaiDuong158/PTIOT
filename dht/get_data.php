<?php
include "../TrangMau/connSql.php";
$data = array('labels' => array(), 'values' => array());

$sql = "SELECT *, DATE_FORMAT(thoiGian, '%d-%m %H:%i') AS formatted_time 
            FROM dht
            WHERE `idThietBi` = '1'";
$result = $conn->query($sql);

// Xử lý dữ liệu trả về từ truy vấn
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data['labels'][0][] = $row['formatted_time'];
        $data['labels'][1][] = $row['formatted_time'];
        $data['values'][0][] = $row['nhietDo'];
        $data['values'][1][] = $row['doAm'];
    }
} else {
    $data['values'][0][] = 0;
    $data['values'][1][] = 0;
    $data['labels'][0][] = 0;
    $data['labels'][1][] = 0;
}

$sql = "SELECT *, DATE_FORMAT(thoiGian, '%d-%m %H:%i') AS formatted_time 
            FROM dht
            WHERE `idThietBi` = '2'";
$result = $conn->query($sql);

// Xử lý dữ liệu trả về từ truy vấn
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data['labels'][2][] = $row['formatted_time'];
        $data['labels'][3][] = $row['formatted_time'];
        $data['values'][2][] = $row['nhietDo'];
        $data['values'][3][] = $row['doAm'];
    }
} else {
    $data['values'][2][] = 0;
    $data['values'][3][] = 0;
    $data['labels'][2][] = 0;
    $data['labels'][3][] = 0;
}

$conn->close();
header('Content-Type: application/json');
echo json_encode($data);
?>