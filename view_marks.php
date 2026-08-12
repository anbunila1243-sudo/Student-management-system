<?php
session_start();
include 'includes/db.php';

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$result = mysqli_query($conn,"
SELECT
marks.id,
marks.student_id,
students.name,
students.reg_no,
marks.tamil,
marks.english,
marks.maths,
marks.science,
marks.social,
marks.computer,
marks.total,
marks.average,
marks.grade,
marks.result
FROM marks
INNER JOIN students
ON marks.student_id = students.id
ORDER BY students.reg_no ASC
");

$ranks = [];

$rankQuery = mysqli_query($conn,"
SELECT student_id,total
FROM marks
WHERE result='PASS'
ORDER BY total DESC
");

$rank = 1;
$position = 1;
$previousTotal = null;

while($r = mysqli_fetch_assoc($rankQuery)){

    if($previousTotal !== null && $r['total'] < $previousTotal){
        $rank = $position;
    }

    $ranks[$r['student_id']] = $rank;

    $previousTotal = $r['total'];
    $position++;
}
?>

<!DOCTYPE html>
<html>
<head>

<title>View Marks</title>

<link rel="stylesheet" href="css/view_marks.css">
<link rel="stylesheet" href="css/dark-mode.css">
<script src="js/theme.js"></script>

</head>

<body>

<button class="theme-toggle" onclick="toggleTheme()">
🌙 / ☀ Theme
</button>

<div class="container">

<h2>Student Marks</h2>

<table>

<tr>

<th>S.No</th>
<th>Register No</th>
<th>Student</th>
<th>Tamil</th>
<th>English</th>
<th>Maths</th>
<th>Science</th>
<th>Social</th>
<th>Computer</th>
<th>Total</th>
<th>Average</th>
<th>Grade</th>
<th>Result</th>
<th>Rank</th>
<th>Action</th>

</tr>

<?php

$serial=1;

while($row=mysqli_fetch_assoc($result)){

?>

<tr>

<td><?php echo $serial++; ?></td>

<td><?php echo $row['reg_no']; ?></td>

<td><?php echo $row['name']; ?></td>

<td>
<?php
$mark=$row['tamil'];

if(strtoupper($mark)=="AB"){
    echo "<span style='color:red;font-weight:bold;'>AB</span>";
}
elseif(is_numeric($mark) && $mark<45){
    echo "<span style='color:red;font-weight:bold;'>$mark</span>";
}
else{
    echo $mark;
}
?>
</td>

<td>
<?php
$mark=$row['english'];

if(strtoupper($mark)=="AB"){
    echo "<span style='color:red;font-weight:bold;'>AB</span>";
}
elseif(is_numeric($mark) && $mark<45){
    echo "<span style='color:red;font-weight:bold;'>$mark</span>";
}
else{
    echo $mark;
}
?>
</td>

<td>
<?php
$mark=$row['maths'];

if(strtoupper($mark)=="AB"){
    echo "<span style='color:red;font-weight:bold;'>AB</span>";
}
elseif(is_numeric($mark) && $mark<45){
    echo "<span style='color:red;font-weight:bold;'>$mark</span>";
}
else{
    echo $mark;
}
?>
</td>

<td>
<?php
$mark=$row['science'];

if(strtoupper($mark)=="AB"){
    echo "<span style='color:red;font-weight:bold;'>AB</span>";
}
elseif(is_numeric($mark) && $mark<45){
    echo "<span style='color:red;font-weight:bold;'>$mark</span>";
}
else{
    echo $mark;
}
?>
</td>

<td>
<?php
$mark=$row['social'];

if(strtoupper($mark)=="AB"){
    echo "<span style='color:red;font-weight:bold;'>AB</span>";
}
elseif(is_numeric($mark) && $mark<45){
    echo "<span style='color:red;font-weight:bold;'>$mark</span>";
}
else{
    echo $mark;
}
?>
</td>

<td>
<?php
$mark=$row['computer'];

if(strtoupper($mark)=="AB"){
    echo "<span style='color:red;font-weight:bold;'>AB</span>";
}
elseif(is_numeric($mark) && $mark<45){
    echo "<span style='color:red;font-weight:bold;'>$mark</span>";
}
else{
    echo $mark;
}
?>
</td>

<td><b><?php echo $row['total']; ?></b></td>

<td><?php echo $row['average']; ?></td>

<td>

<?php

if($row['result']=="FAIL"){

    echo "<span style='color:red;font-weight:bold;'>-</span>";

}else{

    $grade = $row['grade'];

    if($grade=="O"){
        echo "<span style='color:#16a34a;font-weight:bold;'>O</span>";
    }
    elseif($grade=="A+"){
        echo "<span style='color:#2563eb;font-weight:bold;'>A+</span>";
    }
    elseif($grade=="A"){
        echo "<span style='color:#0891b2;font-weight:bold;'>A</span>";
    }
    elseif($grade=="B+"){
        echo "<span style='color:#ca8a04;font-weight:bold;'>B+</span>";
    }
    elseif($grade=="B"){
        echo "<span style='color:#ea580c;font-weight:bold;'>B</span>";
    }
    else{
        echo "<span style='color:#dc2626;font-weight:bold;'>C</span>";
    }

}

?>

</td>

<td>

<?php

if($row['result']=="PASS"){
    echo "<span style='color:green;font-weight:bold;'>PASS</span>";
}else{
    echo "<span style='color:red;font-weight:bold;'>FAIL</span>";
}

?>

</td>

<td>

<?php

if($row['result']=="FAIL"){

    echo "<span style='color:red;font-weight:bold;'>Not Eligible</span>";

}else{

    $rank = $ranks[$row['student_id']] ?? "-";

    if($rank==1){
        echo "🥇 ".$rank;
    }
    elseif($rank==2){
        echo "🥈 ".$rank;
    }
    elseif($rank==3){
        echo "🥉 ".$rank;
    }
    else{
        echo $rank;
    }

}

?>

</td>

<td class="action-buttons">

<a href="edit_marks.php?id=<?php echo $row['id']; ?>" class="edit-btn">
✏ Edit
</a>

<a href="marksheet.php?id=<?php echo $row['id']; ?>" class="view-btn">
📄 Marksheet
</a>

<a href="delete_marks.php?id=<?php echo $row['id']; ?>"
class="delete-btn"
onclick="return confirm('Delete this mark?')">
🗑 Delete
</a>

</td>

</tr>

<?php
}
?>

</table>

<br>

<a href="dashboard.php" class="back-btn">
⬅ Back to Dashboard
</a>

</div>

</body>
</html>