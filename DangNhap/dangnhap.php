<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng Nhập</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <div id="wrapper">
    <div class="container">
      <div class="row justify-content-around">
        <form action="xuly.php" method="post" class="col-md-6 bg-light p-3 my-3">
          <h1 class="text-center text-uppercase h3 py-3">Đăng Nhập</h1>
          <div class="form-group">
            <label for="username" class="text-uppercase">Tên đăng nhập</label>
            <input type="text" name="user" id="username" class="form-control my-2" required>
          </div>
          <div class="form-group">
            <label for="password" class="text-uppercase">Mật khẩu</label>
            <input type="password" id="password" name="pass" class="form-control my-2" required>
          </div>
          <input type="submit" value="Đăng Nhập" class="btn-primary btn-block btn text-uppercase">
        </form>
      </div>
    </div>
  </div>
</body>

</html>
