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

// Date filter
$selected_date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$selected_start_time = isset($_GET['start_time']) ? $_GET['start_time'] : date('H:i');
$selected_end_time = isset($_GET['end_time']) ? $_GET['end_time'] : date('H:i');

$students_query = mysqli_query($conn, "    SELECT 
        s.user_id, 
        s.fname, 
        s.lname,
        sa.date,
        sa.start_time,
        sa.end_time,
        sa.present
    FROM 
        users s
    LEFT JOIN 
        student_attendance sa 
    ON 
        s.user_id = sa.student_admission 
    AND 
        sa.date = '$selected_date'
    WHERE 
        s.course = '$selected_course_id' 
    AND
        s.role = 'student'
    AND 
        s.user_id LIKE '%$searchTerm%'
");

$students = mysqli_fetch_all($students_query, MYSQLI_ASSOC);


// Handle attendance update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['admission_no'] as $admission_no) {
        $date = $_POST['date'][$admission_no];
        $start_time = $_POST['start_time'][$admission_no];
        $end_time = $_POST['end_time'][$admission_no];
        $present = isset($_POST['present'][$admission_no]) ? 1 : 0;
        $duration = (strtotime($end_time) - strtotime($start_time)) / 3600; // Duration in hours

        $query = "INSERT INTO student_attendance (student_admission, date, start_time, end_time, duration, present)
                  VALUES ('$admission_no', '$date', '$start_time', '$end_time', '$duration', '$present')
                  ON DUPLICATE KEY UPDATE
                  start_time = VALUES(start_time),
                  end_time = VALUES(end_time),
                  duration = VALUES(duration),
                  present = VALUES(present)";

        mysqli_query($conn, $query);
    }
    $success = "Attendance records updated successfully!";
}

$select_students = mysqli_query($conn, "SELECT * FROM users where role = 'student'");
$admission_nos = mysqli_fetch_all($select_students, MYSQLI_ASSOC)
?>

<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Attendance</title>
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
        function updateTableTimesAndDurations() {
            const selectedDate = document.getElementById('selected_date').value;
            const selectedStartTime = document.getElementById('selected_start_time').value;
            const selectedEndTime = document.getElementById('selected_end_time').value;

            document.querySelectorAll('#attendanceForm tbody tr').forEach(row => {
                const admissionNo = row.querySelector('td').textContent.trim();
                
                // Update date, start time, and end time in the table
                document.querySelector(`input[name="date[${admissionNo}]"]`).value = selectedDate;
                document.querySelector(`input[name="start_time[${admissionNo}]"]`).value = selectedStartTime;
                document.querySelector(`input[name="end_time[${admissionNo}]"]`).value = selectedEndTime;

                // Calculate duration
                calculateDurationAndAbsent(admissionNo);
            });
        }

        function calculateDurationAndAbsent(admissionNo) {
            const startTime = document.querySelector(`input[name="start_time[${admissionNo}]"]`).value;
            const endTime = document.querySelector(`input[name="end_time[${admissionNo}]"]`).value;

            // Calculate duration
            const start = new Date(`1970-01-01T${startTime}:00`);
            const end = new Date(`1970-01-01T${endTime}:00`);
            const duration = (end - start) / (1000 * 60 * 60); // Duration in hours

            document.querySelector(`input[name="duration[${admissionNo}]"]`).value = duration;

            // Update total duration and time present
            updateTotalDurationAndAbsent(admissionNo);
        }
    </script>
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
				<a href="coursework.php">					
					<h3>Coursework</h3>
				</a>
				<a href="" class="active">
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
            <h1>Students Attendance</h1>
			<div class="recent-appointments">
                <div class="coursework_title">
                <form id="selectionForm">
                    <select name="course_id" onchange="updateTableTimesAndDurations()">
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= $course['course_id'] ?>" <?= $course['course_id'] == $selected_course_id ? 'selected' : '' ?>>
                                <?= $course['course_name']?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="date" id="selected_date" name="date" value="<?= $selected_date ?>" onchange="updateTableTimesAndDurations()">
                    <input type="time" id="selected_start_time" name="start_time" value="<?= $selected_start_time ?>" onchange="updateTableTimesAndDurations()">
                    <input type="time" id="selected_end_time" name="end_time" value="<?= $selected_end_time ?>" onchange="updateTableTimesAndDurations()">
                </form>
                </div>
				
				<form method="POST" action="attendance.php" id="attendanceForm">
                    <?php if (isset($success)): ?>
                        <p class="success"><?= $success ?></p>
                    <?php endif; ?>
                    <?php if (isset($error)): ?>
                        <p class="error"><?= $error ?></p>
                    <?php endif; ?>
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
                                    <td><?= $student['user_id'] ?></td>
                                    <td><?= $student['fname'] ?></td>
                                    <td><?= $student['lname'] ?></td>
                                    <td><input type="date" name="date[<?= $student['user_id'] ?>]" readonly></td>
                                    <td><input type="time" name="start_time[<?= $student['user_id'] ?>]" readonly></td>
                                    <td><input type="time" name="end_time[<?= $student['user_id'] ?>]" readonly></td>
                                    <td><input type="number" name="duration[<?= $student['user_id'] ?>]" readonly></td>
                                    <td class="checkbox"><input type="checkbox" name="present[<?= htmlspecialchars($student['user_id']) ?>]" value="1" <?= $student['present'] ? 'checked' : '' ?>></td>
                                    <td id="percentAbsent_<?= $student['user_id'] ?>">0%</td>
                                </tr>
                                <input type="hidden" name="admission_no[]" value="<?= $student['user_id'] ?>">
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
                                <li><a href="#"><?= $admission['user_id'] ?></a></li>
                            <?php endforeach; ?>                            
                        </ul>										
					</div>
				</div>		
			</div>
        </div>
	</div>
</body>
</html>
