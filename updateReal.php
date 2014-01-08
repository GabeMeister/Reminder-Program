<?php

require_once 'functions.php';
date_default_timezone_set('America/Los_Angeles');

$event = new Event();

//mysqli version
$mysqli = connectDatabase('localhost', 'gabepack_tester', 'test12', 'gabepack_reminders');
$data = $event->selectAllFromDatabase($mysqli);

//set the current datetime
$right_now = new DateTime();
$now_string = date_format($right_now, "h:i:s a F d, Y");
//print out present date-time
print "Right now is: " . $now_string . "<br /><br /><br />";

$reminder_obj = new DateTime();

//loop through whole reminders table
//grabs one row at a time.
//checks if row needs to be updated.
while ($row = $data->fetch_assoc()) {
	//copies all variables from $row to Event class
	$event->getEverythingFromDatabase($row);
	
	//change reminder object to the event reminder, to compare to now.
	$reminder_obj->modify($event->get_reminder_date_time());
	
	//Condition if event is ready to be notified to user.
	//We determine this if event is in the past.
	//Reminder object can be compared using a comparison operator
	//If event reminder is less than or equal to right now, then the event is ready to be notified
	if($reminder_obj <= $right_now) {
		///////////////////////////////////////////////PLACE WHERE EMAILS ARE SENT////////////////////////////////////////////////////
		///////////////////////FOR NOW, THE PROGRAM ONLY DISPLAYS IN BROWSER WHAT WE ARE ARE GOING TO DO//////////////////////////////
		
		
		//case for just email
		if($event->get_notif_method() === "email") {
			$to = $event->get_email();
			$subject = "Event Reminder!";
			$msg = "You have the event \"" . $event->get_name() . "\" happening " . getDateString($event->get_time_before());
		}
		//case for just phone number
		else if($event->get_notif_method() === "text") {
			$to = $event->get_phone_number();
			$subject = "Event Reminder!";
			$msg = "You have the event \"" . $event->get_name() . "\" happening " . getDateString($event->get_time_before());
		}
		//case for both
		else {
			$to = $event->get_email();
			$subject = "Event Reminder!";
			$msg = "You have the event \"" . $event->get_name() . "\" happening " . getDateString($event->get_time_before());
			
			mail($to, $subject, $msg);
			
			$to = $event->get_phone_number();
		}
		
//		$headers = "From:thegabe@gabesdigest.com";
		$headers = "";
		
		//send the mail!
		$sent = mail($to, $subject, $msg, $headers);
		
		print "Sending mail to: " . $to . " from: " . $headers . "<br />";
		
		if($sent) {
			print "Message sent. <br />";
		}
		else {
			print "Message not sent. <br />";
		}

		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		//if event was only one-time, then just delete event from database.
		if($event->get_freq() === "one") {
			$event->deleteEventFromDatabaseById($mysqli);
		}
		//if event is weekly, monthly, or yearly, then we need to update event, not delete.
		else {
			//set event time and reminder forward how ever long the user specified. (1 week, 1 month,...etc.)
			$event->setForward();
			
			//the sql statement to update an event that will happen again in the future.
			$event->updateEventFromDatabase($mysqli);
		}
	}
	
}

$mysqli->close();
?>