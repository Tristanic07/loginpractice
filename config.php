<?php

$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$database = "practice";

$conn = new mysqli($servername, $dbusername, $dbpassword, $database);

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
};
