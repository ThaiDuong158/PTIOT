<?php
    // Kết nối đến cơ sở dữ liệu
    include "../TrangMau/connSql.php";

    // Lấy dữ liệu từ yêu cầu Ajax
    $nhietDo = $_POST['nhietDo'];
    $doAm = $_POST['doAm'];

    // Chuẩn bị câu lệnh SQL
    $sql = "INSERT INTO `dht` (`idThietBi`, `nhietDo`, `doAm`, `thoiGian`) 
            VALUES ('1', ?, ?, current_timestamp())";

    // Thực thi câu lệnh SQL sử dụng PDO
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $nhietDo, $doAm);

    // Kiểm tra và thực thi câu lệnh SQL
    if ($stmt->execute()) {
        echo "Dữ liệu đã được thêm vào cơ sở dữ liệu thành công.";
    } else {
        echo "Lỗi: " . $sql . "<br>" . $conn->error;
    }

    // Đóng kết nối cơ sở dữ liệu
    $conn = null;
?>
