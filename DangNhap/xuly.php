<?php

// Xác định thông tin kết nối cơ sở dữ liệu
$server = "localhost";
$username = "root";
$password = "";
$dbname = "ptiot";

// Khởi tạo kết nối PDO
$pdo = new PDO("mysql:host=$server;dbname=$dbname", $username, $password);

// Xử lý dữ liệu đăng nhập
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Lấy dữ liệu đăng nhập từ form
  $username = $_POST['user'];
  $password = $_POST['pass'];

  // Chuẩn bị truy vấn SQL
  $stmt = $pdo->prepare("SELECT * FROM `taikhoan` WHERE `tenTaiKhoan` = :username AND `matKhau` = :pass");
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':pass', $password);

  // Thực thi truy vấn
  $stmt->execute();

  // Lấy thông tin tài khoản
  $user = $stmt->fetch();
  $stmt = $pdo->prepare("SELECT * FROM `taikhoan` WHERE `tenTaiKhoan` = :username AND `matKhau` = :pass");
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':pass', $password);

  // Thực thi truy vấn
  $stmt->execute();
  // Kiểm tra mật khẩu
  $count = $stmt->fetchColumn();
  try {
    if ($count > 0) {
      if (password_verify($password, password_hash($user['matKhau'], PASSWORD_BCRYPT))) {
        $cookie_name = "user";
        $cookie_value = $user['mssv'];
        setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
        header("Location: ../TrangMau/index.php");
      } else {
        // Xác thực thất bại
        // Hiển thị thông báo lỗi
        echo "<div class='alert alert-danger'>Tên đăng nhập hoặc mật khẩu không chính xác!</div>";
      }
    } else {
      echo "<div class='alert alert-danger'>không tìm thấy tài khoản!</div></br>";
      echo "<a href=dangky.php>Nhấn vào để đăng ký</a>";
    }
  } catch (Exception $e) {
    echo $e;
  }
}
