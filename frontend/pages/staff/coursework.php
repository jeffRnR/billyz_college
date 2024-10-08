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
// Fetch students and their coursework marks for the selected course and coursework type
$students_query = mysqli_query($conn, "SELECT s.user_id, 
        s.fname, 
        s.lname, 
        s.course,
        sc.coursework_id, 
        sc.coursework_type, 
        sc.coursework_mark, 
        sc.coursework_present
    FROM 
        users s
    LEFT JOIN 
        student_coursework sc 
    ON 
        s.user_id = sc.student_admission 
    AND 
        sc.course_id = '$selected_course_id' 
    AND 
        sc.coursework_type = '$selected_coursework_type'
    WHERE 
        s.course = '$selected_course_id'
	AND
		s.role = 'student'
");
$students = mysqli_fetch_all($students_query, MYSQLI_ASSOC);
$admission_no_query = mysqli_query($conn, "SELECT user_id FROM users WHERE course = '$selected_course_id' AND user_id LIKE '%$searchTerm%' AND role='student'");
$admission_nos = mysqli_fetch_all($admission_no_query, MYSQLI_ASSOC);
?>

<!-- ------------------------------------------------------------------------------------------ -->

<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>coursework</title>
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
				<a href="" class="active">					
					<h3>Coursework</h3>
				</a>
				<a href="attendance.php">
					<h3>Attendance</h3>
				</a>
				<a href="submissions.php">
					<h3>Submissions</h3>
				</a>
				<a href="uploads.php">
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
			<h1>Students Coursework</h1>
			<div class="insights">
				<div class="pending">
					<span class="material-symbols-sharp">
						insights
					</span>
					<div class="middle">
						<div class="left">
							<h3>Pending Update</h3>
							<h1>2</h1>
						</div>
					</div>
				</div>
				<div class="updated">
					<span class="material-symbols-sharp">
						insights
					</span>
					<div class="middle">
						<div class="left">
							<h3>Updated</h3>
							<h1>0</h1>
						</div>
					</div>
				</div>
                <div class="updated">
					<span class="material-symbols-sharp">
						insights
					</span>
					<div class="middle">
						<div class="left">
							<h3>Class average</h3>
							<h1>0</h1>
						</div>
					</div>
				</div>
                <div class="updated">
					<span class="material-symbols-sharp">
						insights
					</span>
					<div class="middle">
						<div class="left">
							<h3>Students</h3>
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
                    </form>
                </div>
				
				<form method="POST" action="../../../backend/update_coursework.php">
					<?php if (isset($success)): ?>
						<p class="success"><?= $success ?></p>
					<?php endif; ?>
					<?php if (isset($error)): ?>
						<p class="error"><?= $error ?></p>
                    <?php endif; ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Admission No</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Mark</th>
                                <th>Present</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                                <tr>
                                    <td><?= $student['user_id'] ?></td>
                                    <td><?= $student['fname'] ?></td>
                                    <td><?= $student['lname'] ?></td>
                                    <td><input type="number" name="marks[<?= $student['user_id'] ?>]" value="<?= $student['mark'] ?? '' ?>"></td>
                                    <td class="checkbox"><input type="checkbox" name="present[<?= htmlspecialchars($student['user_id']) ?>]" value="1" checked <?= isset($_POST['present'][$student['user_id']]) && !$_POST['present'][$student['user_id']] ? '' : 'checked' ?>></td>
								</tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <input type="hidden" name="course_id" value="<?= $selected_course_id ?>">
                    <input type="hidden" name="coursework_type" value="<?= $selected_coursework_type ?>">
                    <button type="submit" class="btn">Update Marks</button>
                </form>
			</div>
		</main>

		<!----------------------------------------------------------->

		<div class="right">
            
			<div class="recent-updates">
				<h2>Students</h2>
                <div class="search-container">
                    <form method="GET" action="coursework.php" class="attendance_form"> 
                        <input type="text" name="search" placeholder="Search by admission number..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                        <button type="submit" class="btn">Search</button>
                    </form>
                </div>
				<div class="attendance_students">					
					<div class="student_list">
                        <ul class="student">
                            <?php foreach ($admission_nos as $admission): ?>
                                <li><a href="#"><?= $admission['user_id'] ?></a></li>
                            <?php endforeach; ?>                            
                        </ul>										
					</div>
				</div>		
			</div>
        </div>
	</div>
</body>
</html>