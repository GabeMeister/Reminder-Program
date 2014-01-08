<?php

require_once 'functions.php';
date_default_timezone_set('America/Los_Angeles');

$event = new Event();

$event->getFromCookies();

$event->set_name($_POST['eventName']);


//connect with mysqli database
$mysqli = connectDatabase('localhost', 'gabepack_tester', 'test12', 'gabepack_reminders');

//deletes chosen event
$event->deleteEventFromDatabase($mysqli);





?>
<!DOCTYPE html>
<html>
	<head>
		<title>Confirm Deletion</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		
		<link type='text/css' rel="stylesheet" href="css/bootstrap.min.css" />
		<link type='text/css' rel='stylesheet' href='css/mystyling.css' />
	</head>
	<body>
		
		<div class='wrapper wide topmargin'>
			<h1 class='center'>Event Successfully Deleted!</h1><br />
			<div class='center'>
				<a href="menu.php" class='btn btn-large btn-primary'>Back to Menu</a>
			</div>
		</div>
		
		
		
		<p id='dontCare'>Hosting provided by <a href='http://vlexofree.com/'>VlexoFree Hosting</a></p>
		
	</body>
</html>