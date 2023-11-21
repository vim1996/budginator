<?php

$hostname = "mariadb.c0gy0cx8qq3z.eu-north-1.rds.amazonaws.com"; // Use "localhost" if connecting from the same machine
$username = "admin"; // Replace with your MySQL username
$password = "cEQRCSytUb9N1Ua6fsjS"; // Replace with your MySQL password
$database = "budget"; // Replace with your database name
/*
$hostname = "localhost"; // Use "localhost" if connecting from the same machine
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$database = "budget"; // Replace with your database name
*/
// Create a connection
$con = mysqli_connect($hostname, $username, $password, $database);
mysqli_set_charset($con, "utf8mb4");
// Check connection
if (!$con) {
    die("Connection failed");
}

?>
