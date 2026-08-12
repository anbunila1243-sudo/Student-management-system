<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
if(isset($_GET['search'])){
    $search = $_GET['search'];

    $result = mysqli_query($conn,
    "SELECT * FROM students
     WHERE reg_no LIKE '%$search%'
     OR name LIKE '%$search%'");
}else{
    $result = mysqli_query($conn, "SELECT * FROM students");
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>View Students</title>

    <link rel="stylesheet" href="css/view_students.css">

    <link rel="stylesheet" href="css/dark-mode.css">
<script src="js/theme.js"></script>
</head>

<body>
    <button class="theme-toggle" onclick="toggleTheme()">
    🌙 / ☀ Theme
</button>

<h2>Student List</h2>
<form method="GET">
    <input type="text" name="search" placeholder="Search by Reg No or Name">

    <button type="submit" class="search-btn">Search</button>

   <a href="view_students.php" class="reset-btn">Reset</a>
</form>

<br>

<table>

<tr>    
<th>ID</th>
<th>Reg No</th>
<th>Name</th>
<th>Department</th>
<th>Year</th>
<th>Email</th>
<th>Phone</th>
<th>Photo</th>
<th>Action</th>
</tr>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo $row['id']; ?></td>
<td><?php echo $row['reg_no']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['department']; ?></td>
<td><?php echo $row['year']; ?></td>
<td><?php echo $row['email']; ?></td>
<td><?php echo $row['phone']; ?></td>

<td>
<?php
if(!empty($row['photo'])){
?>
    <img src="images/<?php echo $row['photo']; ?>" width="80" height="80" style="border-radius:8px;">
<?php
}else{
    echo "No Photo";
}
?>
</td>
<td>
    <a href="edit_student.php?id=<?php echo $row['id']; ?>">✏ Edit</a> |

    <a href="delete_student.php?id=<?php echo $row['id']; ?>"
       onclick="return confirm('Are you sure you want to delete this student?');">
       🗑 Delete
    </a>
</td>

</tr>
</td>

</tr>

<?php } ?>

</table>
<br>

<a href="dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
</body>
</html>