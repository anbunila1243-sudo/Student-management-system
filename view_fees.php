<?php
session_start();
include 'includes/db.php';

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

$result = mysqli_query($conn,"
SELECT fees.id,
students.name,
students.reg_no,
fees.total_fee,
fees.paid_fee,
fees.balance_fee,
fees.payment_date
FROM fees
INNER JOIN students
ON fees.student_id = students.id
");
?>

<!DOCTYPE html>
<html>
<head>
<title>View Fees</title>
<link rel="stylesheet" href="css/dark-mode.css">
<script src="js/theme.js"></script>
<style>
body{
    font-family:Arial;
    background:#f4f6f9;
    padding:30px;
}
table{
    width:100%;
    border-collapse:collapse;
    background:white;
}
th,td{
    border:1px solid #ddd;
    padding:10px;
    text-align:center;
}
th{
    background:#2563eb;
    color:white;
}
tr:nth-child(even){
    background:#f2f2f2;
}
.back-btn{
    display:inline-block;
    background:#2563eb;
    color:#fff;
    text-decoration:none;
    padding:10px 18px;
    border-radius:8px;
    margin-bottom:20px;
}

.back-btn:hover{
    background:#1d4ed8;
}
</style>

</head>

<body>

<button class="theme-toggle" onclick="toggleTheme()">
    🌙 / ☀ Theme
</button>

<h2>Student Fees Details</h2>

<table>

<tr>
<th>ID</th>
<th>Name</th>
<th>Reg No</th>
<th>Total Fee</th>
<th>Paid Fee</th>
<th>Balance Fee</th>
<th>Payment Date</th>
<th>Action</th>
</tr>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo $row['id']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['reg_no']; ?></td>
<td>₹<?php echo $row['total_fee']; ?></td>
<td>₹<?php echo $row['paid_fee']; ?></td>
<td>₹<?php echo $row['balance_fee']; ?></td>
<td><?php echo $row['payment_date']; ?></td>

<td>
<a href="edit_fees.php?id=<?php echo $row['id']; ?>">✏ Edit</a> |
<a href="delete_fees.php?id=<?php echo $row['id']; ?>"
onclick="return confirm('Delete this fee record?');">
🗑 Delete
</a>
</td>

</tr>

<?php } ?>

</table>
<br><br>
<a href="dashboard.php" class="back-btn">⬅ Back to Dashboard</a><br><br>

</body>
</html>