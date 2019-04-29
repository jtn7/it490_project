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

<?php include 'html/404body.php' ?>

<?php include 'footer_plain.php' ?>