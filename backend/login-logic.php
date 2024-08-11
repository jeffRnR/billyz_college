<?php

require '../backend/connect.php';
ini_set('display_errors', 1);

if(isset($_POST['submit'])){
	$username = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$password = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

	if(!$username){
		$_SESSION['login'] = "Username required!";
		header ('location: ../frontend/pages/staff/staffLogin.php');
		exit;
	}elseif(!$password){
		$_SESSION['login'] = "Password required!";
		header ('location: ../frontend/pages/staff/staffLogin.php');
		exit;
	}else{
		$fetch_user_query = "SELECT * FROM users WHERE username='$username' ";
		$fetch_user_result = mysqli_query($conn, $fetch_user_query);

		$fetch_student_query = "SELECT * FROM students WHERE admission_no='$username'";
		$fetch_student_result = mysqli_query($conn, $fetch_user_query);

		if(mysqli_num_rows($fetch_user_result) == 1){
			$user_record = mysqli_fetch_assoc($fetch_user_result);
			$db_password = $user_record['password'];

			// Verify the provided password against the hashed password in the database
			if(password_verify($password, $hashed_password = password_hash($user_record['password'], PASSWORD_DEFAULT))){
				$_SESSION['user_id'] = $user_record['user_id'];

				if($user_record['role'] == 'staff'){
					$_SESSION['user_is_staff'] = true;
					$_SESSON['staff_id'] = $user_record['user_id'];
					header ('location: ../frontend/pages/staff/dashboard.php');
					exit;
				}

				if($user_record['role'] == 'admin'){
					$_SESSION['user_is_admin'] = true;
					// Redirect to admin dashboard
					header('location: ../frontend/pages/admin/dashboard.php');
					exit;
				}

				// if($user_record['role'] == 'student'){
				// 	$_SESSION['user_is_student'] = true;
				// 	$_SESSION['student_id'] = $user_record['user_id'];
				// 	header ('location: ../frontend/pages/student/dashboard.php');
				// 	exit;
				// }			
			else if(mysqli_num_rows($fetch_student_result) == 1){
				$student_record = mysqli_fetch_assoc($fetch_student_result);
				$db_password = $student_record['password'];
				if(password_verify($password, $hashed_password = password_hash($user_record['password'], PASSWORD_DEFAULT))){
					$_SESSION['student_id'] = $user_record['student_id'];
					$_SESSION['user_is_student'] = true;
					$_SESSION['student_id'] = $student_record['student_id'];
					header ('location: ../frontend/pages/student/dashboard.php');
					exit;
				}
			}
			}else{
				$_SESSION['login'] = 'Please check your inputs';
				header('location: ../frontend/pages/staff/staffLogin.php');
				exit;
			}

		}
		else {
			$_SESSION['login'] = "User not found";
			header('location: ../frontend/pages/staff/staffLogin.php');
			exit;
		}
	}

	if(isset($_SESSION['login'])){
		$_SESSION['login-data'] = $_POST;
		header ('location: ../frontend/pages/staff/staffLogin.php');
		exit;
	}
}else{
	header ("location: ../frontend/pages/staff/staffLogin.php");
	exit;
}
?>
