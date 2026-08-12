<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>
<link rel="stylesheet" href="css/dashboard.css">
<link rel="stylesheet" href="css/dark-mode.css">
<script src="js/theme.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>

<button class="theme-toggle" onclick="toggleTheme()">
    🌙 / ☀ Theme
</button>

<?php include 'includes/sidebar.php'; ?>

<div class="content">

<div class="dashboard-header">

    <div>
        <h1>Welcome, <?php echo $_SESSION['admin']; ?></h1>
        <p><b>Student Management System</b></p>
    </div>

    <div class="live-time">
        <h2 id="clock"></h2>
        <span id="date"></span>
    </div>

</div>

<?php

include 'includes/db.php';
// Total Students
$countQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM students");
$countResult = mysqli_fetch_assoc($countQuery);
$totalStudents = $countResult['total'];

// Paid Fees
$paidQuery = mysqli_query($conn, "SELECT SUM(paid_fee) AS paid FROM fees");
$paidResult = mysqli_fetch_assoc($paidQuery);
$totalPaid = $paidResult['paid'] ?? 0;

// Total Fees
$totalQuery = mysqli_query($conn, "SELECT SUM(total_fee) AS total FROM fees");
$totalResult = mysqli_fetch_assoc($totalQuery);
$totalFees = $totalResult['total'] ?? 0;

// Due Fees
$dueQuery = mysqli_query($conn, "SELECT SUM(balance_fee) AS due FROM fees");
$dueResult = mysqli_fetch_assoc($dueQuery);
$dueFees = $dueResult['due'] ?? 0;

// Attendance Percentage
$attendanceQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM attendance WHERE status='Present'");
$attendanceResult = mysqli_fetch_assoc($attendanceQuery);
$totalPresent = $attendanceResult['total'];

$totalQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM attendance");
$total = mysqli_fetch_assoc($totalQuery)['total'];

$today = date("Y-m-d");

// Today Present
$todayPresent = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM attendance
WHERE attendance_date='$today'
AND status='Present'
"))['total'];

// Today Absent
$todayAbsent = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM attendance
WHERE attendance_date='$today'
AND status='Absent'
"))['total'];

$todayTotal = $todayPresent + $todayAbsent;

$todayPercent = 0;

if($todayTotal>0){
    $todayPercent = round(($todayPresent/$todayTotal)*100);
}

$month = date("Y-m");

// Month Present
$monthPresent = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM attendance
WHERE DATE_FORMAT(attendance_date,'%Y-%m')='$month'
AND status='Present'
"))['total'];

// Month Absent
$monthAbsent = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM attendance
WHERE DATE_FORMAT(attendance_date,'%Y-%m')='$month'
AND status='Absent'
"))['total'];

$monthTotal = $monthPresent + $monthAbsent;

$monthPercent = 0;

if($monthTotal>0){
    $monthPercent = round(($monthPresent/$monthTotal)*100);
}

$attendancePercent = 0;
if($total > 0){
    $attendancePercent = round(($totalPresent / $total) * 100);
}
?>
<div class="cards">
<div class="container">
    <div class="row">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card green" style="margin:30px;">
            <h3>Total Students</h3>
            <p><?php echo $totalStudents; ?></p>
            </div>

            <div class="card orange" style="margin:30px;">
            <h3>📅 Today Present</h3>
            <p><?php echo $todayPresent; ?></p>
            </div>

            <div class="card red" style="margin:30px;">
            <h3>❌ Today Absent</h3>
            <p><?php echo $todayAbsent; ?></p>
            </div>

            </div>
        </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-12 col-md-6 col-lg-4 ">
            <div class="card cyan" style="margin:30px;">
            <h3>📊 Today Attendance</h3>
            <p><?php echo $todayPercent; ?>%</p>
            </div>

            <div class="card teal" style="margin:30px;">
            <h3>📈 Month Attendance</h3>
            <p><?php echo $monthPercent; ?>%</p>
            </div>

            <div class="card blue" style="margin:30px; cursor:pointer;"
            onclick="window.location='attendance_report.php'">

            <h3>📋 Monthly Report</h3>

            <p>Open Report</p>

            </div>

            
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-12 col-md-6 col-lg-4 ">
            <div class="card yellow" style="margin:30px;">
            <h3>Total Fees</h3>
            <p>₹<?php echo number_format($totalFees); ?></p>
            </div>

            <div class="card dark" style="margin:30px;">
            <h3>💰 Paid Fees</h3>
            <p>₹<?php echo number_format($totalPaid); ?></p>
            </div>

            <div class="card brown" style="margin:30px;">
            <h3>📌 Due Fees</h3>
            <p>₹<?php echo number_format($dueFees); ?></p>
            </div>    
        </div>
    </div>
</div>

</div>

<script>
function updateClock(){

    let now = new Date();

    let time = now.toLocaleTimeString();

    let date = now.toDateString();

    document.getElementById("clock").innerHTML = time;

    document.getElementById("date").innerHTML = date;

}

setInterval(updateClock,1000);

updateClock();
</script>


</body>
</html>