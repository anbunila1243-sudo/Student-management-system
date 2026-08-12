<?php
session_start();
include 'includes/db.php';

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

$where = "";

if(isset($_GET['date']) && $_GET['date']!=""){
    $date = $_GET['date'];
    $where = "WHERE attendance.attendance_date='$date'";
}

if(isset($_GET['month']) && $_GET['month']!=""){
    $month = $_GET['month'];
    $where = "WHERE DATE_FORMAT(attendance.attendance_date,'%Y-%m')='$month'";
}

$presentSql = "
SELECT COUNT(*) AS total
FROM attendance
$where
";

if($where == ""){
    $presentSql = "
    SELECT COUNT(*) AS total
    FROM attendance
    WHERE status='Present'
    ";
}else{
    $presentSql = "
    SELECT COUNT(*) AS total
    FROM attendance
    $where AND status='Present'
    ";
}

$presentQuery = mysqli_query($conn,$presentSql);
$presentCount = mysqli_fetch_assoc($presentQuery)['total'];

// Absent Count
$absentSql = "
SELECT COUNT(*) AS total
FROM attendance
WHERE status='Absent'
";

if($where != ""){
    $absentSql = "
    SELECT COUNT(*) AS total
    FROM attendance
    $where AND status='Absent'
    ";
}

$absentQuery = mysqli_query($conn,$absentSql);
$absentCount = mysqli_fetch_assoc($absentQuery)['total'];

// Attendance Percentage

$totalAttendance = $presentCount + $absentCount;

$attendancePercent = 0;

if($totalAttendance > 0){
    $attendancePercent = round(($presentCount/$totalAttendance)*100);
}

$result = mysqli_query($conn,"
SELECT attendance.*, students.name, students.reg_no
FROM attendance
INNER JOIN students
ON attendance.student_id = students.id
$where
ORDER BY attendance.attendance_date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>View Attendance</title>
<link rel="stylesheet" href="css/dark-mode.css">
<script src="js/theme.js"></script>
<link rel="stylesheet" href="css/view_attendance.css">
</head>

<body>
    <button class="theme-toggle" onclick="toggleTheme()">
    🌙 / ☀ Theme
</button>

<div class="container">

<h2>📋 View Attendance</h2>

<div class="cards">

<div class="card green">
<h3>✅ Present</h3>
<p><?php echo $presentCount; ?></p>
</div>

<div class="card red">
<h3>❌ Absent</h3>
<p><?php echo $absentCount; ?></p>
</div>

<div class="card blue">
<h3>📊 Attendance %</h3>
<p><?php echo $attendancePercent; ?>%</p>
</div>

</div>

<form method="GET">

<label>Select Date</label>
<input type="date" name="date">

<label>Select Month</label>
<input type="month" name="month">

<button type="submit">Search</button>

</form>

<table>

<tr>
<th>Date</th>
<th>Student</th>
<th>Register No</th>
<th>Status</th>
</tr>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo $row['attendance_date']; ?></td>

<td><?php echo $row['name']; ?></td>

<td><?php echo $row['reg_no']; ?></td>

<td>

<?php
if($row['status']=="Present"){
    echo "<span class='present'>Present</span>";
}else{
    echo "<span class='absent'>Absent</span>";
}
?>

</td>

</tr>

<?php } ?>

</table>

<br>



<a href="dashboard.php" class="back-btn">
⬅ Back to Dashboard
</a>

</div>

</body>
</html>