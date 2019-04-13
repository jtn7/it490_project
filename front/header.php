<!DOCTYPE html>

<?php 
session_start();
?>

<html lang="en">
<head>
	<!--Icon image -->
	<link rel="icon" type="image/ico" href="assets/favicon.ico">
	
	<!--Meta / Title -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width = device-width, initial-scale = 1, shrink-to-fit=no">
	<title>Dungeons & Dragons by POGO</title>

	<!--Link / Script -->
	<link rel="stylesheet" href="style.css">
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
			<?php { ?>
				$fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				if (strpos($fullUrl, "index.php") == true){
					<ul class="nav navbar-nav">
						<li class="active"><a href="index.php">Home</a><li>
						<li><a href="createCharacter.php">Create Character</a></li>
						<li><a href="forums.php">Forums</a><li>
					</ul>
				}
				elseif (strpos($fullUrl, "createCharacter.php") == true){
					<ul class="nav navbar-nav">
						<li><a href="index.php">Home</a><li>
						<li class="active"><a href="createCharacter.php">Create Character</a></li>
						<li><a href="forums.php">Forums</a><li>
					</ul>
				}
				elseif (strpos($fullUrl, "forums.php") == true){
					<ul class="nav navbar-nav">
						<li><a href="index.php">Home</a><li>
						<li><a href="createCharacter.php">Create Character</a></li>
						<li class="active"><a href="forums.php">Forums</a><li>
					</ul>
				}
			<?php } ?>
			
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
						<form class="form-inline" action="login.php" method="POST">
							<div class="form-group md-form my-0">
								<label for="loginUN"><font color="white">ID </font></label>
								<input type="text" name="loginUN" class="form-control mr-sm-2" placeholder="ID" required="required">
							</div>
							<div class="form-group md-form my-0">
								<label for="loginPW"><font color="white">Password </font></label>
								<input type="password" name="loginPW" class="form-control mr-sm-2" placeholder="Password" required="required">
							</div>
							<button type="submit" name="loginSubmit" value="login" class="btn btn-default navbar-btn mb-2"><span class='glyphicon glyphicon-log-in'></span> Log In </button>
						</form>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class='glyphicon glyphicon-user'></span><b>Register</b> <span class="caret"></span></a>
						<ul id="login-dp" class="dropdown-menu">
							<li>
								 <div class="row">
										<div class="col-md-12">
											Login via
											<div class="social-buttons">
												<a href="#" class="btn btn-fb"><i class="fa fa-facebook"></i> Facebook</a>
												<a href="#" class="btn btn-tw"><i class="fa fa-twitter"></i> Twitter</a>
											</div>
											or create an account
											 <form class="form" action="signup.php" method="POST" accept-charset="UTF-8" id="login-nav">
													<div class="form-group">
														 <label class="sr-only" for="signUN">ID</label>
														 <input type="text" name="signUN" class="form-control" placeholder="ID" required="required">
													</div>
													<div class="form-group">
														 <label class="sr-only" for="signPW">Password</label>
														 <input type="password" name="signPW" class="form-control" placeholder="Password" required="required">
													</div>
													<div class="form-group">
														 <button type="submit" name="signSubmit" value="register" class="btn btn-primary btn-block">Register Me !</button>
													</div>
											 </form>
										</div>
								 </div>
							</li>
						</ul>
					</li>
				</ul>
			<?php } ?>
		</div>
	</nav>
