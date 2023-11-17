<?php
$hostname = "localhost"; // Use "localhost" if connecting from the same machine
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$database = "budget"; // Replace with your database name

// Create a connection
$con = mysqli_connect($hostname, $username, $password, $database);

// Check connection
if (!$con) {
    die("Connection failed");
}

?>