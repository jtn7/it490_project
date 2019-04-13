<?php
session_start();
require_once 'RPC.php';
require_once 'logging/LogWriter.php';
use logging\LogWriter;
use rabbit\RPC;

$logger = new LogWriter('/var/log/dnd/frontend.log');

$logger->info('sign up page accessed');
if(!empty($_POST)){
	$logger->info('POST recieved');
	$logger->info($_POST);
	$signup_rpc = new RPC("register");
	$user = $_POST['signUN'];
	$usernamepasswd = serialize(array($user, $_POST['signPW']));
	
	$response = $signup_rpc->call($usernamepasswd);
	if ($response==="S"){
		$logger->info('Successful Registration');
		header('Location: login.php');
	}
	else {
		header('Location: signup.php?success=F');
	}
}

if(isset($_GET['success'])){
	if($_GET['success']==="F"){
		echo "<script type='text/javascript'>alert('Error! Username may have already been registered. Try Again.');</script>";
	}
}
?>

<?php include 'header.php';?>

<div class="uaBody">
	<div class="content">
		<h1>Sign Up</h1>
		<form action="" method="POST">
			<h4>Enter Username:</h4>
			<input type="text" name="signUN" required>
			<h4>Enter Password:</h4>
			<input type="password" name="signPW" required>
			<br><br>
			Already have an account? <a href="login.php">Log In</a><br><br>
			<input type="submit" name="signSubmit" value="Sign Up">
		</form>
	</div>
</div>

<?php include 'footer.php';?>
