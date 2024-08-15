<?php
require '../../../backend/connect.php';
$id = $_SESSION['user_id'];
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE user_id = '$id'");
$user = mysqli_fetch_array($user_query, MYSQLI_ASSOC);

$searchTerm = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$staff_query = mysqli_query($conn, "SELECT * FROM users WHERE `role`='staff' AND user_id LIKE '%$searchTerm%'");
$staff = mysqli_fetch_all($staff_query, MYSQLI_ASSOC);
$staff_count = mysqli_num_rows($staff_query);

if ($staff_count === 0) {
    $staff_count = 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $selected_staff = $_POST['selected_staff'] ?? [];

    if ($action === 'delete') {
        foreach ($selected_staff as $staff_id) {
            mysqli_query($conn, "DELETE FROM users WHERE user_id = '$staff_id'");
        }
        $success = "Selected staff members have been deleted successfully!";
    } elseif ($action === 'edit' && count($selected_staff) === 1) {
        $staff_id = $selected_staff[0];
        $edit_query = mysqli_query($conn, "SELECT * FROM users WHERE user_id = '$staff_id'");
        $edit_staff = mysqli_fetch_array($edit_query, MYSQLI_ASSOC);
        $edit_mode = true;
    } elseif ($action === 'update') {
        $staff_id = $_POST['staff_id'];
        $fname = mysqli_real_escape_string($conn, $_POST['fname']);
        $lname = mysqli_real_escape_string($conn, $_POST['lname']);
        $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
		$id_number = mysqli_real_escape_string($conn, $_POST['id_number']);
        $kra_pin = mysqli_real_escape_string($conn, $_POST['kra_pin']);
        $next_of_kin = mysqli_real_escape_string($conn, $_POST['next_of_kin']);
        $next_of_kin_contact = mysqli_real_escape_string($conn, $_POST['next_of_kin_contact']);
		$next_of_kin_relationship = mysqli_real_escape_string($conn, $_POST['next_of_kin_relationship']);
        $course = mysqli_real_escape_string($conn, $_POST['course']);

        mysqli_query($conn, "UPDATE users SET fname='$fname', lname='$lname', phone_number='$phone_number', email='$email', id_number='$id_number', kra_pin='$kra_pin', next_of_kin='$next_of_kin', next_of_kin_contact='$next_of_kin_contact', next_of_kin_relationship='$next_of_kin_relationship', course='$course' WHERE user_id='$staff_id'");
        $success = "Staff member has been updated successfully!";
        $edit_mode = false;
    } elseif ($action === 'edit' && count($selected_staff) > 1) {
        $error = "Please select only one staff member to edit.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Staff List</title>
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
                <a href="" class="active"><h3>Staff</h3></a>
                <a href="adminList.php"><h3>Admin</h3></a>
                <a href="studentList.php"><h3>Student</h3></a>
                <a href="coursesList.php"><h3>Course</h3></a>
                <a href="semesterList.php"><h3>Semesters</h3></a>
                <a href="setFees.php"><h3>Set Fees</h3></a>
                <a href=""><h3>Timetable</h3></a>
                <a href=""><h3>Clearance</h3></a>
                <a href=""><h3>Profile</h3></a>
            </div>
        </aside>

        <main>
            <h1>Staff</h1>
            <div class="insights">
                <div class="pending">
                    <span class="material-symbols-sharp">insights</span>
                    <div class="middle">
                        <div class="left">
                            <h3>All Staff</h3>
                            <h1><?php echo $staff_count ?></h1>
                        </div>
                    </div>
                </div>
            </div>

            <div class="recent-appointments">
                <div class="coursework_title">
                    <h2>Add Staff</h2>
                    <a href="staff.php" class="staff-btn">Add Staff</a>
                </div>

				<?php if (isset($edit_mode) && $edit_mode): ?>
                <div class="edit-form">
                    <h2>Edit Staff</h2>
                    <form method="POST" action="staffList.php">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="staff_id" value="<?= $edit_staff['user_id'] ?>">
                        <label for="fname">First Name:</label>
                        <input type="text" name="fname" value="<?= $edit_staff['fname'] ?>" required>
                        <label for="lname">Last Name:</label>
                        <input type="text" name="lname" value="<?= $edit_staff['lname'] ?>" required>
                        <label for="phone_number">Phone Number:</label>
                        <input type="text" name="phone_number" value="<?= $edit_staff['phone_number'] ?>" required>
                        <label for="email">Email:</label>
                        <input type="email" name="email" value="<?= $edit_staff['email'] ?>" required>
                        <label for="id_number">ID Number:</label>
						<input type="number" name="id_number" value="<?= $edit_staff['id_number'] ?>" required>
                        <label for="kra_pin">KRA PIN:</label>
                        <input type="text" name="kra_pin" value="<?= $edit_staff['kra_pin'] ?>" required>
                        <label for="next_of_kin">Next of Kin:</label>
                        <input type="text" name="next_of_kin" value="<?= $edit_staff['next_of_kin'] ?>" required>
                        <label for="next_of_kin_contact">Next of Kin Contact:</label>
                        <input type="text" name="next_of_kin_contact" value="<?= $edit_staff['next_of_kin_contact'] ?>" required>
						<label for="next_of_kin_relationship">Next of Kin Relationship:</label>
                        <input type="text" name="next_of_kin_relationship" value="<?= $edit_staff['next_of_kin_relationship'] ?>" required>
                        <label for="course">Course:</label>
                        <input type="text" name="course" value="<?= $edit_staff['course'] ?>" required>
                        <button type="submit" class="btn">Update Staff</button>
						<button type="button" class="btn close-btn" onclick="closeEditForm()">Close</button>
                    </form>
                </div>
                <?php endif; ?>
                <form method="POST" action="staffList.php">
                    <table>
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Phone Number</th>
								<th>Email</th>
								<th>ID Number</th>                                
                                <th>KRA PIN</th>
                                <th>Next of Kin</th>
                                <th>Next of Kin Contact</th>
								<th>Next of Kin Relationship</th>
                                <th>Course</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($staff as $staff_member): ?>
                            <tr>
                                <td><input type="checkbox" name="selected_staff[]" value="<?= $staff_member['user_id'] ?>"></td>
                                <td><?= $staff_member['fname']?></td>
                                <td><?= $staff_member['lname']?></td>
                                <td><?= $staff_member['phone_number']?></td>
                                <td><?= $staff_member['email']?></td>
								<td><?= $staff_member['id_number']?></td>
                                <td><?= $staff_member['kra_pin']?></td>
                                <td><?= $staff_member['next_of_kin']?></td>
                                <td><?= $staff_member['next_of_kin_contact']?></td>
								<td><?= $staff_member['next_of_kin_relationship']?></td>
                                <td><?= $staff_member['course']?></td>
                            </tr>
                            <?php endforeach ; ?>
                        </tbody>
                    </table>
                    <input type="hidden" name="action" id="action">
                    <div class="buttons">
                        <button type="button" class="btn" onclick="submitForm('edit')">Edit Selected</button>
                        <button type="button" class="btn delete-btn" onclick="submitForm('delete')">Delete Selected</button>
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
				<h2>Staff</h2>
                <div class="search-container">
                    <form method="GET" action="staff.php" class="attendance_form"> 
                        <input type="text" name="search" placeholder="Search by staff number..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
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

    <script>
        function submitForm(action) {
            document.getElementById('action').value = action;
            document.forms[0].submit();
        }
		function closeEditForm() {
            document.querySelector('.edit-form').style.display = 'none';
        }
		
    </script>
	
</body>
</html>
