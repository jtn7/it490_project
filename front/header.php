<!DOCTYPE html>
<?php 
session_start();
?>
<html lang="en">
	<head>
		<!--Meta / Title -->
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width = device-width, initial-scale = 1, shrink-to-fit=no">
		<title>Dungeons & Dragons by POGO</title>

		<!--Link / Script -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	</head>

	<body>
		<!--Navigation Bars for all-purpose-->
		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<!--Navi Bar Left Contents-->
				<div class="navbar-header">
					<a href ="index.php"><img src="assets/dnd_logo.png" alt="Dungeons & Dragons"></a>
				</div>
				<!--Navi Bar Middle Contents-->
				<ul class="nav navbar-nav">
					<li class="active"><a href="index.php">Home</a><li>
					<li><a href="createCharacter.php">Create Character</a></li>
					<li><a href="forums.php">Forums</a><li>
				</ul>
				<!--Navi Bar Right Contents-->
				<ul class="nav navbar-nav navbar-right">
					<li><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
      				<li><a href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
				</ul>
			</div>	
			<?php
				if(isset($_SESSION['username']))
					echo
					'<div class="dropdown">
						<button class="dropbtn">Account &#9662;</button>
							<div class="dropdownContent">
							<a href="logout.php">Log Out</a>
							</div>
					</div>'
				?>
			</div>
		</nav>