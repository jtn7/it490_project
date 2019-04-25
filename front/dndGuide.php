<?php
session_start();
require_once 'RPC.php';
require_once 'logging/LogWriter.php';
use logging\LogWriter;
use rabbit\RPC;

error_reporting(E_ERROR | E_PARSE);

$logger = new LogWriter('/var/log/dnd/frontend.log');
$logger->info('dndGuide.php accessed');

if (!isset($_SESSION['username'])) {
	header('Location: login.php');
}
?>

<?php include 'header.php' ?>

<!-- Begin Page Content -->
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
                    
                    <embed src="https://s3.amazonaws.com/online.anyflip.com/swxi/xvkz/mobile/index.html" style="position:absolute; width:100%; height:100%;">
                        
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /.container-fluid -->

<?php include 'footer.php' ?>