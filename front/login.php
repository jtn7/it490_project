<?php
session_start();
require_once 'RPC.php';
require_once 'logging/LogWriter.php';
use logging\LogWriter;
use rabbit\RPC;

$logger = new LogWriter('/var/log/dnd/frontend.log');

$logger->info('login page accessed');
if(!empty($_POST)){
	$logger->info('POST recieved');
	$logger->info($_POST);
	$login_rpc = new RPC("login");
	$user = $_POST['loginUN'];
	$usernamepasswd = serialize(array($user, $_POST['loginPW']));

	$response = $login_rpc->call($usernamepasswd);
	if ($response==="S"){
		$logger->info('Successful Verification');
		$_SESSION['username'] = $user;
		header("Location: index.php");
	}
	else {
		header("Location: login.php?success=F");
	}
}

if (isset($_GET['success']) && $_GET['success'] === 'F') {
/*	echo '<script type="text/javascript">swal("Uh Oh", "Login Failed :(", "error");</script>';*/
}
?>

<?php include 'header.php' ?>

<div class="uaBody">
	<div class="content">
		<h1>Log In</h1>
		<form action="" method="POST">
			<h4>Username:</h4>
			<input type="text" name="loginUN" required>
			<h4>Password:</h4>
			<input type="password" name="loginPW" required>
			<br><br>
			Don't have an account? <a href="signup.php">Sign Up</a><br><br>
			<input type="submit" name="loginSubmit" value="Log In">
		</form>
	</div>
</div>


<?php include 'footer.php' ?>
