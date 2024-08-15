<?php
require '../backend/connect.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Optional: Clear existing data
// Uncomment the following lines if you want to clear the students table before inserting new data
/*
$clear_students_query = "TRUNCATE TABLE students";
if (!mysqli_query($conn, $clear_students_query)) {
    die("Error clearing students table: " . mysqli_error($conn));
}
*/

// Fetch all students from the users table
$fetch_students_query = mysqli_query($conn, "SELECT user_id, fname, lname, phone_number, email, password, date_of_birth, course, next_of_kin, next_of_kin_contact FROM users WHERE `role`='student'");
if (!$fetch_students_query) {
    die("Error fetching students: " . mysqli_error($conn));
}

$fetch_students_result = mysqli_fetch_all($fetch_students_query, MYSQLI_ASSOC);

foreach ($fetch_students_result as $student) {
    $admission_no = $student['user_id']; // Mapping user_id to admission_no
    $fname = $student['fname'];
    $lname = $student['lname'];
    $phone_number = $student['phone_number'];
    $email = $student['email'];
    $password = $student['password'];
    $date_of_birth = $student['date_of_birth'];
    $course = $student['course'];
    $next_of_kin = $student['next_of_kin'];
    $next_of_kin_contact = $student['next_of_kin_contact'];

    // Insert or update the student record in the students table
    $stmt = $conn->prepare("INSERT INTO students 
        (admission_no, fname, lname, phone_number, email, `password`, date_of_birth, course, next_of_kin, next_of_kin_contact) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
        fname = VALUES(fname),
        lname = VALUES(lname),
        phone_number = VALUES(phone_number),
        email = VALUES(email),
        `password` = VALUES(`password`),
        date_of_birth = VALUES(date_of_birth),
        course = VALUES(course),
        next_of_kin = VALUES(next_of_kin),
        next_of_kin_contact = VALUES(next_of_kin_contact)"
    );

    $stmt->bind_param("ssssssssss", $admission_no, $fname, $lname, $phone_number, $email, $password, $date_of_birth, $course, $next_of_kin, $next_of_kin_contact);

    if (!$stmt->execute()) {
        echo "Error: " . $stmt->error;
    }
}

$stmt->close();
$conn->close();

echo "Data migration completed.";
?>
