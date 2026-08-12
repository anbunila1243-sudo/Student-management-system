<?php
session_start();
include 'includes/db.php';

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

$result = mysqli_query($conn,"
SELECT
students.name,
students.reg_no,
students.department,
students.year,
marks.*
FROM marks
INNER JOIN students
ON marks.student_id = students.id
WHERE marks.id='$id'
");

$row = mysqli_fetch_assoc($result);

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
<title>Student Marksheet</title>
<link rel="stylesheet" href="css/dark-mode.css">
<script src="js/theme.js"></script>
<link rel="stylesheet" href="css/marksheet.css">
</head>
<body>

<button class="theme-toggle" onclick="toggleTheme()">
    🌙 / ☀ Theme
</button>

<div class="marksheet">
    <div class="header">

    <div class="logo">
        <img src="images/logo.png" alt="College Logo">
    </div>

    <div class="college-details">
        <h2>RRASE COLLEGE OF ENGINEERING</h2>
        <p>Department of Artificial Intelligence & Data Science</p>
        <h1>STUDENT MARKSHEET</h1>
    </div>

    <div class="photo">
        <img src="images/default.png" alt="Student Photo">
    </div>

</div>
    <div class="student-info">

    <div class="info-row">
        <span class="label">Name</span>
        <span class="value"><?php echo $row['name']; ?></span>
    </div>

    <div class="info-row">
        <span class="label">Register No</span>
        <span class="value"><?php echo $row['reg_no']; ?></span>
    </div>

    <div class="info-row">
        <span class="label">Department</span>
        <span class="value"><?php echo $row['department']; ?></span>
    </div>

    <div class="info-row">
        <span class="label">Year</span>
        <span class="value"><?php echo $row['year']; ?></span>
    </div>

</div>


    <table>
        <tr>
        <th>S.No</th>
        <th>Subject</th>
        <th>Mark</th>
        <th>Teacher Signature</th>
        </tr>

    <tr>
    <td>1</td>
    <td>Tamil</td>
    <td><?php echo $row['tamil']; ?></td>
    <td></td>
</tr>

<tr>
    <td>2</td>
    <td>English</td>
    <td><?php echo $row['english']; ?></td>
    <td></td>
</tr>

<tr>
    <td>3</td>
    <td>Maths</td>
    <td><?php echo $row['maths']; ?></td>
    <td></td>
</tr>

<tr>
    <td>4</td>
    <td>Science</td>
    <td><?php echo $row['science']; ?></td>
    <td></td>
</tr>

<tr>
    <td>5</td>
    <td>Social</td>
    <td><?php echo $row['social']; ?></td>
    <td></td>
</tr>

<tr>
    <td>6</td>
    <td>Computer</td>
    <td><?php echo $row['computer']; ?></td>
    <td></td>
</tr>

<tr>
    <td colspan="2"><b>Total</b></td>
    <td><b><?php echo $row['total']; ?></b></td>
    <td></td>
</tr>

<tr>
    <td colspan="2"><b>Average</b></td>
    <td><b><?php echo $row['average']; ?></b></td>
    <td></td>
</tr>

<tr>
    <td colspan="2"><b>Result</b></td>
    <td>
        <b>
        <?php
        if($row['result']=="PASS"){
            echo "<span style='color:green;'>PASS</span>";
        }else{
            echo "<span style='color:red;'>FAIL</span>";
        }
        ?>
        </b>
    </td>
    <td></td>
</tr>

<tr>
    <td colspan="2"><b>Grade</b></td>
    <td>
        <b>
        <?php
        if($row['result']=="FAIL"){
            echo "-";
        }else{
            echo $row['grade'];
        }
        ?>
        </b>
    </td>
    <td></td>
</tr>

<tr>
    <td colspan="2"><b>Rank</b></td>
    <td>
        <b>
        <?php

        if($row['result']=="FAIL"){

            echo "<span style='color:red;'>Not Eligible</span>";

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
        </b>
    </td>
    <td></td>
</tr>

</table>
</div>

<div class="signature-section">

    <div class="left-sign">
        _______________________<br>
        <b>Class Teacher</b>
    </div>

    <div class="center-sign">
        _______________________<br>
        <b>Parent Signature</b>
    </div>

    <div class="right-sign">
        _______________________<br>
        <b>Principal Signature</b>
    </div>

</div>
    <div class="buttons">
        <button onclick="window.print()">🖨 Print</button>
        <a href="view_marks.php">⬅ Back</a>
    </div>

</body>
</html>