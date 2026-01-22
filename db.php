<?php
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db = "adoption_center";
$port = 3306;
$conn = new mysqli($host, $user, $pass, $db, $port);

$conn->options(MYSQLI_OPT_LOCAL_INFILE, true);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
