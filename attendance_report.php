<?php
session_start();
include 'includes/db.php';

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

$month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');

$sql = "
SELECT
s.id,
s.reg_no,
s.name,

SUM(CASE WHEN a.status='Present' THEN 1 ELSE 0 END) AS present,

SUM(CASE WHEN a.status='Absent' THEN 1 ELSE 0 END) AS absent,

COUNT(a.id) AS total_days

FROM students s

LEFT JOIN attendance a
ON s.id=a.student_id
AND DATE_FORMAT(a.attendance_date,'%Y-%m')='$month'

GROUP BY s.id
ORDER BY s.reg_no ASC
";

$result = mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html>

<head>

<title>Monthly Attendance Report</title>

<link rel="stylesheet" href="css/dark-mode.css">
<script src="js/theme.js"></script>

<link rel="stylesheet" href="css/attendance_report.css">

</head>

<body>

<button class="theme-toggle" onclick="toggleTheme()">
🌙 / ☀ Theme
</button>

<div class="container">

<h2>Monthly Attendance Report</h2>

<form method="GET">

<label>Select Month</label>

<input
type="month"
name="month"
value="<?php echo $month; ?>">

<button type="submit">
Search
</button>

<a href="attendance_pdf.php?month=<?php echo $month; ?>" class="print-btn">
📄 Download PDF
</a>

</form>

<table>

<tr>

<th>S.No</th>
<th>Register No</th>
<th>Student Name</th>
<th>Present</th>
<th>Absent</th>
<th>Attendance %</th>
<th>Student Sign</th>

</tr>

<?php

$i=1;

while($row=mysqli_fetch_assoc($result)){

$percent=0;

if($row['total_days']>0){
$percent=round(($row['present']/$row['total_days'])*100,2);
}
?>

<tr>

<td><?php echo $i++; ?></td>

<td><?php echo $row['reg_no']; ?></td>

<td><?php echo $row['name']; ?></td>

<td style="color:green;font-weight:bold;">
<?php echo $row['present']; ?>
</td>

<td style="color:red;font-weight:bold;">
<?php echo $row['absent']; ?>
</td>

<td>

<?php

if($percent>=75){

echo "<span style='color:green;font-weight:bold;'>$percent%</span>";

}else{

echo "<span style='color:red;font-weight:bold;'>$percent%</span>";

}

?>

</td>

<td></td>

</tr>

<?php } ?>

</table>
<br><br>

<div class="signature-section">

<div class="sign">
_________________________<br>
<b>Class Advisor</b>
</div>

<div class="sign">
_________________________<br>
<b>HOD</b>
</div>

<div class="sign">
_________________________<br>
<b>Principal</b>
</div>

</div>

<br>

<a href="dashboard.php" class="back-btn">
⬅ Back to Dashboard
</a>

</div>

</body>
</html>o