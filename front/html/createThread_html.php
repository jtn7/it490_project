<?php include 'header.php' ?>

<!-- This is the start of body for createThread.php page -->
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
					<h6 class="m-0 font-weight-bold text-primary">Create a Thread !</h6>
				</div>
				<div class="card-body">
					<div class="content align-items-center justify-content-between">
						<form action="" id="createThread" method="POST">
							<div class="row">
								<label for="colFormLabelSm" class="col-sm-2 col-form-label col-form-label-sm">Title:</label>
							</div>
							<div class="row">
								<input type="text" name="Name" required="required" class="form-control col-md-12" placeholder="Write the title . . .">
							</div>
							<br>
							<div class="row">
								<label for="colFormLabelSm" class="col-sm-2 col-form-label col-form-label-sm">Contents:</label>
							</div>
							<br>
							<div class="row">
								<textarea name="Content" form="createThread" required="required" class="form-control col-md-12" rows="20"></textarea>
							</div>
							<br>
							<div class="text-right"> 
								<input class="btn btn-primary btn-md" type="submit" name="createThreadSubmit" value="Create Thread">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- This is the end of body for createThread.php page -->

<?php include 'footer.php' ?>