<?php
require "../../../backend/connect.php";
$id = $_SESSION['user_id'];
$fetch_data_query = mysqli_query($conn, "SELECT * FROM users WHERE role = 'staff' and user_id = '$id'");
$results = mysqli_fetch_assoc($fetch_data_query);

$course_query = mysqli_query($conn, "SELECT * FROM courses WHERE staff_id = '$id'");
$courses = mysqli_fetch_all($course_query, MYSQLI_ASSOC);

$selected_course_id = isset($_GET['course_id']) ? $_GET['course_id'] : $courses[0]['course_id']; // Default to first course if not selected
$course_name_query = mysqli_query($conn, "SELECT * FROM courses WHERE course_id = '$selected_course_id' ");
$course_name_result = mysqli_fetch_all($course_name_query, MYSQLI_ASSOC);

$semesters_query = mysqli_query($conn, "SELECT * FROM semester WHERE course = '$selected_course_id'");
$semesters = mysqli_fetch_all($semesters_query, MYSQLI_ASSOC);

?>

<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Staff Dashboard</title>
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
				<a href="" class="active">					
					<h3>Dashboard</h3>
				</a>
				<a href="coursework.php" >					
					<h3>Coursework</h3>
				</a>
				<a href="attendance.php" >					
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
			<h1>Dashboard</h1>
			<div class="date">
				<input type="date" name="">
			</div>
			<div class="insights">
				<div class="updated">
					<span class="material-symbols-sharp">
						insights
					</span>
					<div class="middle">
						<div class="left">
							<h3>Total Courses</h3>
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
							<h3>Total Students</h3>
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
							<h3>Total Classes</h3>
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
							<h3>Updated Coursework</h3>
							<h1>0</h1>
						</div>
					</div>
				</div>
                <div class="pending">
					<span class="material-symbols-sharp">
						insights
					</span>
					<div class="middle">
						<div class="left">
							<h3>Pending Coursework</h3>
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
							<h3>Updated Attendance</h3>
							<h1>0</h1>
						</div>
					</div>
				</div>
                <div class="pending">
					<span class="material-symbols-sharp">
						insights
					</span>
					<div class="middle">
						<div class="left">
							<h3>Pending Attendance</h3>
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
							<h3>Total Uploads</h3>
							<h1>0</h1>
						</div>
					</div>
				</div>
            </div>
			<div class="recent-appointments">
                <div class="coursework_title">
                    <h2>Coursework</h2>
                    <form method="GET" action="coursework.php" class="course_form">
                        <select name="course_id" onchange="this.form.submit()">
                            <?php foreach ($courses as $course): ?>
                                <option value="<?= $course['course_id'] ?>" <?= $course['course_id'] == $selected_course_id ? 'selected' : '' ?>>
                                    <?= $course['course_name'] ?>
                                </option>
                            <?php endforeach; ?>
						</select>
						<select name="semester_id" onchange="this.form.submit()">
                            <?php foreach ($semesters as $semesters): ?>
                                <option value="<?= $semesters['semester_id'] ?>" <?= $semesters['course'] == $selected_course_id ? 'selected' : '' ?>>
                                    <?= $semesters['year_of_study'] ?>.<?= $semesters['semester_number']?>
                                </option>
                            <?php endforeach; ?>
						</select>
                    </form>
                </div>
				
				<form method="POST" action="../../../backend/update_dashboard.php">
					<?php if (isset($success)): ?>
                        <p class="success"><?= $success ?></p>
                    <?php endif; ?>
                    <?php if (isset($error)): ?>
                        <p class="error"><?= $error ?></p>
                    <?php endif; ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Coursework</th>
                                <th>Weight</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($course_name_result as $course_name): ?>
                                <tr>
                                    <td><?= $course_name['course_name'] ?></td>
                                    <td><input type="text" name="coursework_type[<?= $course_name['course_id'] ?>]" value="<?= $course_name_result['coursework_type'] ?? '' ?>"></td>
                                    <td><input type="number" name="coursework_weight[<?= $course_name['course_id'] ?>]" value="<?= $course_name_result['coursework_weight'] ?? '' ?>"></td>
								</tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <input type="hidden" name="course_id" value="<?= $selected_course_id ?>">
                    <button type="submit" class="btn">Update Coursework</button>
                </form>
			</div>
		</main>

		<!----------------------------------------------------------->

		<div class="right">
			<div class="top">
				<button id="menu-btn">					
					<span class="material-symbols-sharp">menu</span>
				</button>
				<div class="profile">
					<div class="info">
						<h3>Welcome <?=$results['fname']?></h3>
						<small class="text-muted">Staff</small>
					</div>
					<a>
                        <span class="material-symbols-sharp" class="profile">
                            person
                        </span>
                    </a>
                    <div class="notification-icon">
                        <span class="material-symbols-sharp">
                            notifications
                        </span>
                        <span class="notification-count">3</span>
                    </div>
				</div>
			</div>

			<div class="recent-updates">
				<h2>Notifications</h2>
				<div class="coursework-menus">
					<div class="update">
						<div class="details">						
							<p>New notification</p>	
                            <p>New notification</p>	
                            <p>New notification</p>							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>