<?php
// Kết nối đến cơ sở dữ liệu
include "../TrangMau/connSql.php";

// Lấy dữ liệu từ yêu cầu Ajax
$nhietDo_TT = $_POST['nhietDo_TT'];
$doAm_TT = $_POST['doAm_TT'];
$nhietDo_CG = $_POST['nhietDo_CG'];
$doAm_CG = $_POST['doAm_CG'];

// Hàm để chèn dữ liệu vào cơ sở dữ liệu
function insertData($conn, $idThietBi, $nhietDo, $doAm) {
    $sql = "INSERT INTO `dht` (`idThietBi`, `nhietDo`, `doAm`, `thoiGian`) 
            VALUES (?, ?, ?, current_timestamp())";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iss', $idThietBi, $nhietDo, $doAm);

    if ($stmt->execute()) {
        echo "Dữ liệu đã được thêm vào cơ sở dữ liệu thành công.";
    } else {
        echo "Lỗi: " . $sql . "<br>" . $conn->error;
    }
}

// Chèn dữ liệu cho thiết bị 1
insertData($conn, 1, $nhietDo_TT, $doAm_TT);

// Chèn dữ liệu cho thiết bị 2
insertData($conn, 2, $nhietDo_CG, $doAm_CG);

// Đóng kết nối cơ sở dữ liệu
$conn = null;
?>
