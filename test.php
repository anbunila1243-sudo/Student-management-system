<?php
$conn = mysqli_connect("localhost","root","","student_management");

if($conn){
    echo "Database Connected";
}else{
    echo mysqli_connect_error();
}
?>