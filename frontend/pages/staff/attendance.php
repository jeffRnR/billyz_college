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
$selected_student = isset($_GET['type']) ? $_GET['type'] : 'dummy1';

$students_query = mysqli_query($conn, "SELECT s.admission_no, 
        s.fname, 
        s.lname
    FROM 
        students s
    LEFT JOIN 
        student_attendance sa 
    ON 
        s.admission_no = sa.student_admission 
    WHERE 
        s.course = '$selected_course_id'
    AND s.admission_no LIKE '%$searchTerm%'
");
$students = mysqli_fetch_all($students_query, MYSQLI_ASSOC);

// Fetch admission numbers
$admission_no_query = mysqli_query($conn, "SELECT admission_no FROM students WHERE course = '$selected_course_id' AND admission_no LIKE '%$searchTerm%'");
$admission_nos = mysqli_fetch_all($admission_no_query, MYSQLI_ASSOC);

?>

<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>attendance</title>
	<link rel="stylesheet" type="text/css" href="../../style/staffDashboard.css">
	<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp:opsz,wght,FILL,GRAD@48,400,0,0" />

    <script>
        function calculateDurationAndAbsent(admissionNo) {
            const startTime = document.querySelector(`input[name="start_time[${admissionNo}]"]`).value;
            const endTime = document.querySelector(`input[name="end_time[${admissionNo}]"]`).value;
            const present = document.querySelector(`input[name="present[${admissionNo}]"]`).checked;

            // Calculate duration
            const start = new Date(`1970-01-01T${startTime}:00`);
            const end = new Date(`1970-01-01T${endTime}:00`);
            const duration = (end - start) / (1000 * 60 * 60); // Duration in hours

            document.querySelector(`input[name="duration[${admissionNo}]"]`).value = duration;

            // Update total duration and time present
            updateTotalDurationAndAbsent(admissionNo);
        }

        function updateTotalDurationAndAbsent(admissionNo) {
            let totalDuration = 0;
            let totalTimePresent = 0;

            document.querySelectorAll('tbody tr').forEach(row => {
                const admissionNo = row.querySelector('td').textContent.trim();
                const duration = parseFloat(document.querySelector(`input[name="duration[${admissionNo}]"]`).value);
                const present = document.querySelector(`input[name="present[${admissionNo}]"]`).checked;

                totalDuration += duration;
                if (present) {
                    totalTimePresent += duration;
                }
            });

            const percentAbsent = ((totalDuration - totalTimePresent) / totalDuration) * 100;
            document.querySelector(`td#percentAbsent_${admissionNo}`).textContent = percentAbsent.toFixed(2) + '%';
        }
    </script>
</head>
<body>
	<div class="container">
		<aside>
			<div class="top">
				<div class="logo">
					<a href="../patient/index.php" class="nav_logo"><h2>Billyz College</h2></a>
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
				<a href="coursework.php">					
					<h3>Coursework</h3>
				</a>
				<a href="" class="active">
					<h3>Attendance</h3>
				</a>
				<a href="" >
					<h3>Subbmissions</h3>
				</a>
				<a href="">
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
            <h1>Students Attendance</h1>
			<div class="recent-appointments">
                <div class="coursework_title">
                    <form method="GET" action="coursework.php">
                        <select name="course_id" onchange="this.form.submit()">
                            <?php foreach ($courses as $course): ?>
                                <option value="<?= $course['course_id'] ?>" <?= $course['course_id'] == $selected_course_id ? 'selected' : '' ?>>
                                    <?= $course['course_name']?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>
				
				<form method="POST" action="../../../backend/update_attendance.php">
                    <table>
                        <thead>
                            <tr>
                                <th>Admission No</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Date</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Duration</th>
                                <th>Present</th>
                                <th>% Absent</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                                <tr>
                                    <td><?= $student['admission_no'] ?></td>
                                    <td><?= $student['fname'] ?></td>
                                    <td><?= $student['lname'] ?></td>
                                    <td><input type="date" name="date[<?= $student['admission_no'] ?>]" value="<?= $student['date'] ?? '' ?>"></td>
                                    <td><input type="time" name="start_time[<?= $student['admission_no'] ?>]" onchange="calculateDurationAndAbsent('<?= $student['admission_no'] ?>')" value="<?= $student['start_time'] ?? '' ?>"></td>
                                    <td><input type="time" name="end_time[<?= $student['admission_no'] ?>]" onchange="calculateDurationAndAbsent('<?= $student['admission_no'] ?>')" value="<?= $student['end_time'] ?? '' ?>"></td>
                                    <td><input type="number" name="duration[<?= $student['admission_no'] ?>]" readonly></td>
                                    <td><input type="checkbox" name="present[<?= $student['admission_no'] ?>]" onchange="updateTotalDurationAndAbsent('<?= $student['admission_no'] ?>')" <?= $student['present'] ? 'checked' : '' ?>></td>
                                    <td id="percentAbsent_<?= $student['admission_no'] ?>">0%</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button type="submit" class="btn">Update Attendance</button>
                </form>
			</div>
		</main>

		<!----------------------------------------------------------->

		<div class="right">
            
			<div class="recent-updates">
				<h2>Students</h2>
                <div class="search-container">
                    <form method="GET" action="attendance.php" class="attendance_form"> 
                        <input type="text" name="search" placeholder="Search by admission number..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                        <button type="submit" class="btn">Search</button>
                    </form>
                </div>
				<div class="attendance_students">					
					<div class="student_list">
                        <ul class="student">
                            <?php foreach ($admission_nos as $admission): ?>
                                <li><a href="#"><?= $admission['admission_no'] ?></a></li>
                            <?php endforeach; ?>                            
                        </ul>										
					</div>
				</div>		
			</div>
        </div>
	</div>
</body>
</html>