<!DOCTYPE html>
<html>
	<head>
		<title>Menu</title>

		<link type="text/css" rel="stylesheet" href="css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="css/mystyling.css" />

		<?php
		
		require_once 'functions.php';
		
		//error check if user doesn't have cookies set on their machine.
		if (!isset($_COOKIE['phoneNumber']) || !isset($_COOKIE['email'])) {
			missingCookieMsg();
		}
		
		?>
	</head>
	<body>

		<h1 class='center topmargin'>Select an option!</h1><br />

		<div class="wrapper">
			<div class='center'>
				<a href="eventAdd.php" class="btn btn-large btn-primary">Create Event</a><br />
			</div>
			<br />
			<div class='center'>
				<a href="listEvents.php" class="btn btn-large btn-primary">Modify Event</a><br />
			</div>
			<br />
			<div class='center'>
				<a href="eventDelete.php" class="btn btn-large btn-primary">Delete Event</a>
			</div>



		</div>
		
		
		
		<p id='dontCare'>Hosting provided by <a href='http://vlexofree.com/'>VlexoFree Hosting</a></p>
	</body>
</html>