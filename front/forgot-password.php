<?php
session_start();
require_once 'RPC.php';
require_once 'logging/LogWriter.php';
use logging\LogWriter;
use rabbit\RPC;

$logger = new LogWriter('/var/log/dnd/frontend.log');

$logger->info('forgot-password page accessed');
if(!empty($_POST)){
  $logger->info('POST recieved');
  $logger->info($_POST);

  /*
  Needs to be edited for password retrieval process
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
  */

}
?>

<?php include 'header_plain.php' ?>

<!-- Sweet Alert for the page-->

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
			  	<img src="assets/forgot-password_background.jpg" alt="Dungeons & Dragons" class="img-responsive">
			  </div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                  <br><br>
				  <br><br>
                    <h1 class="h4 text-gray-900 mb-4"><b>We all forget sometimes. . .</b></h1>
					<br><br>
                  </div>
                  <form class="user" action="" method="">
                    <div class="form-group">
						<input type="text" class="form-control form-control-user" name="forgot-passwordUN" required="required" placeholder="Enter User Name ...">
                    </div>
					<div class="form-group">
                    	<input type="submit" class="btn btn-primary btn-user btn-block" name="forgot-passwordSubmit" value="Reset Password">
                    </div>
                  </form>
				  <br><br>
                  <hr>
				  <br><br>
                  <div class="text-center">
                    <a class="small" href="signup.php">Create an Account!</a>
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

<!-- Custom scripts for forgot-password.php page-->
<script src="js/template.min.js"></script>

</body>

<?php include 'footer.php' ?>