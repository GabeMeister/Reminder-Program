<!DOCTYPE html>
<html>
	<head>
		<title>Add Event Details</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		
		<!--jquery timepicker css link-->
		<link type="text/css" rel="stylesheet" href="timepicker/jquery.timepicker.css" />
		<!--jquery ui datepicker custom theme-->
		<link type="text/css" rel="stylesheet" href="css/jqueryui.custom.css" />
		<!--bootstrap css link-->
		<link rel="stylesheet" type="text/css" href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/css/bootstrap-combined.min.css">
		<!--timepicker css link-->
		<link rel="stylesheet" type="text/css" href="http://jdewit.github.com/bootstrap-timepicker/css/bootstrap-timepicker.min.css">
		<!--my own stylesheet--> 
		<link type="text/css" rel="stylesheet" href="css/mystyling.css" />
		
		<?php
		
		require_once 'functions.php';
		

		//error check if user doesn't have cookies set on their machine.
		if(!isset($_COOKIE['phoneNumber']) || !isset($_COOKIE['email'])) {
			missingCookieMsg();
		}


		?>
		
		
		
	</head>
	<body>
		
		<div class="wrapper wide topmargin center bottommargin">
			<h1 class="h1">Add the details, then click Submit!</h1>
		</div>
		
		<div class="wrapper">
			<form action="confirmAdd.php" method="post" id="event_form">

				<!--Event Name Input-->
				<label>
					Event Name: <br />
					<input type="text" name="eventName" id="eventName" class="input-large"/>
				</label>


				<!--Event Date Input-->
				<label class="eventLabel">
					Event Date: <br />
					<input type="text" name="eventDate" id="eventDate" class="input-large"/>
				</label>



				<!--Event Time Input-->
				<label class="eventLabel">
					Event Time:
				</label>
				<input id="timepicker" name="eventTime" type="text" value='8:00 AM' />

				

				<!--Event Reminder Input-->
				<label>
					Reminder: <br />
					<select name="time_before" class="large224">
						<option value="start">When Event Starts</option>
						<option value="ten_before">10 min before</option>
						<option value="thirty_before">30 min before</option>
						<option value="hour_before">1 hour before</option>
						<option value="day_before">1 day before</option>
						<option value="week_before">1 week before</option>
				</select>
				</label>



				<!-- Event Frequency Input-->
				<label>
					Frequency: <br />
					<select name="frequency" id="frequency" class="large224">
						<option value="one">One-Time Event</option>
						<option value="week">Weekly</option>
						<option value="month">Monthly</option>
						<option value="year">Yearly</option>
					</select>
				</label>



				<!--Event Notification Type Input-->
				<p>Notification Method:</p>
				<label  class="radio">
					<input type="radio" name="method" value="text" id="radio_text" class="radioHoriz" checked/> 
					Text
				</label>
				<label class="radio">
					<input type="radio" name="method" value="email" id="radio_email" class="radioHoriz" /> 
					E-mail
				</label>
				<label class="radio">
					<input type="radio" name="method" value="both" id="radio_both" class="radioHoriz" /> 
					Both
				</label>


				
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

