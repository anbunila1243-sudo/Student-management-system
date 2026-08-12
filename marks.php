<?php
session_start();
include 'includes/db.php';

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

if(isset($_POST['save'])){

    $student_id = $_POST['student_id'];

    // Check if marks already exist
$check = mysqli_query($conn, "SELECT * FROM marks WHERE student_id='$student_id'");

if(mysqli_num_rows($check) > 0){
    echo "<script>
    alert('Marks already exist for this student! Please use Edit Marks.');
    window.location='view_marks.php';
    </script>";
    exit();
}

    if(empty($student_id)){
        echo "<script>alert('Please select a student from the search list.');</script>";
    }else{

        $tamil = strtoupper(trim($_POST['tamil']));
        $english = strtoupper(trim($_POST['english']));
        $maths = strtoupper(trim($_POST['maths']));
        $science = strtoupper(trim($_POST['science']));
        $social = strtoupper(trim($_POST['social']));
        $computer = strtoupper(trim($_POST['computer']));

        $total =
            ($tamil=="AB"?0:$tamil) +
            ($english=="AB"?0:$english) +
            ($maths=="AB"?0:$maths) +
            ($science=="AB"?0:$science) +
            ($social=="AB"?0:$social) +
            ($computer=="AB"?0:$computer);

        $average = round($total/6,2);

        $result = "PASS";

$marks = [$tamil,$english,$maths,$science,$social,$computer];

foreach($marks as $m){

    if($m == "AB" || (is_numeric($m) && $m < 45)){
        $result = "FAIL";
        break;
    }

}

    if($result=="FAIL"){

    $grade="-";

}else{

    if($average >= 90){
        $grade="O";
    }
    elseif($average >=75){
        $grade="A+";
    }
    elseif($average >=60){
        $grade="A";
    }
    elseif($average >=45){
        $grade="B+";
    }
    else{
        $grade="B";
    }

}    

       mysqli_query($conn,"INSERT INTO marks
       (student_id,tamil,english,maths,science,social,computer,total,average,grade,result)
        VALUES
        ('$student_id','$tamil','$english','$maths','$science','$social','$computer','$total','$average','$grade','$result')");

        echo "<script>
        alert('Marks Added Successfully');
        window.location='view_marks.php';
        </script>";
    }
}

$result = mysqli_query($conn,"SELECT id,name,reg_no FROM students");

$studentArray=[];

while($row=mysqli_fetch_assoc($result)){
    $studentArray[]=$row;
}
?>

<!DOCTYPE html>
<html>

<head>

<link rel="stylesheet" href="css/dark-mode.css">
<script src="js/theme.js"></script>
<title>Add Marks</title>

<link rel="stylesheet" href="css/marks.css">

</head>

<body>

<div class="container">

<div class="form-box">

<h2>Add Student Marks</h2>

<form method="POST">

<label>Search Student</label>

<input
type="text"
id="searchStudent"
placeholder="🔍 Type Student Name or Register Number"
autocomplete="off">

<input
type="hidden"
name="student_id"
id="student_id">

<div id="studentList"></div>

<label>Tamil</label>
<input type="text" name="tamil" placeholder="Enter Mark or AB" required>

<label>English</label>
<input type="text" name="english" placeholder="Enter Mark or AB" required>

<label>Maths</label>
<input type="text" name="maths" placeholder="Enter Mark or AB" required>

<label>Science</label>
<input type="text" name="science" placeholder="Enter Mark or AB" required>

<label>Social</label>
<input type="text" name="social" placeholder="Enter Mark or AB" required>

<label>Computer</label>
<input type="text" name="computer" placeholder="Enter Mark or AB" required>

<button type="submit" name="save">
Save Marks
</button>

</form>

<a href="dashboard.php" class="back-btn">
⬅ Back to Dashboard
</a>

</div>

</div>

<script>

let students = <?php echo json_encode($studentArray); ?>;

const search = document.getElementById("searchStudent");
const list = document.getElementById("studentList");
const hidden = document.getElementById("student_id");

search.addEventListener("keyup",function(){

let value=this.value.toLowerCase();

list.innerHTML="";

hidden.value="";

if(value=="") return;

students.forEach(function(student){

if(
student.name.toLowerCase().includes(value) ||
student.reg_no.toLowerCase().includes(value)
){

let div=document.createElement("div");

div.className="student-item";

div.innerHTML=
student.reg_no+" - "+student.name;

div.onclick=function(){

search.value=
student.reg_no+" - "+student.name;

hidden.value=
student.id;

list.innerHTML="";

};

list.appendChild(div);

}

});

});

document.addEventListener("click",function(e){

if(
!search.contains(e.target) &&
!list.contains(e.target)
){
list.innerHTML="";
}

});

</script>

</body>

</html>