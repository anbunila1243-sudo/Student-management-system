<?php
session_start();
include 'includes/db.php';

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

if(isset($_POST['save'])){

    $date = $_POST['attendance_date'];

    foreach($_POST['status'] as $student_id => $status){

        // Check attendance already exists
        $check = mysqli_query($conn,"
        SELECT * FROM attendance
        WHERE student_id='$student_id'
        AND attendance_date='$date'
        ");

        if(mysqli_num_rows($check)>0){

            mysqli_query($conn,"
            UPDATE attendance
            SET status='$status'
            WHERE student_id='$student_id'
            AND attendance_date='$date'
            ");

        }else{

            mysqli_query($conn,"
            INSERT INTO attendance(student_id,attendance_date,status)
            VALUES('$student_id','$date','$status')
            ");

        }

    }

    echo "<script>
    alert('Attendance Saved Successfully');
    window.location='attendance.php';
    </script>";
}

$result = mysqli_query($conn,"SELECT * FROM students");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Attendance</title>
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

        button{
            margin-top:20px;
            padding:10px 20px;
            background:#2563eb;
            color:white;
            border:none;
            cursor:pointer;
        }

        .back-btn{
            display:inline-block;
            background:#2563eb;
            color:white;
            text-decoration:none;
            padding:10px 18px;
            border-radius:8px;
            margin-bottom:20px;
}

.back-btn:hover{
    background:#1d4ed8;
}

    </style>
    <link rel="stylesheet" href="css/dark-mode.css">
<script src="js/theme.js"></script>
</head>

<body>

<button class="theme-toggle" onclick="toggleTheme()">
    🌙 / ☀ Theme
</button>

<h2>Student Attendance</h2>

<form method="POST">
    <label><b>Attendance Date</b></label>
<input type="date" name="attendance_date" value="<?php echo date('Y-m-d'); ?>" required>

<br><br>

<table>

<tr>
<th>ID</th>
<th>Name</th>
<th>Reg No</th>
<th>Present</th>
<th>Absent</th>
</tr>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo $row['id']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['reg_no']; ?></td>

<td>
<input type="radio"
name="status[<?php echo $row['id']; ?>]"
value="Present" checked>
</td>

<td>
<input type="radio"
name="status[<?php echo $row['id']; ?>]"
value="Absent">
</td>

</tr>

<?php } ?>

</table>

<button type="submit" name="save">Save Attendance</button><br><br>
<a href="dashboard.php" class="back-btn">⬅ Back to Dashboard</a><br><br>

</form>

</body>
</html>