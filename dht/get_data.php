<?php
    include "../TrangMau/connSql.php";
    $sql = "SELECT * FROM dht";
    $result = $conn->query($sql);

    $data = array('labels' => array(), 'values' => array());

    // Xử lý dữ liệu trả về từ truy vấn
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data['labels'][] = $row['thoiGian']; // Thay label_column bằng tên cột chứa nhãn cho trục x
            $data['values'][] = $row['nhietDo']; // Thay value_column bằng tên cột chứa giá trị dữ liệu
        }
    } else {
        echo "0 results";
    }
    $conn->close();
    header('Content-Type: application/json');
    echo json_encode($data);
?>