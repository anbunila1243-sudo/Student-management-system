<?php
session_start();

session_unset();
session_destroy();

// Login page redirect
header("Location: login.php");
exit();
?>