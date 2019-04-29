<?php include 'header.php' ?>

<!-- This is the start of body for replies.php page -->
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
					<h6 class="m-0 font-weight-bold text-primary">Reply</h6>
				</div>
				<div class="card-body">
					<div class="content">
						<?php
						$Thread = unserialize($response1);
						echo
							'<h1>' . $Thread['Name'] . '</h1>
							<p>' . $Thread['Content'] . '</p><br>
							<p>Written by ' . $Thread['User'] . ' - ' . $Thread['Timestamp'] . '</p>';

						?>
						<?php
						$unserArr = unserialize($response);
						foreach ($unserArr as $repliesArr){
							echo
							'
							<div class="col-12">
								<div class="card border-left-primary shadow h-50 py-0">
									<div class="card-body">
										<div class="row no-gutters align-items-center">
											<div class="col mr-0">
												<div class="h5 mb-0 font-weight-bold text-gray-800 mb-1">'. $repliesArr['Content'] .'</div>
												<div class="text-s font-weight-regular text-gray-800"><p>'. $repliesArr['User'] . ' - ' . $repliesArr['Timestamp'] .'</p></div>
											</div>
										</div>
									</div>
								</div>
							</div>
							';
						}
						?>
						<br>
						<form action="" method="POST">
							<div class="row">
								<label for="colFormLabelSm" class="col-sm-2 col-form-label col-form-label-sm">Write your reply:</label>
							</div>
							<div class="row">
								<textarea name="ReplyContent" required="required" class="form-control col-md-12" rows="5"></textarea>
							</div>
							<br>
							<div class="text-right"> 
								<input class="btn btn-primary btn-lg" type="submit" name="createReplySubmit" value="Submit">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- This is the end of body for replies.php page -->

<?php include 'footer.php' ?>
