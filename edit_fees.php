<?php
session_start();
include 'includes/db.php';

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

$result = mysqli_query($conn,"SELECT * FROM fees WHERE id='$id'");
$row = mysqli_fetch_assoc($result);

if(isset($_POST['update'])){

    $total_fee = $_POST['total_fee'];
    $paid_fee = $_POST['paid_fee'];
    $balance_fee = $total_fee - $paid_fee;
    $payment_date = $_POST['payment_date'];

    mysqli_query($conn,"
    UPDATE fees
    SET
        total_fee='$total_fee',
        paid_fee='$paid_fee',
        balance_fee='$balance_fee',
        payment_date='$payment_date'
    WHERE id='$id'
    ");

    header("Location:view_fees.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Edit Fees</title>
<link rel="stylesheet" href="css/dark-mode.css">
<script src="js/theme.js"></script>
<link rel="stylesheet" href="css/edit_fees.css">

</head>

<body>
<button class="theme-toggle" onclick="toggleTheme()">
    🌙 / ☀ Theme
</button>

<div class="container">

<div class="form-box">

<h2>Edit Fees</h2>

<form method="POST">

<label>Total Fee</label>

<input
type="number"
name="total_fee"
value="<?php echo $row['total_fee']; ?>"
required>

<label>Paid Fee</label>

<input
type="number"
name="paid_fee"
value="<?php echo $row['paid_fee']; ?>"
required>

<label>Payment Date</label>

<input
type="date"
name="payment_date"
value="<?php echo $row['payment_date']; ?>"
required>

<div class="btn-group">

<button
type="submit"
name="update"
class="update-btn">
Update Fees
</button>

<button
type="reset"
class="reset-btn">
Reset
</button>

</div>

<a href="view_fees.php" class="back-btn">
⬅ Back to View Fees
</a>

</form>

</div>

</div>

</body>
</html>