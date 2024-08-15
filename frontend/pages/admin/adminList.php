<?php
require '../../../backend/connect.php';
$id = $_SESSION['user_id'];

$user_query = mysqli_query($conn, "SELECT * FROM users WHERE user_id = '$id'");
$user = mysqli_fetch_array($user_query, MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $selected_staff = $_POST['selected_staff'] ?? [];

    if ($action === 'delete') {
        foreach ($selected_staff as $staff_id) {
            mysqli_query($conn, "DELETE FROM users WHERE user_id = '$staff_id' AND role='admin'");
        }
        $success = "Selected admins have been deleted successfully!";
    }elseif ($action === 'edit' && count($selected_staff) === 1) {
        $staff_id = $selected_staff[0];
        $edit_query = mysqli_query($conn, "SELECT * FROM users WHERE user_id = '$staff_id' AND role='admin'");
        $admin_to_edit = mysqli_fetch_array($edit_query, MYSQLI_ASSOC);
		$edit_mode = true;
    }elseif ($action === 'update') {
        $staff_id = $_POST['staff_id'];
        $fname = mysqli_real_escape_string($conn, $_POST['fname']);
        $lname = mysqli_real_escape_string($conn, $_POST['lname']);
        $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $next_of_kin = mysqli_real_escape_string($conn, $_POST['next_of_kin']);
        $next_of_kin_contact = mysqli_real_escape_string($conn, $_POST['next_of_kin_contact']);

        mysqli_query($conn, "UPDATE users SET fname='$fname', lname='$lname', phone_number='$phone_number', email='$email', password='$password', next_of_kin='$next_of_kin', next_of_kin_contact='$next_of_kin_contact' WHERE user_id='$staff_id'");
        $success = "Admin updated successfully!";
		$edit_mode = false;
    }
	elseif ($action === 'edit' && count($selected_staff) > 1) {
        $error = "Please select only one admin to edit.";
    }
}

$admin_query = mysqli_query($conn, "SELECT * FROM users WHERE role='admin'");
$admins = mysqli_fetch_all($admin_query, MYSQLI_ASSOC);
$staff_count = count($admins);

?>

<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin List</title>
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
                <a href="" class="active"><h3>Admin</h3></a>
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
            <h1>Admin List</h1>
            <div class="insights">
                <div class="pending">
                    <span class="material-symbols-sharp">insights</span>
                    <div class="middle">
                        <div class="left">
                            <h3>All Admins</h3>
                            <h1><?php echo $staff_count ?></h1>
                        </div>
                    </div>
                </div>
            </div>

            <div class="recent-appointments">
                <div class="coursework_title">
                    <h2>Add Admin</h2>
                    <a href="admin.php" class="staff-btn">Add Admin</a>
                </div>
				<?php if (isset($admin_to_edit)): ?>
                <div class="edit-form">
                    <h2>Edit Admin</h2>
                    <form method="POST" action="adminList.php">
						<input type="hidden" name="action">
                        <input type="hidden" name="staff_id" value="<?= $admin_to_edit['user_id'] ?>">
                        <label for="fname">First Name:</label>
                        <input type="text" id="fname" name="fname" value="<?= htmlspecialchars($admin_to_edit['fname']) ?>" required>
                        <label for="lname">Last Name:</label>
                        <input type="text" id="lname" name="lname" value="<?= htmlspecialchars($admin_to_edit['lname']) ?>" required>
                        <label for="phone_number">Phone Number:</label>
                        <input type="text" id="phone_number" name="phone_number" value="<?= htmlspecialchars($admin_to_edit['phone_number']) ?>" required>
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($admin_to_edit['email']) ?>" required>
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" value="<?= htmlspecialchars($admin_to_edit['password']) ?>" required>
                        <label for="next_of_kin">Next of Kin:</label>
                        <input type="text" id="next_of_kin" name="next_of_kin" value="<?= htmlspecialchars($admin_to_edit['next_of_kin']) ?>" required>
                        <label for="next_of_kin_contact">Next of Kin Contact:</label>
                        <input type="text" id="next_of_kin_contact" name="next_of_kin_contact" value="<?= htmlspecialchars($admin_to_edit['next_of_kin_contact']) ?>" required>
                        <button type="submit" class="btn">Update Admin</button>
						<button type="button" class="btn close-btn" onclick="closeEditForm()">Close</button>
                    </form>
                </div>
                <?php endif; ?>
                <form method="POST" action="adminList.php">
                    <table>
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Phone Number</th>
                                <th>Email</th>
                                <th>Password</th>
                                <th>Next of Kin</th>
                                <th>Next of Kin Contact</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($admins as $admin): ?>
                                <tr>
                                    <td><input type="checkbox" name="selected_staff[]" value="<?= $admin['user_id'] ?>"></td>
                                    <td><?= htmlspecialchars($admin['fname']) ?></td>
                                    <td><?= htmlspecialchars($admin['lname']) ?></td>
                                    <td><?= htmlspecialchars($admin['phone_number']) ?></td>
                                    <td><?= htmlspecialchars($admin['email']) ?></td>
                                    <td><?= htmlspecialchars($admin['password']) ?></td>
                                    <td><?= htmlspecialchars($admin['next_of_kin']) ?></td>
                                    <td><?= htmlspecialchars($admin['next_of_kin_contact']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="buttons">
                        <button type="button" class="btn" onclick="editSelectedAdmin()">Edit Selected</button>
                        <button type="button" class="btn delete-btn" onclick="deleteSelectedAdmins()">Delete Selected</button>
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
                <h2>Admins</h2>
                <div class="search-container">
                    <form method="GET" action="adminList.php" class="attendance_form">
                        <input type="text" name="search" placeholder="Search by staff number..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                        <button type="submit" class="btn">Search</button>
                    </form>
                </div>
                <div class="attendance_students">
                    <div class="student_list">
                        <ul class="student">
                            <?php foreach ($admins as $admin): ?>
                                <li><a href="#"><?= htmlspecialchars($admin['user_id']) ?> <?= htmlspecialchars($admin['fname']) ?> <?= htmlspecialchars($admin['lname']) ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function editSelectedAdmin() {
			const form = document.querySelector('form');
            form.querySelector('input[name="action"]').value = 'edit';
            form.querySelector('form').submit();
			form.submit();
        }

        function deleteSelectedAdmins() {
			const form = document.querySelector('form');
			form.querySelector('input[name="action"]').value = 'delete';
            if (confirm('Are you sure you want to delete the selected admins?')) {
                form.submit();
            }
        }
		function closeEditForm() {
            document.querySelector('.edit-form').style.display = 'none';
        }
    </script>
</body>
</html>
