<?php
session_start();

$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'db_absen_jumat';

$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama = $_POST['nama'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE nama = '$nama'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if ($password == $row['password']) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['nama'];
            $_SESSION['rayon'] = $row['rayon'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Password tidak valid";
        }
    } else {
        $error = "Nama tidak valid";
    }
}

$conn->close();
?>
  <!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="globals.css" />
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <div class="login-page">
      <div class="div">
        <div class="overlap">
          <div class="rectangle"></div>
          <div class="object-other"></div>
        </div>
        <div class="text-wrapper-3">Welcome Back!</div>
        <form class="space-y-6" action="" method="POST">

        <input id="password" class="rectangle-3" name="password" type="password" required>
            <div class="text-wrapper">
              Username:
            </div>
                <input id="name" class="rectangle-2" name="nama" type="text" autocomplete="email" required>
        <div class="group">
          <div class="overlap-group">
            <div class="text-wrapper-4">
            <button type="submit" class="btn">Sign in</button>
            </div>
          </div>
          <?php if (isset($error)) { echo $error; }?>
        </div>
        <div class="text-wrapper-5">Password:</div>
        <div class="overlap-2">
          <div class="rectangle-4"></div>
          <div class="rectangle-5"></div>
        </div>
      </div>
    </div>
  </body>
</html>

</body>
</html>
