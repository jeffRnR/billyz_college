<?php 

require '../../../backend/connect.php';
$id = $_SESSION['user_id'];

// $fetch_student = mysqli_query("SELECT * FROM users where user_id='$id' and `role`='student'");
// $student = mysqli_fetch_array($conn, $fetch_student);

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Billyz College</title>
    <link rel="stylesheet" type="text/css" href="../../style/studentDashboard.css">
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
    <div class="container">
        <header>
            <h1>Welcome <br><span>Jeff</span></h1>
            <div class="icons">
                <span class="icon icon-bell"></span>
                <span class="icon icon-settings"></span>
            </div>
        </header>
        <div class="grid">
            <div class="card">
                <div class="icon icon-profile">
                </div>
                <p>Profile</p>
            </div>
            <div class="card">
                <div class="icon icon-fees"></div>
                <p>Fees</p>
            </div>
            <div class="card">
                <div class="icon icon-coursework"></div>
                <p>Coursework</p>
            </div>
            <div class="card">
                <div class="icon icon-attendance"></div>
                <p>Attendance</p>
            </div>
            <div class="card">
                <div class="icon icon-timetable"></div>
                <p>Timetable</p>
            </div>
            <div class="card">
                <div class="icon icon-timetable"></div>
                <p>Submissions</p>
            </div>
            <div class="card">
                <div class="icon icon-timetable"></div>
                <p>Upload</p>
            </div>
        </div>
    </div>
</body>