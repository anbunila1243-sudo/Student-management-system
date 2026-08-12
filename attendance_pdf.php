<?php
session_start();
include 'includes/db.php';

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$month = $_GET['month'] ?? date('Y-m');

$result = mysqli_query($conn,"
SELECT
students.name,
students.reg_no,

SUM(CASE WHEN attendance.status='Present' THEN 1 ELSE 0 END) AS present,

SUM(CASE WHEN attendance.status='Absent' THEN 1 ELSE 0 END) AS absent,

COUNT(attendance.id) AS total

FROM students

LEFT JOIN attendance
ON students.id=attendance.student_id

AND DATE_FORMAT(attendance.attendance_date,'%Y-%m')='$month'

GROUP BY students.id

ORDER BY students.reg_no
");

$html='

<style>

body{
font-family:DejaVu Sans;
font-size:13px;
}

h2{
text-align:center;
}

table{
width:100%;
border-collapse:collapse;
margin-top:20px;
}

table,th,td{
border:1px solid black;
}

th,td{
padding:8px;
text-align:center;
}

th{
background:#eeeeee;
}

</style>

<h2>

RRASE COLLEGE OF ENGINEERING<br>

Monthly Attendance Report<br>

'.$month.'

</h2>

<table>

<tr>

<th>S.No</th>

<th>Register No</th>

<th>Name</th>

<th>Present</th>

<th>Absent</th>

<th>Attendance %</th>

<th>Student Signature</th>

</tr>

';

$i=1;

while($row=mysqli_fetch_assoc($result)){

    $percent = 0;

    if($row['total'] > 0){
        $percent = round(($row['present']/$row['total'])*100,2);
    }

    $html .= '

    <tr>

    <td>'.$i++.'</td>

    <td>'.$row['reg_no'].'</td>

    <td>'.$row['name'].'</td>

    <td>'.$row['present'].'</td>

    <td>'.$row['absent'].'</td>

    <td>'.$percent.'%</td>

    <td></td>

    </tr>

    ';
}

$html .= '

</table>

<br><br><br><br>

<table style="border:none;width:100%;">

<tr style="border:none;">

<td style="border:none;text-align:center;">

_____________________<br>

Class Advisor

</td>

<td style="border:none;text-align:center;">

_____________________<br>

HOD

</td>

<td style="border:none;text-align:center;">

_____________________<br>

Principal

</td>

</tr>

</table>

';

$options = new Options();
$options->set('isRemoteEnabled',true);

$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);

$dompdf->setPaper('A4','landscape');

$dompdf->render();

$dompdf->stream("Attendance_Report_".$month.".pdf",["Attachment"=>true]);

exit();