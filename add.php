<?php
session_start();

$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'db_absen_jumat';

$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("INSERT INTO rekap (nama, nis, rayon, rombel, status, tanggal) VALUES (?, ?, ?, ?, ?, NOW())");

    if ($stmt) {
        echo "Statement is valid.<br>";
        foreach ($_POST['status'] as $nis => $statusValue) {
            if (
                isset($_POST['nama'][$nis], $_POST['nis'][$nis], $_POST['rayon'][$nis], $_POST['rombel'][$nis])
            ) {
                $nama = htmlspecialchars($_POST['nama'][$nis]);
                $nis = intval($_POST['nis'][$nis]);
                $rayon = htmlspecialchars($_POST['rayon'][$nis]);
                $rombel = htmlspecialchars($_POST['rombel'][$nis]);

                $stmt->bind_param("sisss", $nama, $nis, $rayon, $rombel, $statusValue);

                if ($stmt->execute()) {
                    echo "Data inserted successfully for NIS: $nis<br>";
                } else {
                    echo "Error: " . $stmt->error . " for NIS: $nis<br>";
                }
            } else {
                echo "Missing data for NIS: $nis<br>";
            }
        }
        $stmt->close();
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();

