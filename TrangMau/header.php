<header class="header">
    <div class="row">
        <div class="sidebar-width sidebar-transition col-2 bg-success d-flex">
            <a href="../TrangMau/index.php"
                class="container-fluid d-inline-flex justify-content-center align-items-center">
                <img src="../icon/iconVLUTE.png" class="header__icon" alt="">
                <p class="text-white sidebar-hide">VLUTE</p>
            </a>
        </div>
        <div class="col container-fluid d-inline-flex justify-content-between">
            <i class="fa-solid fa-bars sidebar-mini text-white align-content-center header__bar"></i>
            <div class="d-inline-flex no-select align-items-center text-white">

                <?php
                $cookie_name = 'user';
                if (!isset($_COOKIE[$cookie_name])) {
                    echo '
                        <a href="../DangNhap/dangnhap.php" class="header__login d-inline-flex align-items-center text-white">
                            <i class="fa-solid fa-right-to-bracket"></i>
                            <p>Đăng nhập</p>
                        </a>
                    ';
                } else {
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $db = "ptiot";
                    // Create connection
                    $conn = new mysqli($servername, $username, $password, $db);

                    // Check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }
                    $mssv = $_COOKIE[$cookie_name];
                    $sql = 'SELECT `taikhoan`.*, `quyen`.`tenQuyen`
                            FROM `taikhoan` 
                                LEFT JOIN `quyen` ON `taikhoan`.`idPhanQuyen` = `quyen`.`idQuyen`
                                WHERE `mssv` = ' . $mssv . ';';
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        // output data of each row
                        while ($row = $result->fetch_assoc()) {
                            echo '
                                <div class="header__login d-inline-flex align-items-center text-white">
                                    <img src="../icon/basicUser.jpg" class="rounded-circle" alt="Cinque Terre" width="25" height="25">
                                    <p>' . $row["tenUser"] . '</p>
                                    <div class="dropdown-list" style="width: 280px;">
                                        <div class="dropdown-info d-flex flex-column align-items-center">
                                            <img src="../icon/basicUser.jpg" class="rounded-circle" alt="" width="90" height="90">
                                            <div class="dropdown-text">
                                                <p>' . $row["tenUser"] . '</p>
                                                <p>' . $row["mssv"] . '</p>
                                                <p>' . $row["tenQuyen"] . '</p>
                                            </div>
                                        </div>
                                        <div class="dropdown-setting d-flex justify-content-between">
                                            <form method="POST" action="">
                                                <button class="dropdown-mk">Đổi mật khẩu</button>
                                            </form>
                                            <form method="POST" action="">
                                                <button type="submit" name="logout" class="dropdown-dx">Đăng xuất</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            ';
                        }
                    }
                    $conn->close();
                }
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
                    setcookie($cookie_name, "", time() - 3600, "/");
                    header("Location: ../TrangMau/index.php");
                    exit();
                }
                ?>

            </div>
        </div>
    </div>
</header>