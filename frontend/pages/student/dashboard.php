<?php 

require '../../../backend/connect.php';
$id = $_SESSION['student_id'];

$fetch_student = mysqli_query("SELECT * FROM students where admission_no='$id'");
$student = mysqli_fetch_array($conn, $fetch_student);

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Billyz College</title>
    <link rel="stylesheet" type="text/css" href="../style/Index.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&family=Rubik+Moonrocks&display=swap"
        rel="stylesheet">
</head>
<body>
    <nav>
        <div class="container nav_container">
            <a href="#" class="nav_logo">Billyz College</a>
            <h2>Welcome <?=$student['fname'] $student['lname']?></h2>
        </div>
    </nav>
</body>