<?php
require 'connect.php';

$selected_course_id = isset($_POST['course_id']) ? $_POST['course_id'] : '';
$selected_coursework_type = isset($_POST['coursework_type']) ? $_POST['coursework_type'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $coursework_type = $_POST['coursework_type'][$selected_course_id] ?? '';
    $coursework_weight = $_POST['coursework_weight'][$selected_course_id] ?? '';

    $coursework_type = mysqli_real_escape_string($conn, $coursework_type);
    $coursework_weight = mysqli_real_escape_string($conn, $coursework_weight);

    $query = " INSERT INTO coursework_type (course, coursework_type, coursework_weight)
        VALUES ('$selected_course_id', '$coursework_type', '$coursework_weight')
        ON DUPLICATE KEY UPDATE
            coursework_type = VALUES(coursework_type),
            coursework_weight = VALUES(coursework_weight)
    ";
    if (!mysqli_query($conn, $query)) {
        echo "Error: " . mysqli_error($conn);
    }
    else{
        $success = " coursework Updated successfully.";
    }

    header("Location: ../frontend/pages/staff/dashboard.php?course_id=$selected_course_id&success=1");
    exit();
}
?>
