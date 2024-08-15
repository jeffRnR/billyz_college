<?php
require 'connect.php';

$selected_course_id = isset($_POST['course_id']) ? mysqli_real_escape_string($conn, $_POST['course_id']) : '';
$selected_coursework_type = isset($_POST['coursework_type']) ? mysqli_real_escape_string($conn, $_POST['coursework_type']) : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $upload_title = isset($_POST['upload_title']) ? mysqli_real_escape_string($conn, $_POST['upload_title']) : '';
    $due_date = isset($_POST['due_date']) ? mysqli_real_escape_string($conn, $_POST['due_date']) : '';
    $due_time = isset($_POST['due_time']) ? mysqli_real_escape_string($conn, $_POST['due_time']) : '';

    if (isset($_FILES['upload_file']) && $_FILES['upload_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['upload_file']['tmp_name'];
        $fileName = $_FILES['upload_file']['name'];
        $fileSize = $_FILES['upload_file']['size'];
        $fileType = $_FILES['upload_file']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Set the destination path
        $uploadFileDir = '../uploads/';
        $dest_path = $uploadFileDir . $fileName;

        // Move the file to the destination directory
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $upload_file = $dest_path;
        } else {
            echo "Error uploading file.";
            exit();
        }
    } else {
        $upload_file = ''; 
    }

    // Insert data into the database
    $query = "INSERT INTO uploads (course, coursework_type, upload_title, due_date, due_time, upload_file)
        VALUES ('$selected_course_id','$selected_coursework_type', '$upload_title', '$due_date', '$due_time', '$upload_file')
        ON DUPLICATE KEY UPDATE
            upload_title = VALUES(upload_title),
            due_date = VALUES(due_date),
            due_time = VALUES(due_time),
            upload_file = VALUES(upload_file)
    ";

    if (!mysqli_query($conn, $query)) {
        echo "Error: " . mysqli_error($conn);
    }else{
        $success = "Updated successfully.";
    }

    // Redirect or show success message
    header("Location: ../frontend/pages/staff/uploads.php");
    exit();
}
?>
