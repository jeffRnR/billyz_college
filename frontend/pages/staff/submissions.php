<?php
require '../../../backend/connect.php';
$id = $_SESSION['user_id'];
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE user_id = '$id'");
$user = mysqli_fetch_array($user_query, MYSQLI_ASSOC);
$searchTerm = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
// Fetch courses associated with the staff member
$course_query = mysqli_query($conn, "SELECT * FROM courses WHERE staff_id = '$id'");
$courses = mysqli_fetch_all($course_query, MYSQLI_ASSOC);

$selected_course_id = isset($_GET['course_id']) ? $_GET['course_id'] : $courses[0]['course_id'];
$selected_coursework_type = isset($_GET['type']) ? $_GET['type'] : 'CAT 1';

$coursework_query = mysqli_query($conn, "SELECT * FROM coursework_type WHERE course = '$selected_course_id'");
$coursework = mysqli_fetch_all($coursework_query, MYSQLI_ASSOC);

$submission_query = mysqli_query($conn, "SELECT * from student_submissions WHERE course = '$selected_course_id' AND coursework = '$selected_coursework_type'");
$submissions = mysqli_fetch_all($submission_query, MYSQLI_ASSOC);

$students_query = mysqli_query($conn, "SELECT s.admission_no, 
        s.fname, 
        s.lname, 
        s.course,
        sc.coursework_id, 
        sc.coursework_type, 
        sc.coursework_mark, 
        sc.coursework_present
    FROM 
        students s
    LEFT JOIN 
        student_coursework sc 
    ON 
        s.admission_no = sc.student_admission 
    AND 
        sc.course_id = '$selected_course_id' 
    AND 
        sc.coursework_type = '$selected_coursework_type'
    WHERE 
        s.course = '$selected_course_id'
");
$students = mysqli_fetch_all($students_query, MYSQLI_ASSOC);

$admission_no_query = mysqli_query($conn, "SELECT admission_no FROM students WHERE course = '$selected_course_id' AND admission_no LIKE '%$searchTerm%'");
$admission_nos = mysqli_fetch_all($admission_no_query, MYSQLI_ASSOC);
?>

<!-- ------------------------------------------------------------------------------------------ -->

<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>uploads</title>
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
				<a href="coursework.php">					
					<h3>Coursework</h3>
				</a>
				<a href="attendance.php">
					<h3>Attendance</h3>
				</a>
                <a href="submissions.php"class="active">
					<h3>Submissions</h3>
				</a>
				<a href="uploads.php" >
					<h3>Uploads</h3>
				</a>
                <a href="">
					<h3>Timetable</h3>
				</a>
                <a href="">
					<h3>Profile</h3>
				</a>
			</div>
		</aside>

		<!-------------------------------------------------------section------------------------------------------------->
		<main>
			<h1>Submissions from students</h1>
			<div class="insights">
                <div class="updated">
					<span class="material-symbols-sharp">
						insights
					</span>
					<div class="middle">
						<div class="left">
							<h3>Total submissions</h3>
							<h1>0</h1>
						</div>
					</div>
				</div>
            </div>

			<!---------------------------------------------------------------------------------->

			<div class="recent-appointments">
                <div class="coursework_title">
                    <h2>Your Students</h2>
                    <form method="GET" action="coursework.php" class="course_form">
						<?php if (isset($success)): ?>
							<p class="success"><?= $success ?></p>
						<?php endif; ?>
						<?php if (isset($error)): ?>
							<p class="error"><?= $error ?></p>
						<?php endif; ?>
                        <select name="course_id" onchange="this.form.submit()">
                            <?php foreach ($courses as $course): ?>
                                <option value="<?= $course['course_id'] ?>" <?= $course['course_id'] == $selected_course_id ? 'selected' : '' ?>>
                                    <?= $course['course_name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
						<select>
						<?php foreach ($coursework as $coursework): ?>
							<option>
								<?= $coursework['coursework_type'] ?>
							</option>
						<?php endforeach; ?>
						</select>
                        <select>
							<option>2024 1.1</option>
							<option>2024 1.2</option>
							<option>2025 1.1</option>
							<option>2025 1.2</option>
						</select>
                    </form>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Admission no</th>
                            <th>First name</th>
                            <th>Last name</th>
                            <th>Submission date</th>
                            <th>Submission time</th>
                            <th>Submitted file</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student ): ?>
                            <?php foreach ($submissions as $submissions): ?>
                                <tr>
                                    <td><?= $student['admission_no'] ?></td>
                                    <td><?= $student['fname'] ?></td>
                                    <td><?= $student['lname'] ?></td>
                                    <td><?= $submissions['submission_date'] ?></td>
                                    <td><?= $submissions['submission_time'] ?></td>
                                    <td><?= $submissions['file'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>                
			</div>
		</main>

		<!----------------------------------------------------------->

		<div class="right">
            
			<div class="recent-updates">
				<h2>Students</h2>
                <div class="search-container">
                    <form method="GET" action="uploads.php" class="attendance_form"> 
                        <input type="text" name="search" placeholder="Search by admission number..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                        <button type="submit" class="btn">Search</button>
                    </form>
                </div>
				<div class="attendance_students">					
					<div class="student_list">
                        <ul class="student">
                            <?php foreach ($admission_nos as $admission): ?>
                                <li><a href="#"><?= $admission['admission_no'] ?></a></li>
                            <?php endforeach; ?>                            
                        </ul>										
					</div>
				</div>		
			</div>
        </div>
	</div>
</body>
</html>