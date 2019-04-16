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
		header("Location: login.php?login=F");
	}
}
?>

<?php include 'header_plain.php' ?>

<!-- Sweet Alert for the login-->
<?php
	$fullUrl 	= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	if (strpos($fullUrl, "login=F") == true){
		echo '<script type="text/javascript">swal("Wait a minute!", "Wrong credentials :(", "error");</script>';
	}
?>

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
			  	<img src="assets/login_background.jpg" alt="Dungeons & Dragons" class="img-fluid">
			  </div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
				  <br><br>
                    <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
					<br><br>
                  </div>
                  <form class="user" action="login.php" method="POST">
                    <div class="form-group">
						<input type="text" class="form-control form-control-user" name="loginUN" required="required" placeholder="Enter User Name ...">
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control form-control-user" name="loginPW" required="required" placeholder="Password">
                    </div>
                    <div class="form-group">
                      <div class="custom-control custom-checkbox small">
                        <input type="checkbox" class="custom-control-input" id="customCheck">
                        <label class="custom-control-label" for="customCheck">Remember Me</label>
                      </div>
                    </div>
					<div class="form-group">
                    	<input type="submit" class="btn btn-primary btn-user btn-block" name="loginSubmit" value="Log In">
                    </div>
					<br><br>
					<hr>
					<br><br>
                    <a href="#" class="btn btn-google btn-user btn-block">
                      <i class="fab fa-google fa-fw"></i> Login with Google
                    </a>
                    <a href="#" class="btn btn-facebook btn-user btn-block">
                      <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
                    </a>
                  </form>
				  <br><br>
          <hr>
				  <br><br>
                  <div class="text-center">
                    <a class="small" href="forgot-password.php">Forgot Password?</a>
                  </div>
                  <div class="text-center">
                    <a class="small" href="signup.php">Create an Account!</a>
                  </div>
                </div>
              </div>
            </div>
        </div>
        </div>
      	</div>
    </div>
</div>
</body>

<!-- Custom scripts for login.php page-->
<script src="js/template.min.js"></script>

<?php include 'footer.php' ?>