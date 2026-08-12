<?php
include 'includes/db.php';

$id = $_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM students WHERE id='$id'");
$row = mysqli_fetch_assoc($result);

if(isset($_POST['update'])){

    $reg_no = $_POST['reg_no'];
    $name = $_POST['name'];
    $department = $_POST['department'];
    $year = $_POST['year'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // photo //
    $photo = $row['photo'];

    // upload//
    if($_FILES['photo']['name'] != ""){
        $photo = $_FILES['photo']['name'];
        move_uploaded_file($_FILES['photo']['tmp_name'], "images/".$photo);
    }

    mysqli_query($conn,"UPDATE students SET
    reg_no='$reg_no',
    name='$name',
    department='$department',
    year='$year',
    email='$email',
    phone='$phone',
    photo='$photo'
    WHERE id='$id'");

    header("Location:view_students.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Edit Student</title>

<link rel="stylesheet" href="css/dark-mode.css">
<script src="js/theme.js"></script>

<link rel="stylesheet" href="css/edit_student.css">

</head>

<body>
<button class="theme-toggle" onclick="toggleTheme()">
    🌙 / ☀ Theme
</button>

<div class="container">
    <div class="form-box">

    <a href="view_students.php" class="back-btn">⬅ Back</a>

<h2>Edit Student</h2>

<form method="POST" enctype="multipart/form-data">

<label>Register Number</label><br>
<input type="text" name="reg_no" value="<?php echo $row['reg_no']; ?>"><br><br>

<label>Name</label><br>
<input type="text" name="name" value="<?php echo $row['name']; ?>"><br><br>

<label>Department</label><br>
<input type="text" name="department" value="<?php echo $row['department']; ?>"><br><br>

<label>Year</label><br>
<input type="text" name="year" value="<?php echo $row['year']; ?>"><br><br>

<label>Email</label><br>
<input type="email" name="email" value="<?php echo $row['email']; ?>"><br><br>

<label>Phone</label><br>
<input type="text" name="phone" value="<?php echo $row['phone']; ?>"><br><br>

<label>Current Photo</label><br>

<?php if($row['photo'] != ""){ ?>
    <img src="images/<?php echo $row['photo']; ?>" width="80"><br><br>
<?php } ?>

<label>New Photo</label><br>

<input type="file" name="photo"><br><br>

<div class="btn-group">

<button type="submit" name="update" class="update-btn">Update Student</button>

<button type="reset" class="reset-btn">Reset</button>

</div>

</form>

</div>

</div>

</body>
</html>