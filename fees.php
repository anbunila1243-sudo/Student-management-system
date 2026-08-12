<?php
session_start();
include 'includes/db.php';

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

if(isset($_POST['save'])){

    $student_id = $_POST['student_id'];
    $total_fee = $_POST['total_fee'];
    $paid_fee = $_POST['paid_fee'];
    $balance_fee = $total_fee - $paid_fee;
    $payment_date = $_POST['payment_date'];

    mysqli_query($conn,"INSERT INTO fees(student_id,total_fee,paid_fee,balance_fee,payment_date)
    VALUES('$student_id','$total_fee','$paid_fee','$balance_fee','$payment_date')");

    echo "<script>alert('Fees Saved Successfully');</script>";
}

$students = mysqli_query($conn,"SELECT * FROM students");
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/dark-mode.css">
<script src="js/theme.js"></script>
<title>Add Fees</title>

<style>
body{
    font-family:Arial;
    background:#f4f6f9;
    padding:36px;
}
form{
    width:450px;
    background:white;
    padding:25px;
    border-radius:10px;
}
input,select{
    width:100%;
    padding:12px;
    margin:10px 0;
}
button{
    width:100%;
    padding:10px;
    background:#2563eb;
    color:white;
    border:none;
    cursor:pointer;
}

.back-btn{
    display:inline-block;
    background:#2563eb;
    color:white;
    padding:10px 18px;
    text-decoration:none;
    border-radius:8px;
    margin-bottom:20px;
}

.back-btn:hover{
    background:#1d4ed8;
}
</style>
</head>

<body>

    <center>

<h2>Student Fees</h2>

<form method="POST">

<select name="student_id" required>

<option value="">Select Student</option>

<?php while($row=mysqli_fetch_assoc($students)){ ?>

<option value="<?php echo $row['id']; ?>">
<?php echo $row['name']; ?>
</option>

<?php } ?>

</select>

<input type="number" name="total_fee" placeholder="Total Fee" required>

<input type="number" name="paid_fee" placeholder="Paid Fee" required>

<input type="date" name="payment_date" required>

<button type="submit" name="save">Save Fees</button>
<br>
</form>
<a href="dashboard.php" class="back-btn">⬅ Back to Dashboard</a><br><br>
</center>

</body>
</html>