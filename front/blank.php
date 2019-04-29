<?php
session_start();
require_once 'RPC.php';
require_once 'logging/LogWriter.php';
use logging\LogWriter;
use rabbit\RPC;

$logger = new LogWriter('/var/log/dnd/frontend.log');

$logger->info('blank page accessed');
?>

<?php include 'header.php' ?>

<!-- This is the start of body for blank.php page -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4"></div>

    <!-- Content Row -->
    <div class="row">

        <!-- Content Column -->
        <div class="col-12">

            <!-- Project Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Title</h6>
                </div>
                <div class="card-body">
                    <div class="content">
                    
                    <!-- Enter the content here-->
                    Content goes here !
                                             
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- This is the end of body for blank.php page -->

<?php include 'footer.php' ?>