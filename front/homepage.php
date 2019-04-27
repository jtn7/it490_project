<?php
session_start();
require_once 'RPC.php';
require_once 'logging/LogWriter.php';
use logging\LogWriter;
use rabbit\RPC;

$logger = new LogWriter('/var/log/dnd/frontend.log');
$logger->info('homepage.php accessed');
?>

<!DOCTYPE html>

<html lang="en">
<head>
	<!--Icon image -->
	<link rel="icon" type="image/ico" href="assets/favicon.ico">
	
	<!--Meta / Title -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width = device-width, initial-scale = 1, shrink-to-fit=no">
	<title>Dungeons & Dragons by POGO</title>
	<meta name="description" content="This is the web service for dungeons and dragons!">
	<meta name="author" content="POGO">

	<!--Link / Script -->
	<link rel="stylesheet" href="styleHomepage.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

	<!-- Custom font-->
	<link href="fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

	<!-- Custom styles-->
  <link href="css/template.min.css" rel="stylesheet">	
  
  <!-- Custom script for delay in order to load video-->
</head>

<body>

<!--Navigation bar for homepage-->
<?php
    //Navigation bar when the user is logged on
    if (isset($_SESSION['username'])) {
        echo
        '
        <nav class="navbar navbar-inverse justify-content-between shadow navbar-static-top" role="navigation"> 
            <ul class="nav narbar-nav narbar-left" id="navbar" style="display: flex; align-items: center";>
                <li>
                    <a class ="navbar-brand" href ="homepage.php"><img src="assets/dnd_logo.png" alt="Dungeons & Dragons" style="width:90px;"></a>
                </li>
                <li>
                    <a href="#">About</a>
                </li>
                <li>
                    <a href="#">Updates</a>
                </li>
                <li>
                    <a href="#">Team</a>
                </li>
                <li>
                    <a href="#">Contact</a>
                </li>
            </ul>
            <ul class="nav navbar-nav narbar-right" style="display: flex; align-items: center";>
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-gray-700 medium">
        ';
                        //Getting the username to display
                            $user = ($_SESSION['username']);
                            echo "<b class='medium text-gray-500'>$user</b>";

        echo
        '       
                    </span>
                    <img class="img-profile rounded-circle" src="assets/dnd_user_icon.png" style="width:30px;">
                    </a>
                    <!-- Dropdown - User Information -->
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        Profile
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                        Activity Log
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </a>
                    </div>
                </li>
            </ul>
        </nav>
        ';
    }

    //Navigation bar when the user is not logged on
    else 
    {
        echo
        '
        <nav class="navbar navbar-inverse justify-content-between shadow navbar-static-top" role="navigation">
            <ul class="nav narbar-nav narbar-left" id="navbar" style="display: flex; align-items: center";>
                <li>
                    <a class ="navbar-brand" href ="homepage.php"><img src="assets/dnd_logo.png" alt="Dungeons & Dragons" style="width:90px;"></a>
                </li>
                <li>
                    <a href="#">About</a>
                </li>
                <li>
                    <a href="#">Updates</a>
                </li>
                <li>
                    <a href="#">Team</a>
                </li>
                <li>
                    <a href="#">Contact</a>
                </li>
            </ul>
            <ul class="nav navbar-nav narbar-right" style="display: flex; align-items: center";>
                <form action="login.php" method="POST">
                    <div class="input-group">
                        <input type="text" placeholder="Username" name="loginUN" required="required" style="padding:5px;">
                        <input type="password" placeholder="Password" name="loginPW" required="required" style="padding:5px;">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Login</button>
                        </div>
                    </div>
                </form>
            </ul>
        </nav>
        ';
    }
?>
  
<!-- Sweet Alert for the login-->
<?php
	$fullUrl 	= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	if (strpos($fullUrl, "contact=error") == true){
		echo '<script type="text/javascript">swal("Wait a minute!", "Something went wrong while sending message :(", "error");</script>';
  }
  elseif (strpos($fullUrl, "contact=success") == true){
		echo '<script type="text/javascript">swal("Great job!", "Message sent to the developer :D", "success");</script>';
	}
?>

<!-- Add contents here-->
    <header class="v-header container-of-homepage">
        <div class="fullscreen-video-wrap">
            <video id="dnd_main_background" src="assets/dnd_main_background.mov"  autoplay="true" preload="true" loop="true" muted="true"></video>
        </div>
        <div class="header-overlay"></div>
        <div class="header-content">
            <h1>Welcome to D&D by POGO</h1>
            <p>We are creating an immersive dungeons and dragons experience</p>
            <a href="#" class="btn btn-lg">Read More</a>
        </div>
    </header>

    <!-- Section with About -->
    <section class="section section-a">
      <div class="container-of-homepage">
        <h1>About</h1>
        <p>Take us with your journey</p>
        <div class="row text-center">
          <div class="col-md-4">
            <span class="fa-stack fa-4x">
              <i class="fas fa-circle fa-stack-2x text-danger"></i>
              <i class="fas fa-user-edit fa-stack-1x fa-inverse"></i>
            </span>
            <h3 class="service-heading">Characters</h3>
            <p class="text-muted">Our web service application allow the end user to keep track of all the characters in thier party, look up other people's build and share their ideas on the forum!</p>
          </div>
          <div class="col-md-4">
            <span class="fa-stack fa-4x">
              <i class="fas fa-circle fa-stack-2x text-danger"></i>
              <i class="fab fa-connectdevelop fa-stack-1x fa-inverse"></i>
            </span>
            <h3 class="service-heading">Stay Connected</h3>
            <p class="text-muted">We have developed the best way to play dungeons and dragons using user-friendly platforms such as Google Hangouts, Calendars, Notifications, Messaging System and Forums</p>
          </div>
          <div class="col-md-4">
            <span class="fa-stack fa-4x">
              <i class="fas fa-circle fa-stack-2x text-danger"></i>
              <i class="fas fa-chess-knight fa-stack-1x fa-inverse"></i>
            </span>
            <h3 class="service-heading">Updates</h3>
            <p class="text-muted">We are continously researching & developing so that we can create something wonderful for our end user. Stay tuned on our updates section to see any upcoming updates!</p>
          </div>
        </div>
      </div>
    </section>
    
    <!-- Section with Updates -->
    <section class="section section-b">
      <div class="container-of-homepage">
          <h1>Updates</h1>
          <p>Here are some of the features that we are working on</p>
      </div>
    </section>

    <!-- Section with Member's Information -->
    <section class="section section-c">
        <div class="container-of-homepage">
            <h1>OUR AMAZING TEAM</h1>
            <p>Here are the members who are working on this project</p>

          <div class="row">
            <!-- Description for Brian-->
            <div class="col-sm-6">
              <div class="team-member">
                <img class="mx-auto rounded-circle" src="assets/members/brian.png" alt="Picture of Brian">
                <h4>Brian Hontiveros</h4>
                <h5>Back End Developer</h5>
                <ul class="list-inline social-buttons">
                  <li class="list-inline-item">
                    <a href="#">
                      <i class="fab fa-twitter"></i>
                    </a>
                  </li>
                  <li class="list-inline-item">
                    <a href="#">
                      <i class="fab fa-facebook-f"></i>
                    </a>
                  </li>
                  <li class="list-inline-item">
                    <a href="https://www.linkedin.com/in/brian-hontiveros-b4923a178/">
                      <i class="fab fa-linkedin-in"></i>
                    </a>
                  </li>
                </ul>
              </div>
            </div>

            <!-- Description for Vincent-->
            <div class="col-sm-6">
              <div class="team-member">
                <img class="mx-auto rounded-circle" src="assets/members/vincent.jpg" alt="Picture of Vincent">
                <h4>Vincent Lozano</h4>
                <h5>Front End Developer</h5>
                <ul class="list-inline social-buttons">
                  <li class="list-inline-item">
                    <a href="#">
                      <i class="fab fa-twitter"></i>
                    </a>
                  </li>
                  <li class="list-inline-item">
                    <a href="#">
                      <i class="fab fa-facebook-f"></i>
                    </a>
                  </li>
                  <li class="list-inline-item">
                    <a href="https://www.linkedin.com/in/vincent-lozano021/">
                      <i class="fab fa-linkedin-in"></i>
                    </a>
                  </li>
                </ul>
              </div>
            </div>

            <!-- Description for Umar-->
            <div class="col-sm-4">
              <div class="team-member">
                <img class="mx-auto rounded-circle" src="assets/members/umar.png" alt="Picture of Umar">
                <h4>Umar Arshad</h4>
                <h5>Database Scientist</h5>
                <ul class="list-inline social-buttons">
                  <li class="list-inline-item">
                    <a href="#">
                      <i class="fab fa-twitter"></i>
                    </a>
                  </li>
                  <li class="list-inline-item">
                    <a href="#">
                      <i class="fab fa-facebook-f"></i>
                    </a>
                  </li>
                  <li class="list-inline-item">
                    <a href="https://www.linkedin.com/in/umar-arshad-218907177/">
                      <i class="fab fa-linkedin-in"></i>
                    </a>
                  </li>
                </ul>
              </div>
            </div>

            <!-- Description for Josiah-->
            <div class="col-sm-4">
              <div class="team-member">
                <img class="mx-auto rounded-circle" src="assets/members/josiah.jpg" alt="Picture of Josiah">
                <h4>Josiah Nieves</h4>
                <h5>Project Manager</h5>
                <ul class="list-inline social-buttons">
                  <li class="list-inline-item">
                    <a href="#">
                      <i class="fab fa-twitter"></i>
                    </a>
                  </li>
                  <li class="list-inline-item">
                    <a href="#">
                      <i class="fab fa-facebook-f"></i>
                    </a>
                  </li>
                  <li class="list-inline-item">
                    <a href="https://www.linkedin.com/in/josiah-nieves-02233ab7/">
                      <i class="fab fa-linkedin-in"></i>
                    </a>
                  </li>
                </ul>
              </div>
            </div>

            <!-- Description for Marvin-->
            <div class="col-sm-4">
              <div class="team-member">
                <img class="mx-auto rounded-circle" src="assets/members/marvin.jpg" alt="Picture of Marvin">
                <h4>Marvin Jung</h4>
                <h5>Front End Developer</h5>
                <ul class="list-inline social-buttons">
                  <li class="list-inline-item">
                    <a href="#">
                      <i class="fab fa-twitter"></i>
                    </a>
                  </li>
                  <li class="list-inline-item">
                    <a href="#">
                      <i class="fab fa-facebook-f"></i>
                    </a>
                  </li>
                  <li class="list-inline-item">
                    <a href="https://www.linkedin.com/in/marvin-jung-b1a63a104/">
                      <i class="fab fa-linkedin-in"></i>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
    </section>

    <!-- Section with Contact -->
    <section class="section section-d" id="contact">
      <div class="container-of-homepage">
        <h1>Contact Us</h1>
        <p>Let us know how we are doing & where we should improve!</p>

        <div class="row">
          <div class="col-lg-12">
            <form id="contactForm" name="sentMessage" action="contact_us.php" method="POST">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <input class="form-control" id="name" name="name" type="text" placeholder="Your Name *" required="required">
                    <p class="help-block text-danger"></p>
                  </div>
                  <div class="form-group">
                    <input class="form-control" id="email" name="email" type="email" placeholder="Your Email *" required="required">
                    <p class="help-block text-danger"></p>
                  </div>
                  <div class="form-group">
                    <input class="form-control" id="phone" name="phone" type="tel" placeholder="Your Phone *" required="required">
                    <p class="help-block text-danger"></p>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <textarea class="form-control" id="message" name="message" placeholder="Your Message *" required="required"></textarea>
                    <p class="help-block text-danger"></p>
                  </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-12 text-center" style="margin-bottom:30px;">
                  <div id="success"></div>
                  <button id="sendMessageButton" class="btn btn-primary btn-xl text-uppercase" type="submit">Send Message</button>
                </div>
              </div>
            </form>
          </div>
        </div>

      </div>
    </section>
<!-- End Contents here-->

<!-- Footer -->
<footer class="sticky-footer" style="background-color:black;">

    <!-- Footer Links -->
    <div class="container text-center text-md-left">

      <!-- Grid row -->
      <div class="row">

        <!-- Grid column -->
        <div class="col-md-4 col-lg-3 mr-auto my-md-4 my-0 mt-4 mb-1">

          <!-- Content -->
          <h5 class="font-weight-bold text-uppercase mb-4">Team POGO</h5>
          <p>Welcome to the Dungeons & Dragons web service application developed by Team POGO!</p>
          <p>We are dedicated to develop something wonderful for our fellow gamers!</p>

        </div>
        <!-- Grid column -->

        <hr class="clearfix w-100 d-md-none">

        <!-- Grid column -->
        <div class="col-md-2 col-lg-2 mx-auto my-md-4 my-0 mt-4 mb-1">

          <!-- Links -->
          <h5 class="font-weight-bold text-uppercase mb-4">Useful Links</h5>

          <ul class="list-unstyled">
            <li>
              <p>
                <a href="homepage.html">HOME</a>
              </p>
            </li>
            <li>
              <p>
                <a href="login.php">LOGIN</a>
              </p>
            </li>
            <li>
              <p>
                <a href="signup.php">REGISTER</a>
              </p>
            </li>
            <li>
              <p>
                <a href="forgot-password.php">PASSWORD RETRIEVAL</a>
              </p>
            </li>
          </ul>
        </div>

        <!-- Grid column -->
        <hr class="clearfix w-100 d-md-none">

        <!-- Grid column -->
        <div class="col-md-4 col-lg-3 mx-auto my-md-4 my-0 mt-4 mb-1">

          <!-- Contact details -->
          <h5 class="font-weight-bold text-uppercase mb-4">CONTACT INFO</h5>

          <ul class="list-unstyled">
            <li>
              <p>
                <i class="fas fa-home mr-3"></i> Newark, NJ 07102, US</p>
            </li>
            <li>
              <p>
                <i class="fas fa-envelope mr-3"></i> POGO@gmail.com</p>
            </li>
            <li>
              <p>
                <i class="fas fa-phone mr-3"></i> + 01 973 123 4567</p>
            </li>
            <li>
              <p>
                <i class="fas fa-print mr-3"></i> + 01 973 123 4567</p>
            </li>
          </ul>

        </div>
        <!-- Grid column -->

        <hr class="clearfix w-100 d-md-none">

        <!-- Grid column -->
        <div class="col-md-2 col-lg-2 text-center mx-auto my-4">

          <!-- Social buttons -->
          <h5 class="font-weight-bold text-uppercase mb-4">Follow Us</h5>

          <!-- Facebook -->
          <a type="button" class="btn-floating btn-fb btn-lg btn-primary">
            <i class="fab fa-facebook-f"></i>
          </a>
          <!-- Google -->
          <a type="button" class="btn-floating btn-gplus btn-lg">
            <i class="fab fa-google-plus-g"></i>
          </a>
        </div>

        <!-- Grid column -->

      </div>
      <!-- Grid row -->

    </div>
    <!-- Footer Links -->

    <!-- Copyright -->
    <div class="footer-copyright text-center py-3">© 2019 Copyright:
        <a href="#"> Dungeons & Dragons by POGO</a>
    </div>
    <!-- Copyright -->

</footer>
  
</body>
</html>