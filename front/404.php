<?php
session_start();
require_once 'RPC.php';
require_once 'logging/LogWriter.php';
use logging\LogWriter;
use rabbit\RPC;

$logger = new LogWriter('/var/log/dnd/frontend.log');

$logger->info('404 page accessed');
?>

<?php include 'header_plain.php' ?>

<div class="container-fluid">

    <!-- 404 Error Text -->
    <div class="text-center">
        <br><br><br><br><br>
        <br><br><br><br><br>
        <br><br><br><br><br>
        <br><br>
        <div class="error mx-auto" data-text="404">404</div>
        <p class="lead text-gray-800 mb-5">Page Not Found</p>
        <p class="text-gray-500 mb-0">It looks like you found a glitch in our website...</p>
        <a href="index.php">&larr; Back to Homepage</a>
        <br>
        <br><br><br><br><br>
        <br><br><br><br><br>
        <br><br><br><br><br>
    </div>
</div>

<?php include 'footer_plain.php' ?>