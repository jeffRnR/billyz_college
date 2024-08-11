<?php
require "../../../backend/connect.php";
$username =$_SESSION['login-data']['username'] ?? null;
$password =$_SESSION['login-data']['password'] ?? null;

unset($_SESSION['login-data']);

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login</title>
	<link rel="stylesheet" type="text/css" href="../../style/staffLogin.css">
	<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&family=Rubik+Moonrocks&display=swap" rel="stylesheet">
</head>
<body>
	<nav>
		<div class="cointainer nav_container"> 
			<a href="../pages/Index.html" class="nav_logo"><h4>Billyz College</h4></a>
			<ul id="nav_menu" class="nav_items">
				<li><a href="../pages/Index.html">Home</a></li>
				<li><a href="../pages/Index.html" target="_blank">About</a></li>					
							
			</ul>
			<button id="open_nav-btn" ><i class="uil uil-bars"></i></button>			
			<button id="close_nav-btn" class="close_menu"><i class="uil uil-times-square"></i></button>
		</div>
	</nav>

	<section class="login">
		<div class="container login_container">
			<h4> Staff Login</h4>

			<?php if(isset($_SESSION['login'])) : ?>
				<div class="alert_message error">
					<p>
						<?= $_SESSION['login'];
						unset($_SESSION['login'])?>
					</p>
				</div>
			<?php endif?>				
							
			<form action="../../../backend/login-logic.php" method="post">							
				<input type="username" name="username" value="<?= $username?>" placeholder="Username" >

				<input type="password" name="password" value="<?= $password?>" placeholder="Password">				

				<button type="submit" name="submit" class="btn">Login</button>				
			</form>
			
		</div>
	</section>

	<script type="text/javascript" src="../js/main.js"></script>
</body>
</html>