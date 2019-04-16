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
		header("Location: signup.php?signup=S");
	}
	else {
		header("Location: signup.php?signup=F");
	}
}
?>

<?php include 'header_plain.php'?>

<!-- Sweet Alert for the Registration-->
<?php
	$fullUrl 	= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	if (strpos($fullUrl, "signup=F") == true){
		echo '<script type="text/javascript">swal("Wait a minute!", "Registration error :(", "error");</script>';
	}
	elseif (strpos($fullUrl, "signup=S") == true){
		echo '<script type="text/javascript">swal("Great job!", "Registration completed :)", "success");</script>';
	}
?>

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

<!-- Custom scripts for signup.php page-->
<script src="js/template.min.js"></script>

<?php include 'footer.php';?>