<?php
include "../TrangMau/connSql.php";
$sql = "SELECT *, DATE_FORMAT(thoiGian, '%d-%m %H:%i') AS formatted_time 
            FROM dht";
$result = $conn->query($sql);

$data = array('labels' => array(), 'values' => array());

// Xử lý dữ liệu trả về từ truy vấn
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data['labels'][] = $row['formatted_time'];
        $data['values'][0][] = $row['nhietDo'];
        $data['values'][1][] = $row['doAm'];
    }
} else {
    $data['labels'][] = 0;
    $data['values'][0][] = 0;
    $data['values'][1][] = 0;
}
$conn->close();
header('Content-Type: application/json');
echo json_encode($data);
?>