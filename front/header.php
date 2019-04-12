<!DOCTYPE html>

<?php
session_start();
require_once 'RPC.php';
require_once 'logging/LogWriter.php';
use logging\LogWriter;
use rabbit\RPC;
$logger = new LogWriter('/var/log/dnd/frontend.log');
$logger->info('login page accessed');

if(!empty($_POST['login'])){
	$logger->info('POST recieved');
	$logger->info($_POST);
	$login_rpc = new RPC("login");
	$user = $_POST['loginUN'];
	$usernamepasswd = serialize(array($user, $_POST['loginPW']));
	$response = $login_rpc->call($usernamepasswd);
	if ($response === 'S'){
		$logger->info('Successful Verification');
		$_SESSION['username'] = $user;
		header("Location: index.php");
	} else {	
		header("Location: login.php?success=LoginFail");
	}
}
if (isset($_GET['success']) && $_GET['success'] === 'LoginFail') {
	echo "<script type='text/javascript'>alert('Failed to Log In! Please try Again.');</script>";
}

if(!empty($_POST['register'])){
	$signup_rpc = new RPC("register");
	$user = $_POST['signUN'];
	$usernamepasswd = serialize(array($user, $_POST['signPW']));
	$response = $signup_rpc->call($usernamepasswd);
	if ($response==="S"){
		header('Location: login.php');
	}
	else {
		header('Location: signup.php?success=RegisterFail');
	}
}
if(isset($_GET['success'])){
	if($_GET['success']==="RegisterFail"){
		echo "<script type='text/javascript'>alert('Error! Username may have already been registered. Try Again.');</script>";
	}
}
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
								<label for="loginUN"><b>ID </b></label>
								<input type="text" name="loginUN" class="form-control mr-sm-2" placeholder="ID" required="required">
							</div>
							<div class="form-group md-form my-0">
								<label for="loginPW"><b>Password </b></label>
								<input type="password" name="loginPW" class="form-control mr-sm-2" placeholder="Password" required="required">
							</div>
							<button type="submit" name="loginSubmit" class="btn btn-default navbar-btn mb-2" value="login"><span class='glyphicon glyphicon-log-in'></span> Log In </button>
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
											 <form class="form" action="" method="POST" accept-charset="UTF-8" id="login-nav">
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
