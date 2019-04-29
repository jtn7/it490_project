<?php include 'header_plain.php' ?>

<body class="bg-gradient-danger">

<!-- This is the start of body for forgot-password.php page -->
<div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center" style="padding-bottom: 70px;">
      	<div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">

            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block">
			  	<img src="assets/forgot-password_background.jpg" alt="Dungeons & Dragons" class="img-responsive" style="max-width:100%; max-height:100%; object-fit: contain">
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
<!-- This is the end of body for forgot-password.php page -->

<!-- Custom scripts for forgot-password.php page-->
<script src="js/template.min.js"></script>

</body>

<?php include 'footer_plain.php' ?>