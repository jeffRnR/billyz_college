<?php
require '../../../backend/connect.php';
$id = $_SESSION['user_id'];
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE user_id = '$id'");
$user = mysqli_fetch_array($user_query, MYSQLI_ASSOC);

$searchTerm = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$course_query = mysqli_query($conn, "SELECT * FROM courses");
$courses = mysqli_fetch_all($course_query, MYSQLI_ASSOC);
$selected_course_id = isset($_GET['course_id']) ? $_GET['course_id'] : $courses[0]['course_id'];

$users_query = mysqli_query($conn, "SELECT * FROM users WHERE `role`='student'");
$users = mysqli_fetch_all($users_query, MYSQLI_ASSOC);
$user_count = mysqli_num_rows($users_query);
if ($user_count === 0) {
    $user_count = 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $selected_students = $_POST['selected_students'] ?? [];

    if ($action === 'delete') {
        foreach ($selected_students as $student_id) {
            mysqli_query($conn, "DELETE FROM students WHERE user_id = '$student_id'");
        }
        $success = "Selected students have been deleted successfully!";
    } 
	elseif ($action === 'edit' && count($selected_students) === 1) {
        $student_id = $selected_students[0];
        $student_query = mysqli_query($conn, "SELECT * FROM users WHERE user_id = '$student_id' AND role='student'");
        $student_to_edit = mysqli_fetch_array($student_query, MYSQLI_ASSOC);    
	}
	elseif ($action === 'update') {
        $user_id = $_POST['user_id'];
        $fname = mysqli_real_escape_string($conn, $_POST['fname']);
        $lname = mysqli_real_escape_string($conn, $_POST['lname']);
        $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
		$id_number = mysqli_real_escape_string($conn, $_POST['id_number']);
        $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth']);
        $next_of_kin = mysqli_real_escape_string($conn, $_POST['next_of_kin']);
        $next_of_kin_contact = mysqli_real_escape_string($conn, $_POST['next_of_kin_contact']);
		$next_of_kin_relationship = mysqli_real_escape_string($conn, $_POST['next_of_kin_relationship']);
        $course = mysqli_real_escape_string($conn, $_POST['course']);

        mysqli_query($conn, "UPDATE users SET fname='$fname', lname='$lname', phone_number='$phone_number', email='$email', id_number='$id_number', date_of_birth='$date_of_birth', next_of_kin='$next_of_kin', next_of_kin_contact='$next_of_kin_contact', next_of_kin_relationship='$next_of_kin_relationship', course='$course' WHERE user_id='$staff_id'");
        $success = "Student has been updated successfully!";
        $edit_mode = false;
	} elseif ($action === 'edit' && count($selected_students) > 1) {
        $error = "Please select only one student to edit.";
    }
}
?>

<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student List</title>
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
                    <span class="material-symbols-sharp">close</span>
                </div>
            </div>
            <div class="sidebar">
                <a href="dashboard.php"><h3>Dashboard</h3></a>
                <a href="staffList.php"><h3>Staff</h3></a>
                <a href="adminList.php"><h3>Admin</h3></a>
                <a href="studentList.php" class="active"><h3>Student</h3></a>                
                <a href="coursesList.php"><h3>Course</h3></a>
                <a href="semesterList.php"><h3>Semesters</h3></a>
                <a href="setFees.php"><h3>Set Fees</h3></a>
                <a href="#"><h3>Timetable</h3></a>
                <a href="#"><h3>Clearance</h3></a>
                <a href="#"><h3>Profile</h3></a>
            </div>
        </aside>

        <main>
            <h1>Students</h1>
            <div class="insights">
                <div class="pending">
                    <span class="material-symbols-sharp">insights</span>
                    <div class="middle">
                        <div class="left">
                            <h3>All Students</h3>
                            <h1><?php echo $user_count ?></h1>
                        </div>
                    </div>
                </div>
            </div>

            <div class="recent-appointments">
                <div class="coursework_title">
                    <h2>Add Students</h2>
                    <a href="student.php" class="staff-btn">Add Student</a>
                </div>

				<?php if (isset($student_to_edit)): ?>
				<div class="edit-form">
					<h2>Edit Student</h2>
					<form method="POST" action="studentList.php">
						<input type="hidden" name="action" value="update">
						<input type="hidden" name="user_id" value="<?= $student_to_edit['user_id'] ?>">
						<label>First Name: <input type="text" name="fname" value="<?= $student_to_edit['fname'] ?>" required></label>
						<label>Last Name: <input type="text" name="lname" value="<?= $student_to_edit['lname'] ?>" required></label>
						<label>Phone Number: <input type="text" name="phone_number" value="<?= $student_to_edit['phone_number'] ?>" required></label>
						<label>Email: <input type="email" name="email" value="<?= $student_to_edit['email'] ?>" required></label>
						<label>ID Number: <input type="number" name="id_number" value="<?= $student_to_edit['id_number'] ?>" required></label>
						<label>Date of Birth: <input type="date" name="date_of_birth" value="<?= $student_to_edit['date_of_birth'] ?>" required></label>
						<label>Next of Kin: <input type="text" name="next_of_kin" value="<?= $student_to_edit['next_of_kin'] ?>" required></label>
						<label>Next of Kin Contact: <input type="text" name="next_of_kin_contact" value="<?= $student_to_edit['next_of_kin_contact'] ?>" required></label>
						<label>Next of Kin Relationship: <input type="text" name="next_of_kin_relationship" value="<?= $student_to_edit['next_of_kin_relationship'] ?>" required></label>
						<button type="submit" class="btn">Update Student</button>
						<button type="button" class="btn close-btn" onclick="closeEditForm()">Close</button>
					</form>
				</div>
				<?php endif; ?>
                <form method="POST" action="studentList.php">
                    <table>
                        <thead>
                            <tr>
                                <th></th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Phone Number</th>
                                <th>Email</th>
                                <th>ID Number</th>
                                <th>Date of Birth</th>
                                <th>Next of Kin</th>
                                <th>Next of Kin Contact</th>
								<th>Next of Kin Relationship</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $student): ?>
                            <tr>
                                <td><input type="checkbox" name="selected_students[]" value="<?= $student['user_id'] ?>"></td>
                                <td><?= $student['fname']?></td>
                                <td><?= $student['lname']?></td>
                                <td><?= $student['phone_number']?></td>
                                <td><?= $student['email']?></td>
                                <td><?= $student['id_number']?></td>
                                <td><?= $student['date_of_birth']?></td>
                                <td><?= $student['next_of_kin']?></td>
                                <td><?= $student['next_of_kin_contact']?></td>
								<td><?= $student['next_of_kin_relationship']?></td>
                            </tr>
                            <?php endforeach ; ?>
                        </tbody>
                    </table>
                    <input type="hidden" name="action" value="">
                    <div class="buttons">
                        <button type="button" class="btn" onclick="editSelectedStudent()">Edit Selected</button>
                        <button type="button" class="btn delete-btn" onclick="deleteSelectedStudents()">Delete Selected</button>
                    </div>
                    <?php if (isset($success)): ?>
                        <p class="success"><?= $success ?></p>
                    <?php endif; ?>
                    <?php if (isset($error)): ?>
                        <p class="error"><?= $error ?></p>
                    <?php endif; ?>
                </form>
            </div>            
        </main>
        <div class="right">
            <div class="recent-updates">
                <h2>Students</h2>
                <div class="search-container">
                    <form method="GET" action="studentList.php" class="attendance_form"> 
                        <input type="text" name="search" placeholder="Search by student number..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                        <button type="submit" class="btn">Search</button>
                    </form>
                </div>
                <div class="attendance_students">                    
                    <div class="student_list">
                        <ul class="student">
                            <?php foreach ($users as $student): ?>
                                <li><a href="#"><?= $student['user_id']?> <?= $student['fname']?> <?= $student['lname'] ?></a></li>
                            <?php endforeach; ?>                            
                        </ul>                                        
                    </div>
                </div>        
            </div>
        </div>
    </div>

    <script>
        function editSelectedStudent() {
            const form = document.querySelector('form');
            form.querySelector('input[name="action"]').value = 'edit';
            form.submit();
        }

        function deleteSelectedStudents() {
            const form = document.querySelector('form');
            form.querySelector('input[name="action"]').value = 'delete';
            if (confirm('Are you sure you want to delete the selected students?')) {
                form.submit();
            }
        }
		function closeEditForm() {
            document.querySelector('.edit-form').style.display = 'none';
        }
    </script>
</body>
</html>
