<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "student_management";

$conn = mysqli_connect($host, $user, $password, $database);
if (!$conn) {
    die("Error No: " . mysqli_connect_errno() . "<br>" .
        mysqli_connect_error());
}

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}
?>
