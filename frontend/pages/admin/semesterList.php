<?php
require '../../../backend/connect.php';
$id = $_SESSION['user_id'];

// Fetch semester records
$semester_query = mysqli_query($conn, "SELECT * FROM semester");
$semesters = mysqli_fetch_all($semester_query, MYSQLI_ASSOC);

// Fetch courses for semester display
$course_query = mysqli_query($conn, "SELECT * FROM courses");
$courses = mysqli_fetch_all($course_query, MYSQLI_ASSOC);

// Map course IDs to names
$course_list = [];
foreach ($courses as $course) {
    $course_list[$course['course_id']] = $course['course_name'];
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $selected_semesters = $_POST['selected_semesters'] ?? [];

    if ($action === 'delete') {
        foreach ($selected_semesters as $semester_id) {
            mysqli_query($conn, "DELETE FROM semester WHERE semester_id = '$semester_id'");
        }
        $success = "Selected semesters have been deleted successfully!";
    }

    if ($action === 'edit' && count($selected_semesters) === 1) {
        $semester_id = $selected_semesters[0];
        // Display semester details for inline editing
        $edit_query = mysqli_query($conn, "SELECT * FROM semester WHERE semester_id = '$semester_id'");
        $semester_to_edit = mysqli_fetch_array($edit_query, MYSQLI_ASSOC);
    } elseif ($action === 'edit' && count($selected_semesters) > 1) {
        $error = "Please select only one semester to edit.";
    }
}
?>

<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Semesters List</title>
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
                <a href="coursesList.php"><h3>Course</h3></a>
                <a href="" class="active"><h3>Semesters</h3></a>
                <a href="setFees.php"><h3>Set Fees</h3></a>
                <a href=""><h3>Timetable</h3></a>
                <a href=""><h3>Clearance</h3></a>
                <a href=""><h3>Profile</h3></a>
            </div>
        </aside>

        <main>
            <h1>Semesters</h1>
            <div class="recent-appointments">
                <div class="coursework_title">
                    <h2>Add Semester</h2>
                    <a href="semester.php" class="staff-btn">Add Semester</a>
                </div>
                <form method="POST" action="semesterList.php">
                    <table>
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Year of Study</th>
                                <th>Semester Number</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Course</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($semesters as $semester): ?>
                                <tr>
                                    <td><input type="checkbox" name="selected_semesters[]" value="<?= $semester['semester_id'] ?>"></td>
                                    <td><?= $semester['year_of_study'] ?></td>
                                    <td><?= $semester['semester_number'] ?></td>
                                    <td><?= $semester['start_date'] ?></td>
                                    <td><?= $semester['end_date'] ?></td>
                                    <td><?= $course_list[$semester['course_id']] ?? 'N/A' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="buttons">
                        <button type="button" class="btn" onclick="editSelectedSemester()">Edit Selected</button>
                        <button type="button" class="btn delete-btn" onclick="deleteSelectedSemesters()">Delete Selected</button>
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
    </div>

    <script>
        function editSelectedSemester() {
            const form = document.querySelector('form');
            form.querySelector('input[name="action"]').value = 'edit';
            form.submit();
        }

        function deleteSelectedSemesters() {
            const form = document.querySelector('form');
            form.querySelector('input[name="action"]').value = 'delete';
            if (confirm('Are you sure you want to delete the selected semesters?')) {
                form.submit();
            }
        }
    </script>
</body>
</html>
