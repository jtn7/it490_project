<!DOCTYPE html>
<?php 
session_start();
?>
<html lang="en">
<head>
	<!--Icon image -->
	<link rel="icon" href="assets/dnd_icon.ico">
	
	<!--Meta / Title -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width = device-width, initial-scale = 1, shrink-to-fit=no">
	<title>Dungeons & Dragons by POGO</title>

	<!--Link / Script -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>

<body>
	<!--Navigation Bars for all-purpose-->
	<nav class="navbar navbar-inverse justify-content-between">
		<div class="container-fluid">
			<!--Navi Bar Left Contents-->
			<div class="navbar-header">
				<a class ="navbar-brand" href ="index.php"><img src="assets/dnd_logo.png" alt="Dungeons & Dragons" style="width:80px;"></a>
			</div>
			
			<!--Navi Bar Middle Contents-->
			<ul class="nav navbar-nav">
				<li class="active"><a href="index.php">Home</a><li>
				<li><a href="createCharacter.php">Create Character</a></li>
				<li><a href="forums.php">Forums</a><li>
			</ul>
			
			<!--Navi Bar Right Contents-->
			<?php if(isset($_SESSION['username'])){ ?>
				<!--Logged on status menu bar-->
				<ul class='nav navbar-nav navbar-right'>
					<li><a href='#'><span class='glyphicon glyphicon-user'></span> User</a></li>
					<li><a href='logout.php'><span class='glyphicon glyphicon-log-out'></span> Log Out</a></li>
				</ul>
			<?php } ?>
			
			<?php if(!isset($_SESSION['username'])){ ?>
				<!--Logged off status menu bar-->
				<ul class="nav navbar-nav navbar-right">
					<li>
						<form class="form-inline" action="" method="POST">
							<div class="form-group md-form my-0">
								<label for="loginUN">ID </label>
								<input type="text" class="form-control mr-sm-2" placeholder="ID" required="required">
							</div>
							<div class="form-group md-form my-0">
								<label for="loginPW">Password </label>
								<input type="password" class="form-control mr-sm-2" placeholder="Password" required="required">
							</div>
							<button type="submit" class="btn btn-default navbar-btn mb-2"><span class='glyphicon glyphicon-log-in'></span> Log In </button>
						</form>
					</li>
					<li>
						<button type="button" class="btn btn-default navbar-btn mb-2"><a href='signup.php'><span class='glyphicon glyphicon-user'></span> Sign Up </a></button>
					</li>
				</ul>
			<?php } ?>
		</div>
	</nav>
