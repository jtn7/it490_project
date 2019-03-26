<!DOCTYPE html>
<?php 
session_start();
?>
<html>
<head>
	<title>IT490 Midterm</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
	<div class="navBar">
		<a href="index.php">Home</a>
		<a href="createCharacter.php">Create Character</a>
		<a href="forums.php">Forums</a>
		<?php
			if(isset($_SESSION['username']))
				echo
				'<div class="dropdown">
					<button class="dropbtn">Account &#9662;</button>
					<div class="dropdownContent">
						<a href="logout.php">Log Out</a>
					</div>
				</div>'
		?>
	</div>
