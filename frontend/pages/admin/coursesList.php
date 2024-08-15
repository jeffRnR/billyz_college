<?php
require '../../../backend/connect.php';
$id = $_SESSION['user_id'];

// Fetch user details
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE user_id = '$id'");
$user = mysqli_fetch_array($user_query, MYSQLI_ASSOC);

// Fetch courses and associated staff
$course_query = mysqli_query($conn, "SELECT * FROM courses");
$courses = mysqli_fetch_all($course_query, MYSQLI_ASSOC);

$staff = [];
if (!empty($courses)) {
    foreach ($courses as $course) {
        $course_staff_id = $course['staff_id'];
        $course_staff_query = mysqli_query($conn, "SELECT * FROM users WHERE user_id='$course_staff_id'");
        $staff[$course['course_id']] = mysqli_fetch_array($course_staff_query, MYSQLI_ASSOC);
    }
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $selected_courses = $_POST['selected_courses'] ?? [];

    if ($action === 'delete') {
        foreach ($selected_courses as $course_id) {
            mysqli_query($conn, "DELETE FROM courses WHERE course_id = '$course_id'");
        }
        $success = "Selected courses have been deleted successfully!";
    }

    if ($action === 'edit' && count($selected_courses) === 1) {
        $course_id = $selected_courses[0];
        // Display course details for inline editing
        $edit_query = mysqli_query($conn, "SELECT * FROM courses WHERE course_id = '$course_id'");
        $course_to_edit = mysqli_fetch_array($edit_query, MYSQLI_ASSOC);
    } elseif ($action === 'edit' && count($selected_courses) > 1) {
        $error = "Please select only one course to edit.";
    }

    // Handle editing the course
    if (isset($_POST['edit_course'])) {
        $course_id = $_POST['course_id'];
        $course_name = mysqli_real_escape_string($conn, $_POST['course_name']);
        $staff_id = $_POST['staff_id'];

        mysqli_query($conn, "UPDATE courses SET course_name='$course_name', staff_id='$staff_id' WHERE course_id='$course_id'");
        $success = "Course updated successfully!";
        // Refresh course list
        $course_query = mysqli_query($conn, "SELECT * FROM courses");
        $courses = mysqli_fetch_all($course_query, MYSQLI_ASSOC);
    }
}
?>

<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Courses List</title>
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
                <a href="studentList.php"><h3>Student</h3></a>
                <a href="" class="active"><h3>Course</h3></a>
                <a href="semesterList.php"><h3>Semesters</h3></a>
                <a href="setFees.php"><h3>Set Fees</h3></a>
                <a href=""><h3>Timetable</h3></a>
                <a href=""><h3>Clearance</h3></a>
                <a href=""><h3>Profile</h3></a>
            </div>
        </aside>

        <main>
            <h1>Courses</h1>
            <div class="insights">
                <div class="pending">
                    <span class="material-symbols-sharp">insights</span>
                    <div class="middle">
                        <div class="left">
                            <h3>All Courses</h3>
                            <h1><?php echo count($courses); ?></h1>
                        </div>
                    </div>
                </div>
            </div>

            <div class="recent-appointments">
                <div class="coursework_title">
                    <h2>Add Course</h2>
                    <a href="courses.php" class="staff-btn">Add Course</a>
                </div>
				<?php if (isset($course_to_edit)): ?>
                <div class="edit-form">
                    <h2>Edit Course</h2>
                    <form method="POST" action="coursesList.php">
                    	<input type="hidden" name="action">
                        <input type="hidden" name="edit_course">
                        <input type="hidden" name="course_id" value="<?= $course_to_edit['course_id'] ?>">
                        <label for="course_name">Course Name:</label>
                        <input type="text" id="course_name" name="course_name" value="<?= $course_to_edit['course_name'] ?>" required>
                        <label for="staff_id">Staff:</label>
                        <select id="staff_id" name="staff_id">
                            <?php foreach ($staff as $staff_id => $staff_member): ?>
                                <option value="<?= $staff_member['user_id'] ?>" <?= $course_to_edit['staff_id'] == $staff_member['user_id'] ? 'selected' : '' ?>>
                                    <?= $staff_member['fname'] ?> <?= $staff_member['lname'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
						<button type="submit" class="btn">Update Course</button>
						<button type="button" class="btn close-btn" onclick="closeEditForm()">Close</button>                      
                    </form>
                </div>
                <?php endif; ?>
                <form method="POST" action="coursesList.php">
                    <input type="hidden" name="action">
                    <table>
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Course Name</th>
                                <th>Staff Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($courses as $course): ?>
                                <tr>
                                    <td><input type="checkbox" name="selected_courses[]" value="<?= $course['course_id'] ?>"></td>
                                    <td><?= $course['course_name'] ?></td>
                                    <td><?= $staff[$course['course_id']]['fname'] ?? 'N/A' ?> <?= $staff[$course['course_id']]['lname'] ?? 'N/A' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="buttons">
                        <button type="button" class="btn" onclick="editSelectedCourse()">Edit Selected</button>
                        <button type="button" class="btn delete-btn" onclick="deleteSelectedCourses()">Delete Selected</button>
                    </div>
                    <?php if (isset($success)): ?>
                        <p class="success"><?= $success ?></p>
                    <?php endif; ?>
                    <?php if (isset($error)): ?>
                        <p class="error"><?= $error ?></p>
                    <?php endif; ?>
                </form>

                <!-- Edit Form -->
                
            </div>
        </main>
    </div>

    <script>
        function editSelectedCourse() {
            const form = document.querySelector('form');
            form.querySelector('input[name="action"]').value = 'edit';
            form.submit();
        }

        function deleteSelectedCourses() {
            const form = document.querySelector('form');
            form.querySelector('input[name="action"]').value = 'delete';
            if (confirm('Are you sure you want to delete the selected courses?')) {
                form.submit();
            }
        }
		function closeEditForm() {
            document.querySelector('.edit-form').style.display = 'none';
        }
    </script>
</body>
</html>
