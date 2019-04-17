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
		header("Location: login.php?signup=S");
	}
	else {
		header("Location: signup.php?signup=F");
	}
}
?>

<?php include 'header_plain.php' ?>

<!-- Sweet Alert for the Registration-->
<?php
	$fullUrl 	= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	if (strpos($fullUrl, "signup=F") == true){
		echo '<script type="text/javascript">swal("Wait a minute!", "Registration error :(", "error");</script>';
	}
?>

<!-- Register password double check using javascript-->
<script>
function check_pass() {
    if (document.getElementById('signPW').value == document.getElementById('signPW-confirm').value) {
        document.getElementById('signSubmit').disabled = false;
    } 
		else if (document.getElementById('signPW-confirm').value !== "") {	
				swal("Wait a minute!", "Password does not match. . .", "error");
        document.getElementById('signSubmit').disabled = true;
		}
  }
</script>

<body class="bg-gradient-danger">
<div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">
      	<div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">

            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block">
			  				<img src="assets/signup_background.jpg" alt="Dungeons & Dragons" class="img-responsive" style="max-width:100%; max-height:100%;">
			 				</div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
				  		<br><br>
                <h1 class="h4 text-gray-900 mb-4"><b>Create an Account!</b></h1>
							<br><br>
                  </div>
                  <form class="user" action="signup.php" method="POST">
                    <div class="form-group">
						<input type="text" class="form-control form-control-user" name="signUN" required="required" placeholder="Enter User Name ...">
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control form-control-user" name="signPW" id="signPW" required="required" placeholder="Password" onchange='check_pass();'>
                    </div>
										<div class="form-group">
                      <input type="password" class="form-control form-control-user" id="signPW-confirm" required="required" placeholder="Confirm your password" onchange='check_pass();'>
                    </div>
										<div class="form-group">
                    	<input type="submit" class="btn btn-primary btn-user btn-block" name="signSubmit" id="signSubmit" value="Register">
                    </div>
							<br>
							<hr>
							<br>
                    <a href="#" class="btn btn-google btn-user btn-block">
                      <i class="fab fa-google fa-fw"></i> Register with Google
                    </a>
                    <a href="#" class="btn btn-facebook btn-user btn-block">
                      <i class="fab fa-facebook-f fa-fw"></i> Register with Facebook
                    </a>
                  </form>
							<br>
							<hr>
							<br>
					<div class="text-center">
						<a class="small" href="forgot-password.php">Forgot Password?</a>
					</div>
					<div class="text-center">
						<a class="small" href="login.php">Already have an account? Log in!</a>
					</div>
                </div>
              </div>
            </div>
        </div>
        </div>
      	</div>
    </div>
</div>

<!-- Custom empty space for signup.php page-->
<div class="row justify-content-center" style="margin-bottom:200px;">

</div>

<!-- Custom scripts for signup.php page-->
<script src="js/template.min.js"></script>

</body>

<?php include 'footer.php' ?>