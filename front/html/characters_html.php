<?php include 'header.php' ?>

<!-- Sweet Alert for the character creation-->
<?php
	$fullUrl 	= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	if (strpos($fullUrl, "success=S") == true){
		echo '<script type="text/javascript">swal("Great job!", "Character created successfully :D", "success");</script>';
  }
?> 

<!-- This is the start of body for characters.php page -->
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
					<h6 class="m-0 font-weight-bold text-primary">Characters</h6>
					<div class="dropdown no-arrow">
						<a href="createCharacter.php" role="button" aria-haspopup="true" aria-expanded="false">
							<i class="fas fa-plus fa-sm fa-fw text-gray-400"></i>
						</a>
					</div>
				</div>
				<div class="card-body">
					<div class="content">
					
						<?php
						if (isset($_GET['load'])) {
							//Sweet Alert for the characters
							echo '<script type="text/javascript">swal("Wait a minute!", "Failed to get characters! :(", "error");</script>';
						}   
						else {
							// Creating character contents
							foreach ($characters as $character) {
								echo
								'<div class="col-12">
									<div class="card border-left-primary shadow h-100 py-2">
										<div class="card-body">
											<div class="row no-gutters align-items-center">
												<div class="col mr-2">
													<div class="h5 mb-0 font-weight-bold text-gray-900 mb-1"> Character Name: ' . $character['name'] . '</div>
													<div class="text-s font-weight-regular text-gray-800"><p>Race: ' . $character['race'] . ' | Class: ' . $character['class'] . '</p></div>
												</div>
												<div class="col-auto">
													<a href="editCharacter.php">
														<i class="fas fa-bars fa-2x text-gray-300"></i>
													</a>
												</div>
											</div>
										</div>
									</div>
								</div>
								';
							}
						}
						?>
                    
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- This is the end of body for characters.php page -->

<?php include 'footer.php' ?>