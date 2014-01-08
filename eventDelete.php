<?php

require_once 'functions.php';
date_default_timezone_set('America/Los_Angeles');

echo "<link type='text/css' rel='stylesheet' href='css/bootstrap.css' />";
echo "<link type='text/css' rel='stylesheet' href='css/mystyling.css' />";



//case if user doesn't have cookies set on their machine.
if (!isset($_COOKIE['email']) || !isset($_COOKIE['phoneNumber'])) {
	missingCookieMsg();
}

$event = new Event();

$event->getFromCookies();


$mysqli = connectDatabase('localhost', 'gabepack_tester', 'test12', 'gabepack_reminders');
$data = $event->selectFromDatabase($mysqli);

//case if user doesn't have any events.
if($data->num_rows === 0) {
	noEventsMsg();
}


//Have to echo out a heredoc for the styling to be correct
echo <<<html
	<div class='wrapper wide topmargin'>
		<h1 class='bottommargin'>
			Select an event to delete:<br />
		</h1>
		<div class='wrapper'>
			<form action="confirmDelete.php" method='post' id='deleteForm'>
html;

//first iteration needed to put status of "checked" on first choice, 
//so we don't have to use jquery validator to make sure user checked one.
$row = $data->fetch_assoc();
$name = $row['eventName'];
echo "<label for='$name'> <input type='radio' name='eventName' value='$name' id='$name' checked/> $name</label><br />";

//loop through the whole rest of database.
while ($row = $data->fetch_assoc()) {
	$name = $row['eventName'];
	echo "<label for='$name'> <input type='radio' name='eventName' value='$name' id='$name'/> $name</label><br />";
}

//more html for styling purposes
echo <<<html
			<div class='center'>
				<input type='submit' name='submitListEvent' value='Delete Event!' class='btn btn-primary' />
			</div>
			</form>
		</div>
	</div>
html;

?>


<!DOCTYPE html>
<html>
	<head>
		<title>Delete Event</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	</head>
	<body>
		
		
		
		<p id='dontCare'>Hosting provided by <a href='http://vlexofree.com/'>VlexoFree Hosting</a></p>
		
		
		<!--confirm delete script not working yet-->
		<script type='text/javascript' src='js/checkDelete.js'></script>
	</body>
</html>