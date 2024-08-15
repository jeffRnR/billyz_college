<?php
require '../../../backend/connect.php';
$id = $_SESSION['user_id'];
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE user_id = '$id'");
$user = mysqli_fetch_array($user_query, MYSQLI_ASSOC);
$searchTerm = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$course_query = mysqli_query($conn, "SELECT * FROM courses");
$courses = mysqli_fetch_all($course_query, MYSQLI_ASSOC);

$selected_course_id = isset($_GET['course_id']) ? $_GET['course_id'] : $courses[0]['course_id'];

foreach($courses as $course){
    $course_staff_id = $course['staff_id'];
};


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $semester = mysqli_real_escape_string($conn, $_POST['semester']);
    $fee_amount = mysqli_real_escape_string($conn, $_POST['fee_amount']);

    $query = "INSERT INTO set_fees (course, semester, fee_amount);
                VALUES ('$selected_course_id', '$semester', '$fee_amount')";
    if (mysqli_query($conn, $query)) {
        $success = "Fees added successfully.";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
};

$semester_query = mysqli_query($conn, "SELECT * FROM semester WHERE course = '$selected_course_id'");
$semester = mysqli_fetch_all($semester_query, MYSQLI_ASSOC);

$all_semester_query = mysqli_query($conn, "SELECT * FROM semester");
$all_semester = mysqli_fetch_all($all_semester_query, MYSQLI_ASSOC);

?>

<!-- ------------------------------------------------------------------------------------------ -->
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>fees</title>
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
				<a href="coursesList.php">					
					<h3>Course</h3>
				</a>
				<a href="semesterList.php">		
					<h3>Semesters</h3>
				</a>
                <a href="" class="active">					
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
			<div class="recent-appointments">
				<div class="coursework_title">
					<h2>Semester Fees</h2>
                    <a href="otherFees.php" class="staff-btn">Set Other Fees</a>
                    <h2>Fees</h2>
                    <form method="GET" action="setFees.php" class="course_form">
                        <select name="course_id" onchange="this.form.submit()">
                            <?php foreach ($courses as $course): ?>
                                <option value="<?= $course['course_id'] ?>" <?= $course['course_id'] == $selected_course_id ? 'selected' : '' ?>>
                                    <?= $course['course_name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>                    
                </div>				
				<form method="POST" action="setFees.php">
                    <?php if (isset($success)): ?>
                        <p class="success"><?= $success ?></p>
                    <?php endif; ?>
                    <?php if (isset($error)): ?>
                        <p class="error"><?= $error ?></p>
                    <?php endif; ?>
                    <table>
                        <thread>
                            <tr>
                                <th>Semester</th>
                                <th>Fees (in ksh)</th>
                            </tr>
                        </thread>
                        <tbody>
                            <?php foreach ($all_semester as $semesters): ?>
                                <tr>                                
                                    <td>
                                        <select id="semester" name="semester" required>
                                            <?php foreach($semester as $semester) : ?>
                                                <option value="<?= $semester['semester_id'] ?>">
                                                    <?= $semester['year_of_study']?> . <?= $semester['semester_number']?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td><input type="text" id="fee_amount" name="fee_amount" required></td>
                                </tr>
                            <?php endforeach ;?>
                        </tbody>
                    </table>
                    <button type="submit" class="btn">Add Fee</button>        
                </form>
			</div>
		</main>

		<!------------------------------------------------------------------->

		<div class="right">            
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
	</div>
</body>
</html>