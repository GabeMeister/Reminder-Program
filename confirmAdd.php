<!DOCTYPE html>
<html>
	<head>
		<title>Confirmation Page</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		
		<link type="text/css" rel="stylesheet" href="css/bootstrap.css" />
		<link type="text/css" rel="stylesheet" href="css/mystyling.css" />
		
		<?php

		require_once 'functions.php';
		date_default_timezone_set('America/Los_Angeles');

		//Make new event
		$event = new Event();
		
		//Copies all $_POST and $_COOKIE variables into Event class
		$event->getFromPost();
		$event->getFromCookies();
		
		//get mysql database ready
		$mysqli = connectDatabase('localhost', 'gabepack_tester', 'test12', 'gabepack_reminders');
		
		
		
		//check if user accidentally added the same event twice
		$userevents = $event->selectFromDatabase($mysqli);
		
		//checks if there are two of the same events in database if there is, displays error message.
		while($row = $userevents->fetch_assoc()) {
			if($row['eventName'] === $event->get_name()) {
				duplicateEventsMsg();
			}
		}





		//////////FORMATTING ALGORITHMS FOR INSERTING DATA INTO DATABASE////////

		////////ALGORITHM 1: take event date and event time and convert into one date-time database entry
		//change event date and time to format for database
		$event->uiToDBtime();
		$event->uiToDBdate();

		//Create event date object from user input.
		$event->set_event_date_time();

		
		////////ALGORITHM 2: Take reminder and convert from time span before event time to actual date-time
		//Reminder is set to event time by default.
		$event->set_reminder_date_time($event->get_event_date_time());

		
		////////PUT INTO DATABASE////////
		//inserting data into database, so we use insertDatabase method
		$event->insertDatabase($mysqli);
		
		$mysqli->close();
		?>
		
		
		
		
	</head>
	<body>
		
		<div class='wrapper wide topmargin'>
			<h1 class='center'>Event Successfully Added!</h1><br />
			<div class='center'>
				<a href="menu.php" class='btn btn-large btn-primary'>Back to Menu</a>
			</div>
		</div>
		
		
		<p id='dontCare'>Hosting provided by <a href='http://vlexofree.com/'>VlexoFree Hosting</a></p>
	</body>
</html>