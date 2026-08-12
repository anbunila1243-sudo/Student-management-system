<?php
session_start();
include 'includes/db.php';

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM marks WHERE id='$id'");
$row = mysqli_fetch_assoc($result);

if(isset($_POST['update'])){

    $tamil = strtoupper(trim($_POST['tamil']));
    $english = strtoupper(trim($_POST['english']));
    $maths = strtoupper(trim($_POST['maths']));
    $science = strtoupper(trim($_POST['science']));
    $social = strtoupper(trim($_POST['social']));
    $computer = strtoupper(trim($_POST['computer']));

    // Total
    $total =
        ($tamil=="AB"?0:$tamil)+
        ($english=="AB"?0:$english)+
        ($maths=="AB"?0:$maths)+
        ($science=="AB"?0:$science)+
        ($social=="AB"?0:$social)+
        ($computer=="AB"?0:$computer);

    $average = round($total/6,2);

    // Result
    $result = "PASS";

    $marks = [$tamil,$english,$maths,$science,$social,$computer];

    foreach($marks as $m){

        if($m=="AB" || (is_numeric($m) && $m<45)){
            $result="FAIL";
            break;
        }

    }

    // Grade
    if($result=="FAIL"){

        $grade="-";

    }else{

        if($average>=90){
            $grade="O";
        }
        elseif($average>=75){
            $grade="A+";
        }
        elseif($average>=60){
            $grade="A";
        }
        elseif($average>=45){
            $grade="B+";
        }
        else{
            $grade="B";
        }

    }

    mysqli_query($conn,"
    UPDATE marks SET

    tamil='$tamil',
    english='$english',
    maths='$maths',
    science='$science',
    social='$social',
    computer='$computer',
    total='$total',
    average='$average',
    grade='$grade',
    result='$result'

    WHERE id='$id'
    ");

    echo "<script>
    alert('Marks Updated Successfully');
    window.location='view_marks.php';
    </script>";

    exit();

}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Marks</title>
    <link rel="stylesheet" href="css/dark-mode.css">
<script src="js/theme.js"></script>
    <link rel="stylesheet" href="css/edit_marks.css">
</head>
<body>
<button class="theme-toggle" onclick="toggleTheme()">
    🌙 / ☀ Theme
</button>
<div class="container">
<div class="form-box">

<h2>Edit Marks</h2>

<form method="POST">

<label>Tamil</label>
<input type="text" name="tamil" value="<?php echo $row['tamil']; ?>">

<label>English</label>
<input type="text" name="english" value="<?php echo $row['english']; ?>">

<label>Maths</label>
<input type="text" name="maths" value="<?php echo $row['maths']; ?>">

<label>Science</label>
<input type="text" name="science" value="<?php echo $row['science']; ?>">

<label>Social</label>
<input type="text" name="social" value="<?php echo $row['social']; ?>">

<label>Computer</label>
<input type="text" name="computer" value="<?php echo $row['computer']; ?>">

<br><br>

<div class="btn-group">
    <button type="submit" name="update" class="save-btn">
        Update Marks
    </button>

    <button type="reset" class="reset-btn">
        Reset
    </button>
</div>

<br><br>

<a href="view_marks.php" class="back-btn">
⬅ Back to View Marks
</a>

</form>

</div>
</div>

</body>
</html>