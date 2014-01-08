<!DOCTYPE html>
<html>
	<head>
		<title>Your Events</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		
		<link type="text/css" rel="stylesheet" href="css/bootstrap.css" />
		<link type="text/css" rel="stylesheet" href="css/mystyling.css" />
	</head>
	<body>

		<?php
		
		require_once 'functions.php';
		
		//case if user doesn't have cookies set on their machine.
		if (!isset($_COOKIE['email']) || !isset($_COOKIE['phoneNumber'])) {
			missingCookieMsg();
		}
		
		//make new event object
		$event = new Event();
		
		//get email and phone number data from cookies
		$event->getFromCookies();
		
		//connect to database, just called mysqli
		$mysqli = connectDatabase('localhost', 'gabepack_tester', 'test12', 'gabepack_reminders');
		
		//select all events for one user.
		$data = $event->selectFromDatabase($mysqli);
		
		//case if user doesn't have any events.
		if($data->num_rows === 0) {
			noEventsMsg();
		}
		
		echo <<<html
		<div class='wrapper wide topmargin'>
		<h1 class='bottommargin'>
			Select an event to modify:<br />
		</h1>
		<div class='wrapper'>
		<form action="eventModify.php" method='post'>
html;
		
		
		//first iteration needed to put status of "checked" on first choice.
		$row = $data->fetch_assoc();
		$name = $row['eventName'];
		echo "<label for='$name'> <input type='radio' name='eventName' value='$name' id='$name' checked/> $name</label><br />";
		
		//loop through the whole rest of database.
		while ($row = $data->fetch_assoc()) {
			$name = $row['eventName'];
			echo "<label for='$name'><input type='radio' name='eventName' value='$name' id='$name'/> $name</label><br />";
		}
		
		echo <<<html
					<div class='center'>
						<input type='submit' name='submitListEvent' value='Modify Event!' class='btn btn-primary'/>
					</div>
				</div>
			</form>
		</div>
html;
		
		$mysqli->close();
		?>
	
		
		<p id='dontCare'>Hosting provided by <a href='http://vlexofree.com/'>VlexoFree Hosting</a></p>
	</body>
</html>