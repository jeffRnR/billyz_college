<?php
require '../../../backend/connect.php';
$id = $_SESSION['user_id'];
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE user_id = '$id'");
$user = mysqli_fetch_array($user_query, MYSQLI_ASSOC);
$searchTerm = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$course_query = mysqli_query($conn, "SELECT * FROM courses");
$courses = mysqli_fetch_all($course_query, MYSQLI_ASSOC);
foreach($courses as $course){
    $course_staff_id = $course['staff_id'];
};


$course_staff_query = mysqli_query($conn, "SELECT * FROM users where user_id='$course_staff_id'");
$course_staff = mysqli_fetch_all($course_staff_query, MYSQLI_ASSOC );
$courses_count = mysqli_num_rows($course_query);

$staff_query = mysqli_query($conn, "SELECT * FROM users WHERE role='staff'");
$staff = mysqli_fetch_all($staff_query, MYSQLI_ASSOC);
foreach ($staff as $staffs){
    $staff_id = $staffs['user_id'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_name = mysqli_real_escape_string($conn, $_POST['course_name']);
    $staff_id = mysqli_real_escape_string($conn, $_POST['staff']);

    $query = "INSERT INTO courses (course_name, staff_id);
                VALUES ('$course_name', '$staff_id')";
    if (mysqli_query($conn, $query)) {
        $success = "Course added successfully.";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
};
if ($courses_count === 0) {
    $courses_count = 0;
}
?>

<!-- ------------------------------------------------------------------------------------------ -->

<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>courses</title>
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
				<a href="" class="active">					
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
			<h1>Courses</h1>
			<div class="insights">
				<div class="pending">
					<span class="material-symbols-sharp">
						insights
					</span>
					<div class="middle">
						<div class="left">
							<h3>All Courses</h3>
							<h1><?php echo $courses_count ?></h1>
						</div>
					</div>
				</div>
            </div>
			<!---------------------------------------------------------------------------------->

			<div class="recent-appointments">
                <div class="coursework_title">
                    <h2>Add Course</h2>
                    <a href="coursesList.php" class="staff-btn">See Course</a>
                </div>
				<form method="POST" action="courses.php">
                    <?php if (isset($success)): ?>
                    <p class="success"><?= $success ?></p>
                    <?php endif; ?>
                    <?php if (isset($error)): ?>
                        <p class="error"><?= $error ?></p>
                    <?php endif; ?>
                    <table>
                        <thread>
                            <tr>
                                <th>Course Name</th>
                                <th>Staff</th>
                            </tr>
                        </thread>
                        <tbody>
                            <tr>
                                <td><input type="text" id="course_name" name="course_name" required></td>
                                <td>
                                    <select id="staff" name="staff" required>
                                        <?php foreach($staff as $staff) : ?>
                                            <option value="<?= $staff['user_id'] ?>">
                                                <?= $staff['fname']?> <?= $staff['lname']?>
                                            </option>
                                        <?php endforeach ?>
                                    </select>
                                </td>
                        </tbody>
                    </table>
                    <button type="submit" class="btn">Add Course</button>        
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