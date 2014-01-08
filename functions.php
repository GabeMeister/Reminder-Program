<?php

date_default_timezone_set('America/Los_Angeles');

class Event {
	////////////////////////////////////////////////// VARIABLES ////////////////////////////////////////////////////
	
	//Variables from user interface.
	//some get inputted into database, but others are used for formatting of other database fields
	private $phone_number;
		function get_phone_number() {
			return $this->phone_number;
		}
		function set_phone_number($the_phone_number) {
			$this->phone_number = $the_phone_number;
		}
	private $email;
		function get_email() {
			return $this->email;
		}
		function set_email($theEmail) {
			$this->email = $theEmail;
		}
	private $carrier;
		function get_carrier() {
			return $this->carrier;
		}
		function set_carrier($theCarrier) {
			$this->carrier = $theCarrier;
		}
	private $name;
		function get_name() {
			return $this->name;
		}
		function set_name($theName) {
			$this->name = $theName;
		}
	private $date;
		function get_date() {
			return $this->date;
		}
		function set_date($theDate) {
			$this->date = $theDate;
		}
	private $time;
		function get_time() {
			return $this->time;
		}
		function set_time($theTime) {
			$this->time = $theTime;
		}
	private $time_before;
		function get_time_before() {
			return $this->time_before;
		}
		function set_time_before($theRemind) {
			$this->time_before = $theRemind;
		}
	private $freq;
		function get_freq() {
			return $this->freq;
		}
		function set_freq($theFreq) {
			$this->freq = $theFreq;
		}
	private $notif_method;
		function get_notif_method() {
			return $this->notif_method;
		}
		function set_notif_method($the_notif_method) {
			$this->notif_method = $the_notif_method;
		}
	
	
	//Variables from Database.
	var $event_date_time;
		function get_event_date_time() {
			return $this->event_date_time;
		}
		function set_event_date_time() {
			//create temporary object to form date object
			$dateobj = date_create($this->date . " " . $this->time);
			
			//we can format the date object string to exactly what we want. Leading zeros, etc.
			$this->event_date_time = date_format($dateobj, "Y-m-d H:i:s");
		}
	var $reminder_date_time;
		function get_reminder_date_time() {
			return $this->reminder_date_time;
		}
		function set_reminder_date_time($theReminder) {
			$dateobj = date_create($theReminder);
			$dateobj = reminderMaker($dateobj, $this->time_before);
			$this->reminder_date_time = date_format($dateobj, "Y-m-d H:i:s");
		}
	var $id;
		function get_id() {
			return $this->id;
		}
		function set_id($theID) {
			$this->id = $theID;
		}
		
	//////////////////////////////////////////////// METHODS //////////////////////////////////////////////////////
	//Set cookies for user phone number and email, which is used to skip first page
	//	after user has already used program.
	//Doesn't return anything
	function setCookies() {
		//cookies are set to 1 month, or 30 days, from now.
		setcookie("phoneNumber", $this->phone_number, time() + (60 * 60 * 24 * 30));
		setcookie("email", $this->email, time() + (60 * 60 * 24 * 30));
	}
		
	//Change phone number to email to send text message
	//Extracts numbers out of phone number, and concatenates corresponding email domain at end.
	//Doesn't return anything.
	function changeNumber() {
	
		//set phone number as is, with () and - in it.
		$phoneNumber = $this->phone_number;

		//seperate phone number into 3 parts
		$phoneNum1 = substr($phoneNumber, -13, 3);
		$phoneNum2 = substr($phoneNumber, -8, 3);
		$phoneNum3 = substr($phoneNumber, -4, 4);
		
		//combine three parts of phone number into one.
		$phoneNumberFull = $phoneNum1 . $phoneNum2 . $phoneNum3;

		//change phoneNumber data for specific carrier
		$phoneCarrier = $this->carrier;
		
		switch($phoneCarrier) {
			//Verizon
			case "verizon":
				$phoneNumberFull .= "@vtext.com";
				break;
			//AT & T
			case "at&t":
				$phoneNumberFull .= "@txt.att.net";
				break;
			//Sprint
			case "sprint":
				$phoneNumberFull .= "@messaging.sprintpcs.com";
				break;
			//T-Mobile
			case "tmobile":
				$phoneNumberFull .= "@tmomail.net";
				break;
			//Default case where error occurred.
			default:
				die("Converting phoneNumber failed.<br />");
				break;
		}
		
		//assigns phone number to event class
		$this->phone_number = $phoneNumberFull;
	
	}
	
	//Assigns $_COOKIE variables into Event class
	//Doesn't return anything.
	function getFromCookies() {
		//Data stored in cache as a cookie.
		$this->phone_number = $_COOKIE['phoneNumber'];
		$this->email = $_COOKIE['email'];
	}
	
	//Assigns $_POST variables into Event class
	//Doesn't return anything.
	function getFromPost() {
		
		//Event Page variables.
		$this->name = $_POST['eventName'];
		$this->date = $_POST['eventDate'];
		$this->time = $_POST['eventTime'];
		$this->time_before = $_POST['time_before'];
		$this->freq = $_POST['frequency'];
		$this->notif_method = $_POST['method'];
	}
	
	//Assigns only some variables fetched from row of database to Event class
	//The reason for this is in some cases fetching from database, it is only to 
	//	display info in the user interface form. We don't need data like the reminder date time
	//Doesn't return anything
	function getFromDatabase($row) {
		//id
		$this->id = $row['id'];
		//eventDateTime
		$this->event_date_time = $row['eventDateTime'];
		//time before
		$this->time_before = $row['timeBefore'];
		//frequency
		$this->freq = $row['frequency'];
		//method
		$this->notif_method = $row['method'];
	}
	
	//Assigns all variables fetched from row of database to Event class
	//Doesn't return anything
	function getEverythingFromDatabase($row) {
		//initilizing variables from row data
		$this->id = $row['id'];
		$this->email = $row['email'];
		$this->phone_number = $row['phoneNumber'];
		$this->name = $row['eventName'];
		$this->event_date_time = $row['eventDateTime'];
		$this->reminder_date_time = $row['reminderDateTime'];
		$this->notif_method = $row['method'];
		$this->time_before = $row['timeBefore'];
		$this->freq = $row['frequency'];
	}
	
	//Change time from user interface format to database format
	//Doesn't return anything
	function uiToDBtime() {
				
		//break time up into hours, minutes, seconds, and am or pm.
		$hour = (int)substr($this->time, -8, 2);
		$min = substr($this->time, -5, 2);
		$sec = "00";
		$am_pm = substr($this->time, -2, 2);
		
		//To reassemble into date-time format, must change hours to 24 hour time
		$hour = changeTo24hour($hour, $am_pm);
		
		//concatenate hours, minutes, and seconds together with colons inbetween for formatted time.
		$formattedTime = $hour . ":" . $min . ":" . $sec;
		
		//set object time to formatted time.
		$this->time = $formattedTime;
	}
	
	//Change date from user interface format to database format
	//Doesn't return anything
	function uiToDBdate() {

		$month = (int) substr($this->date, -10, 2);
		$day = (int) substr($this->date, -7, 2);
		$year = (int) substr($this->date, -4, 4);

		$formattedDate = $year . "-" . $month . "-" . $day;

		$this->date = $formattedDate;
	}
	
	//Change time from database format to user interface format
	//Doesn't return anything
	function dbToUItime() {

		$hour = substr($this->event_date_time, -8, 2);
		$min = substr($this->event_date_time, -5, 2);

		$am_pm = "AM";

		//change hour based on certain conditions
		$hour = changeTo12hour($hour, $am_pm);

		$this->time = $hour . ":" . $min . " " . $am_pm;
	}
	
	//Changes date from database format to user interface format
	//Doesn't return anything
	function dbToUIdate() {
		
		$year = substr($this->event_date_time, -19, 4);
		$month = substr($this->event_date_time, -14, 2);
		$day = substr($this->event_date_time, -11, 2);

		$this->date = $month . "/" . $day . "/" . $year;
	
	}
	
	//Inserts a new row of data into database. 
	//Doesn't return anything.
	function insertDatabase($database) {
		//mysql statement
		$statement = "INSERT INTO reminders (email, phoneNumber, eventName, eventDateTime, reminderDateTime, method, timeBefore, frequency) VALUES ('$this->email', '$this->phone_number', '$this->name', '$this->event_date_time', '$this->reminder_date_time', '$this->notif_method', '$this->time_before', '$this->freq')";

		//mysql query for reminders table
		$database->query($statement) or die("Unable to add to database. " . $database->error);
	}
	
	//Selects everything from database.
	//Returns the database query
	function selectAllFromDatabase($database) {
		$statement = "SELECT * FROM reminders";
		$query = $database->query($statement) or die("Unable to select from database. " . $database->error);
		return $query;
	}
	
	//Selects all events under particular user's email and phone number. 
	//Returns database query.
	function selectFromDatabase($database) {
		$statement = "SELECT * FROM reminders WHERE email='$this->email' && phoneNumber='$this->phone_number'";
		$query = $database->query($statement) or die("Unable to select from database. " . $database->error);
		return $query;
	}
	
	//Selects a specific event from user's added events
	//Uses email, phone number and event name as key to find correct event.
	//Returns database query
	function selectEventFromDatabase($database) {
		//mysql statement
		$statement = "SELECT * FROM reminders WHERE email='$this->email' && phoneNumber='$this->phone_number' && eventName='$this->name'";
		$query = $database->query($statement) or die("Unable to select event from database. " . $database->error);
		return $query;
	}
	
	//Updates a row of data. 
	//Uses id as key to find correct event from database.
	//Doesn't return anything
	function updateEventFromDatabase($database) {
		//mysql statement
		$statement = "UPDATE reminders SET eventName='$this->name', eventDateTime='$this->event_date_time', reminderDateTime='$this->reminder_date_time', method='$this->notif_method', timeBefore='$this->time_before', frequency='$this->freq' WHERE id='$this->id'";

		//mysql query for reminders table
		$database->query($statement) or die("Unable to update database. " . $database->error);
	}
	
	//Deletes a row of data from database. 
	//Uses phone number, email, and event name as key to find correct event in database.
	//Doesn't return anything
	function deleteEventFromDatabase($database) {
		//delete statement
		$statement = "DELETE FROM reminders WHERE eventName='$this->name' && email='$this->email' && phoneNumber='$this->phone_number'";
		
		//delete query or die
		$database->query($statement) or die("Unable to delete event. " . $database->error);
	}
	
	//Deletes a row of data from database.
	//Uses id as key to find correct event in database.
	//Doesn't return anything.
	function deleteEventFromDatabaseById($database) {
		//delete statement
		$statement = "DELETE FROM reminders WHERE id=" . $this->id;
		
		//delete query or die
		$database->query($statement) or die("Error with mysql query. " . $database->error);
	}
	
	//Sets the event_date_time and reminders ahead by the amount specified by the user.
	//This function is used only when an event has a frequency of more than a one time event. (Weekly, Monthly, etc.)
	function setForward() {
		
		switch($this->freq) {
			case "one":
				$timeAfter = "0 minutes";
				break;
			case "week":
				$timeAfter = "1 week";
				break;
			case "month":
				$timeAfter = "1 month";
				break;
			case "year":
				$timeAfter = "1 year";
				break;
			default:
				die("Error with setting Forward.");
		}
		
		//Have to make an event date and remind object, to be able to use date_add() to make event available for next time
		$temp_event_date_obj = new DateTime($this->event_date_time);
		$temp_remind_date_obj = new DateTime($this->reminder_date_time);
		
		//adding specified amount of weeks, months, etc. for event date and reminder date
		$temp_event_date_obj = date_add($temp_event_date_obj, date_interval_create_from_date_string($timeAfter));
		$temp_remind_date_obj = date_add($temp_remind_date_obj, date_interval_create_from_date_string($timeAfter));
		
		//assign a formatted string of new dates
		$this->event_date_time = date_format($temp_event_date_obj, "Y-m-d H:i:s");
		$this->reminder_date_time = date_format($temp_remind_date_obj, "Y-m-d H:i:s");
		
		//unset both temporary objects to free memory
		unset($temp_event_date_obj);
		unset($temp_remind_date_obj);
	}
	
}



/////////////////////////////FUNCTIONS THAT AREN'T PART OF EVENT OBJECT///////////////////////////////


//prints a styled message to user if there are no cookies.
//Doesn't return anything.
function missingCookieMsg() {
	echo <<<message
		<div class="wrapper wide topmargin">
			<h1 class="center">
				We don't have your phone number or email!<br /><br />
				Return to login page.
			</h1>
			<br />
			<div class="center">
				<a href="index.php" class="btn btn-large btn-primary"><div id="addEvent" class="menu">Login Page</div></a>
			</div>
		</div>
message;
			die();
}

//prints a styled message to user if they have no events to modify or delete
//Doesn't return anything.
function noEventsMsg() {
	echo <<<noevents
	<div class='wrapper wide topmargin'>
		<h1 class='center'>It seems like you don't have any events added!</h1><br />
		<h1 class='center'>Return to Menu to create an event.</h1><br />
		<div class='center'>
			<a href='menu.php' class='btn btn-large btn-primary'>Back to Menu</a>
		</div>
		
	</div>
noevents;
	die();
}

//Prints a styled message to user if they are trying to add an event with the same name
//	as an event they entered before.
//Doesn't return anything.
function duplicateEventsMsg() {
	echo <<<duplicateEvent
	<div class='wrapper wide topmargin'>
		<h1 class='center'>It seems like you already have an event with the same name!</h1><br />
		<h1 class='center'>Change the event name to something different.</h1><br />
		<div class='center'>
			<a href='eventAdd.php' class='btn btn-large btn-primary'>Back to Create Event</a>
		</div>
		
	</div>
duplicateEvent;
	die();
}

//Connects to database
//Returns the database connection
function connectDatabase($mysqliHost, $username, $password, $database) {
	$connection = new mysqli($mysqliHost, $username, $password, $database) or die("Unable to connect to database. " . $connection->connecterror);
	return $connection;
}

//Change time from 24 hours to 12 hours.
//We use this when fetching data from database and displaying to user
//Returns changed hour, and saves whether am or pm through a reference
function changeTo12hour($thehour, &$theam_pm) {
	//change hour based on certain conditions
	if($thehour === "00") {
		$thehour = "12";
	}
	else if((int)$thehour > 12) {
		$thehour -= 12;
		$theam_pm = "PM";
	}

	return $thehour;
}

//Change time from 12 hour to 24 hour
//This function is used when formatting time to input into database
//Returns the changed hour
function changeTo24hour($thehour, $theam_pm) {
	//Case if the time is in the afternoon. Hours in 24 hour time don't reset to 1 again.
	if ($theam_pm === "PM" && $thehour < 12) {
		$thehour += 12;
	}
	//Case if time is during midnight hour. (Between 0:00 to 1:00). In 24 hour time, hour is 00, not 12.
	else if ($theam_pm === "AM" && $thehour === 12) {
		$thehour = "00";
	}
	
	return $thehour;
}

//Returns reminder date-time object from given an event date-time object and amount of time before
function reminderMaker($eventDateTime, $timeBefore) {
	
	//Make new reminder date-time object
	//This will be returned by the function
	$remindDateTime = new DateTime();
	
	switch($timeBefore) {
	case "start":
		$remindDateTime = date_sub($eventDateTime, date_interval_create_from_date_string("0 min"));
		break;
	case "ten_before":
		$remindDateTime = date_sub($eventDateTime, date_interval_create_from_date_string("10 min"));
		break;
	case "thirty_before":
		$remindDateTime = date_sub($eventDateTime, date_interval_create_from_date_string("30 min"));
		break;
	case "hour_before":
		$remindDateTime = date_sub($eventDateTime, date_interval_create_from_date_string("1 hour"));
		break;
	case "day_before":
		$remindDateTime = date_sub($eventDateTime, date_interval_create_from_date_string("1 day"));
		break;
	case "week_before":
		$remindDateTime = date_sub($eventDateTime, date_interval_create_from_date_string("1 week"));
		break;
	default: 
		die("Unable to create reminder date-time.");
		break;
	}
	
	return $remindDateTime;
}

//Returns a string describing when an event is happening in plain english.
//This function is used when sending out emails/texts to user.
function getDateString($timeAmount) {
	switch($timeAmount) {
		case "start":
			return "right now.";
			break;
		case "ten_before":
			return "in 10 minutes.";
			break;
		case "thirty_before":
			return "in 30 minutes.";
			break;
		case "hour_before":
			return "in 1 hour.";
			break;
		case "day_before":
			return "in 1 day.";
			break;
		case "week_before":
			return "in 1 week.";
			break;
		default: 
			return "unknown event time.";
			break;
	}
}

?>