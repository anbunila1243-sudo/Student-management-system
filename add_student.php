<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['save'])) { 
    $photo = $_FILES['photo']['name'];
$tmp = $_FILES['photo']['tmp_name'];

if($photo != ""){
    move_uploaded_file($tmp, "images/".$photo);
}

    $reg_no = $_POST['reg_no'];
    $name = $_POST['name'];
    $department = $_POST['department'];
    $year = $_POST['year'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

   $sql = "INSERT INTO students(reg_no,name,department,year,email,phone,photo)
VALUES('$reg_no','$name','$department','$year','$email','$phone','$photo')";

    if(mysqli_query($conn,$sql)){
        echo "<script>alert('Student Added Successfully');</script>";
    }else{
        echo "<script>alert('Error');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>

    <link rel="stylesheet" href="css/dark-mode.css">
<script src="js/theme.js"></script>

   <link rel="stylesheet" href="css/add_student.css">
</head>
<body>
    <button class="theme-toggle" onclick="toggleTheme()">
    🌙 / ☀ Theme
</button>

<div class="container">

<div class="form-box">

<h2>Add Student</h2>

<form method="POST" enctype="multipart/form-data">

<input type="text" name="reg_no" placeholder="Register Number" required>

<input type="text" name="name" placeholder="Student Name" required>

<input type="text" name="department" placeholder="Department" required>

<input type="text" name="year" placeholder="Year" required>

<input type="email" name="email" placeholder="Email">

<input type="text" name="phone" placeholder="Phone">

<label>Student Photo</label>

<input type="file" name="photo">

<button type="submit" name="save">Save Student</button>
<br><br>

<a href="dashboard.php" class="back-btn">⬅ Back to Dashboard</a>

</form>

</div>

</div>
<br>


</body>
</html>