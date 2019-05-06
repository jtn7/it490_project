
<?php include 'header.php' ?>

<!-- This is the start of body for forums.php page -->
<div class="container-fluid">

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
</div>

	<!-- Content Row -->
	<div class="row">

		<!-- Content Column -->
		<div class="col-12">

			<!-- Project Card -->
			<div class="card shadow mb-4">
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h6 class="m-0 font-weight-bold text-primary">Party</h6>
				</div>
				<div class="card-body">
					<div class="content">
					<?php
					// echo "<h2>";
					// print_r($response);
					// echo "<h2>";
					$unserArr = unserialize($response);

					foreach ($unserArr as $partyArr) {
					echo
					'				
					<div class="col-12">
						<div class="card border-left-primary shadow h-100 py-2">
							<div class="card-body">
								<div class="row no-gutters align-items-center">
									<div class="col mr-2">
										<div class="h5 mb-0 font-weight-bold text-warning text-uppercase mb-1"><a href="threads.php?forumID=' . $partyArr['ForumID'] . '&forumName=' . $partyArr['Name'] . '">' . $partyArr['Name'] . '</a></div>
										<div class="text-s font-weight-regular text-gray-800"><p>' . $partyArr['Description'] . '</p></div>
									</div>
									<div class="col-auto">
										<i class="fas fa-comments fa-2x text-gray-300"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
					';
					}
					?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- This is the end of body for forums.php page -->

<?php include 'footer.php' ?>