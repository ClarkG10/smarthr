<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "smart_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("DATABASE CONNECTION FAILED" . $conn->connect_error);
}
