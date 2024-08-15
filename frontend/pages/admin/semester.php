<?php
require '../../../backend/connect.php';
$id = $_SESSION['user_id'];

$course_query = mysqli_query($conn, "SELECT * FROM courses");
$courses = mysqli_fetch_all($course_query, MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $year_of_study = mysqli_real_escape_string($conn, $_POST['year_of_study']);
    $semester_number = mysqli_real_escape_string($conn, $_POST['semester_number']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);


    $query = "INSERT INTO semester (year_of_study, semester_number, `start_date`, end_date, course);
                VALUES ('$year_of_study', '$semester_number','$start_date', '$end_date', '$course')";
    if (mysqli_query($conn, $query)) {
        $success = "Semester added successfully.";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>

<!-- ------------------------------------------------------------------------------------------ -->

<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>semester</title>
	<link rel="stylesheet" type="text/css" href="../../style/staffDashboard.css">
	<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp:opsz,wght,FILL,GRAD@48,400,0,0" />
</head>
<body>
	<div class="container">
		<aside>
			<div class="top">
				<div class="logo">
					<a href="../Index.html" class="nav_logo"><h2>Billyz College</h2></a>
				</div>
				<div class="close" id="close_btn">
					<span class="material-symbols-sharp">
						close
					</span>
				</div>
			</div>
			<div class="sidebar">
				<a href="dashboard.php">					
					<h3>Dashboard</h3>
				</a>
				<a href="staffList.php"  >					
					<h3>Staff</h3>
				</a>
				<a href="adminList.php"  >					
					<h3>Admin</h3>
				</a>
				<a href="studentList.php">
					<h3>Student</h3>
				</a>				
				<a href="coursesList.php" >					
					<h3>Course</h3>
				</a>
				<a href="" class="active">		
					<h3>Semesters</h3>
				</a>
                <a href="setFees.php">					
					<h3>Set Fees</h3>
				</a>
                <a href="">
					<h3>Timetable</h3>
				</a>
				
				<a href="">					
					<h3>Clearance</h3>
				</a>
                <a href="">
					<h3>Profile</h3>
				</a>
			</div>
		</aside>

		<!-------------------------------------------------------section------------------------------------------------->
		<main>
			<h1>Semester</h1>
			<!---------------------------------------------------------------------------------->

			<div class="recent-appointments">
                <div class="coursework_title">
                    <h2>Add Semester</h2>
                    <a href="semesterList.php" class="staff-btn">See Semesters</a>
                </div>
				<form method="POST" action="semester.php">
                    <?php if (isset($success)): ?>
                        <p class="success"><?= $success ?></p>
                    <?php endif; ?>
                    <?php if (isset($error)): ?>
                        <p class="error"><?= $error ?></p>
                    <?php endif; ?>
                    <table>
                        <thread>
                            <tr>
                                <th>Year of Study</th>
                                <th>Semester Number</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Course</th>
                            </tr>
                        </thread>
                        <tbody>
                            <tr>
                                <td><input type="number" id="year_of_study" name="year_of_study" required></td>
                                <td><input type="number" id="semester_number" name="semester_number" required></td>
                                <td><input type="date" id="start_date" name="start_date" required></td>
                                <td><input type="date" id="end_date" name="end_date" required></td>
                                <td>
                                    <select id="course" name="course" required>
                                        <?php foreach ($courses as $course): ?>
                                            <option value="<?= $course['course_id'] ?>"><?= $course['course_name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                        </tbody>
                    </table>
                    <button type="submit" class="btn">Add Semester</button>
                </form>
			</div>
		</main>

		<!------------------------------------------------------------------->

		<!-- <div class="right">            
			<div class="recent-updates">
				<h2>Courses</h2>
				<div class="attendance_students">					
					<div class="student_list">
                        <ul class="student">
                            <?php foreach ($courses as $courses): ?>
                                <li><a href="#"> <?= $courses['course_name']?></a></li>
                            <?php endforeach; ?>                            
                        </ul>										
					</div>
				</div>		
			</div>
        </div>
	</div> -->
</body>
</html>