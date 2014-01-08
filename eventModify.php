<!DOCTYPE html>
<html>
	<head>
		<title>Modify Event Details</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<!--jquery timepicker css link-->
		<link type="text/css" rel="stylesheet" href="timepicker/jquery.timepicker.css" />
		<!--jquery ui custom theme-->
		<link type="text/css" rel="stylesheet" href="css/jqueryui.custom.css" />
		<!--datepicker css link-->
		<link rel="stylesheet" type="text/css" href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/css/bootstrap-combined.min.css">
		<!--timepicker css link-->
		<link rel="stylesheet" type="text/css" href="http://jdewit.github.com/bootstrap-timepicker/css/bootstrap-timepicker.min.css">
		<!--my own stylesheet--> 
		<link type="text/css" rel="stylesheet" href="css/mystyling.css" />
		
		
		<?php
		require_once 'functions.php';
		date_default_timezone_set('America/Los_Angeles');
		
		//php isn't included in beginning because css links need to be included before php prints any styled elements to the DOM
		
		//case if user doesn't have cookies set on their machine.
		if (!isset($_COOKIE['email']) || !isset($_COOKIE['phoneNumber'])) {
			missingCookieMsg();
		}
		
		//create new Event object
		$event = new Event();
		
		//we get email and phone number from previously stored cookies.		
		$event->getFromCookies();

		//we get eventName from user input on listEvents.php
		$event->set_name($_POST['eventName']);


		//connect to database. Represented by variable name $mysqli
		$mysqli = connectDatabase('localhost', 'gabepack_tester', 'test12', 'gabepack_reminders');
		
		
		//search database for row that matches email, phonenumber and event name.
		$data = $event->selectEventFromDatabase($mysqli);
		$row = $data->fetch_assoc();
		
		//we get rest of information from data stored in database, from $row variable
		$event->getFromDatabase($row);
		
		
		//we must process data to put back into 'user-interface' format
		//change eventDateTime to seperate date and time variables
		$event->dbToUItime();
		$event->dbToUIdate();
		

		
		$mysqli->close();
		?>
		
		
		
		
		
	</head>
	<body>
		
		<div class="wrapper wide topmargin center bottommargin">
			<h1 class="h1">Change any details you want, then click Submit!</h1>
		</div>	
		
		<div class="wrapper">
			<form action="confirmModify.php" method="post" id="event_form">

				<!--Event Name Input-->
				<label>
					Event Name: <br />
					<input type="text" name="eventName" id="eventName" class="input-large" value="<?php echo $event->get_name(); ?>"/>
				</label>


				<!--Event Date Input-->
				<label class="eventLabel">
					Event Date: <br />
					<input type="text" name="eventDate" id="eventDate" class="input-large" value="<?php echo $event->get_date(); ?>"/>
				</label>



				<!--Event Time Input-->
				<label class="eventLabel">
					Event Time:
				</label>
				<input id="timepicker" name="eventTime" type="text" value="<?php echo $event->get_time(); ?>" />


				<!--Event Reminder Input-->
				<label>
					Reminder: <br />
					<select name="time_before" class="large224">
						<option value="start" <?php echo $event->get_time_before() === "start" ? "selected='selected'" : ""; ?>>When Event Starts</option>
						<option value="ten_before" <?php echo $event->get_time_before() === "ten_before" ? "selected='selected'" : ""; ?>>10 min before</option>
						<option value="thirty_before" <?php echo $event->get_time_before() === "thirty_before" ? "selected='selected'" : ""; ?>>30 min before</option>
						<option value="hour_before" <?php echo $event->get_time_before() === "hour_before" ? "selected='selected'" : ""; ?>>1 hour before</option>
						<option value="day_before" <?php echo $event->get_time_before() === "day_before" ? "selected='selected'" : ""; ?>>1 day before</option>
						<option value="week_before" <?php echo $event->get_time_before() === "week_before" ? "selected='selected'" : ""; ?>>1 week before</option>
					</select>
				</label>



				<!-- Event Frequency Input-->
				<label>
					Frequency: <br />
					<select name="frequency" id="frequency" class="large224">
						<option value="one" <?php echo $event->get_freq() === "one" ? "selected='selected'" : ""; ?>>One-Time Event</option>
						<option value="week" <?php echo $event->get_freq() === "week" ? "selected='selected'" : ""; ?>>Weekly</option>
						<option value="month" <?php echo $event->get_freq() === "month" ? "selected='selected'" : ""; ?>>Monthly</option>
						<option value="year" <?php echo $event->get_freq() === "year" ? "selected='selected'" : ""; ?>>Yearly</option>
					</select>
				</label>



				<!--Event Notification Type Input-->
				<p>Notification Method:</p>
				<label  class="radio">
					<input type="radio" name="method" value="text" id="radio_text" class="radioHoriz" <?php echo $event->get_notif_method() === "text" ? "checked" : ""; ?>/> 
					Text
				</label>
				<label class="radio">
					<input type="radio" name="method" value="email" id="radio_email" class="radioHoriz" <?php echo $event->get_notif_method() === "email" ? "checked" : ""; ?>/> 
					E-mail
				</label>
				<label class="radio">
					<input type="radio" name="method" value="both" id="radio_both" class="radioHoriz" <?php echo $event->get_notif_method() === "both" ? "checked" : ""; ?>/> 
					Both
				</label>
				
				<input type="hidden" name="id" value=<?php echo $event->get_id(); ?> />


				<!--Submit Button-->
				<div class='center'>
					<input type="submit" name="submit2" id="submit" class="btn btn-primary" />
				</div>
				
			
			
			</form>
		</div>
		
		
		
		
		<p id='dontCare'>Hosting provided by <a href='http://vlexofree.com/'>VlexoFree Hosting</a></p>
		
		
		
		<!--jquery library-->
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
		<!--jquery ui library-->
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<!--jquery validation library-->
		<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.10.0/jquery.validate.js" type="text/javascript"></script>
		<!--jquery timepicker library-->
		<script type="text/javascript" src="timepicker/jquery.timepicker.js"></script>
		<!--my script for time and date to work-->
		<script type="text/javascript" src="js/page2.js"></script>
		<!--my jquery validation file-->
		<script type="text/javascript" src="js/checkEvent.js" charset="UTF-8"></script>
	</body>
</html>