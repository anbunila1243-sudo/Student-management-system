<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

// Child tables-ல இருக்கும் records-ஐ முதலில் delete பண்ணு
mysqli_query($conn, "DELETE FROM marks WHERE student_id='$id'");
mysqli_query($conn, "DELETE FROM attendance WHERE student_id='$id'");
mysqli_query($conn, "DELETE FROM fees WHERE student_id='$id'");

// பிறகு student record delete பண்ணு
mysqli_query($conn, "DELETE FROM students WHERE id='$id'");

header("Location: view_students.php");
exit();
?>