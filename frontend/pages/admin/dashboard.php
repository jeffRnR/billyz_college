<?php
require "../../../backend/connect.php";
$id = $_SESSION['user_id'];
$fetch_data_query = mysqli_query($conn, "SELECT * FROM users WHERE role = 'admin' and user_id = '$id'");
$results = mysqli_fetch_assoc($fetch_data_query);

$course_query = mysqli_query($conn, "SELECT * FROM courses");
$courses = mysqli_fetch_all($course_query, MYSQLI_ASSOC);

$selected_course_id = isset($_GET['course_id']) ? $_GET['course_id'] : $courses[0]['course_id'];
$course_name_query = mysqli_query($conn, "SELECT * FROM courses WHERE course_id = '$selected_course_id' ");
$course_name_result = mysqli_fetch_all($course_name_query, MYSQLI_ASSOC);

?>

<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin Dashboard</title>
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
				<a href="staffList.php" >					
					<h3>Staff</h3>
				</a>
				<a href="adminList.php" >					
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
							<h3>Total Staff</h3>
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
							<h3>Total Courses</h3>
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
							<h3>Cleared Students</h3>
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
							<h3>Total Fee Paid</h3>
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
							<h3>Total fee pending</h3>
							<h1>0</h1>
						</div>
					</div>
				</div>
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