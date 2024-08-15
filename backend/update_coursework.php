<?php
require 'connect.php';

// Retrieve the selected course and coursework type from GET parameters
$selected_course_id = isset($_POST['course_id']) ? $_POST['course_id'] : '';
$selected_coursework_type = isset($_POST['coursework_type']) ? $_POST['coursework_type'] : '';


// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Loop through each student's attendance data
    foreach ($_POST['present'] as $admissionNo => $present) {
        // Retrieve other data for this student
        $date = $_POST['date'][$admissionNo] ?? '';
        $startTime = $_POST['start_time'][$admissionNo] ?? '';
        $endTime = $_POST['end_time'][$admissionNo] ?? '';
        $duration = $_POST['duration'][$admissionNo] ?? '';

        $present = isset($present) ? 1 : 0;
        $date = mysqli_real_escape_string($conn, $date);
        $startTime = mysqli_real_escape_string($conn, $startTime);
        $endTime = mysqli_real_escape_string($conn, $endTime);
        $duration = mysqli_real_escape_string($conn, $duration);

        $query = "INSERT INTO student_attendance (student_admission, course_id, coursework_type, `date`, start_time, end_time, duration, present)
            VALUES ('$admissionNo', '$selected_course_id', '$selected_coursework_type', '$date', '$startTime', '$endTime', '$duration', $present)
            ON DUPLICATE KEY UPDATE
                date = VALUES(date),
                start_time = VALUES(start_time),
                end_time = VALUES(end_time),
                duration = VALUES(duration),
                present = VALUES(present)
        ";
        if (!mysqli_query($conn, $query)) {
            echo "Error: " . mysqli_error($conn);
        }
        else{
            $success = "Updated successfully.";
        }
    }

    // Redirect back to the same page with updated data
    header("Location: ../frontend/pages/staff/coursework.php?course_id=$selected_course_id&coursework_type=$selected_coursework_type&success=1");
    exit();
}
?>
