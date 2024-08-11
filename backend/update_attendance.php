<?php
require 'connect.php';
session_start();

$selected_course_id = isset($_POST['course_id']) ? mysqli_real_escape_string($conn, $_POST['course_id']) : '';
$selected_coursework_type = isset($_POST['coursework_type']) ? mysqli_real_escape_string($conn, $_POST['coursework_type']) : '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if 'present' key exists in POST data
    if (isset($_POST['present'])) {
        // Loop through each student's attendance data
        foreach ($_POST['present'] as $admissionNo => $present) {

            $date = isset($_POST['date'][$admissionNo]) ? mysqli_real_escape_string($conn, $_POST['date'][$admissionNo]) : '';
            $startTime = isset($_POST['start_time'][$admissionNo]) ? mysqli_real_escape_string($conn, $_POST['start_time'][$admissionNo]) : '';
            $endTime = isset($_POST['end_time'][$admissionNo]) ? mysqli_real_escape_string($conn, $_POST['end_time'][$admissionNo]) : '';
            $duration = isset($_POST['duration'][$admissionNo]) ? mysqli_real_escape_string($conn, $_POST['duration'][$admissionNo]) : '';

            $present = isset($present) ? 1 : 0;

            $query = "
                INSERT INTO student_attendance (student_admission, course_id, coursework_type, date, start_time, end_time, duration, present)
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
        }
    }

    //header("Location: ../frontend/pages/staff/attendance.php?course_id=$selected_course_id");
    exit();
}
?>
