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

<?php include 'html/login_html.php' ?>