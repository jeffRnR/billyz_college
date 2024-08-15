<?php
require '../../../backend/connect.php';
$id = $_SESSION['user_id'];
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE user_id = '$id'");
$user = mysqli_fetch_array($user_query, MYSQLI_ASSOC);
$searchTerm = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$course_query = mysqli_query($conn, "SELECT * FROM courses");
$courses = mysqli_fetch_all($course_query, MYSQLI_ASSOC);

$selected_course_id = isset($_GET['course_id']) ? $_GET['course_id'] : $courses[0]['course_id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $next_of_kin = mysqli_real_escape_string($conn, $_POST['next_of_kin']);
    $next_of_kin_contact = mysqli_real_escape_string($conn, $_POST['next_of_kin_contact']);

    // Validate email and KRA PIN
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        $query = "INSERT INTO users (fname, lname, phone_number, email, `password`, next_of_kin, next_of_kin_contact, `role`)
                  VALUES ('$fname', '$lname', '$phone_number', '$email', '$password', '$next_of_kin', '$next_of_kin_contact', 'admin')";
        if (mysqli_query($conn, $query)) {
            $success = "Admin added successfully.";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
};

$staff_query = mysqli_query($conn, "SELECT * FROM users WHERE `role`='admin' AND user_id LIKE '%$searchTerm%'");
$staff = mysqli_fetch_all($staff_query, MYSQLI_ASSOC);
$staff_count = mysqli_num_rows($staff_query);

if ($staff_count === 0) {
    $staff_count = 0;
}
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
				<a href="staffList.php"  >					
					<h3>Staff</h3>
				</a>
				<a href="" class="active" >					
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
			<h1>Admins</h1>
			<div class="insights">
				<div class="pending">
					<span class="material-symbols-sharp">
						insights
					</span>
					<div class="middle">
						<div class="left">
							<h3>All Admins</h3>
							<h1><?php echo $staff_count ?></h1>
						</div>
					</div>
				</div>
            </div>
			<!---------------------------------------------------------------------------------->

			<div class="recent-appointments">
                <div class="coursework_title">
                    <h2>Add Admin</h2>
                    <a href="adminList.php" class="staff-btn">See Admins</a>
                </div>
				<form method="POST" action="admin.php">
					<?php if (isset($success)): ?>
                        <p class="success"><?= $success ?></p>
                    <?php endif; ?>
                    <?php if (isset($error)): ?>
                        <p class="error"><?= $error ?></p>
                    <?php endif; ?>
                    <table>
                        <thread>
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Phone Number</th>
                                <th>Email</th>
                                <th>Password</th>
                                <th>Next of Kin</th>
                                <th>Next of Kin Contact</th>
                            </tr>
                        </thread>
                        <tbody>
                            <tr>
                                <td><input type="text" id="fname" name="fname" required></td>
                                <td><input type="text" id="lname" name="lname" required></td>
                                <td><input type="text" id="phone_number" name="phone_number" required></td>
                                <td><input type="email" id="email" name="email" required></td>
                                <td><input type="password" id="password" name="password" required></td>
                                <td><input type="text" id="next_of_kin" name="next_of_kin" required></td>
                                <td><input type="text" id="next_of_kin_contact" name="next_of_kin_contact" required></td>
							</tr>
                        </tbody>
                    </table>
                    <button type="submit" class="btn">Add Admin</button>
                    <?php if (isset($success)): ?>
                        <p class="success"><?= $success ?></p>
                    <?php endif; ?>
                    <?php if (isset($error)): ?>
                        <p class="error"><?= $error ?></p>
                    <?php endif; ?>
                </form>
			</div>
		</main>

		<!------------------------------------------------------------------->

		<div class="right">
            
			<div class="recent-updates">
				<h2>Admin</h2>
                <div class="search-container">
                    <form method="GET" action="admin.php" class="attendance_form"> 
                        <input type="text" name="search" placeholder="Search by admin number..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                        <button type="submit" class="btn">Search</button>
                    </form>
                </div>
				<div class="attendance_students">					
					<div class="student_list">
                        <ul class="student">
                            <?php foreach ($staff as $staff): ?>
                                <li><a href="#"><?= $staff['user_id']?> <?= $staff['fname']?> <?= $staff['lname'] ?></a></li>
                            <?php endforeach; ?>                            
                        </ul>										
					</div>
				</div>		
			</div>
        </div>
	</div>
</body>
</html>