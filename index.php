<?php

require_once 'functions.php';

//case if cookies are already previously set, meaning the user has already filled out form and
//	is returning to fill out another event.
if (isset($_COOKIE['email']) && isset($_COOKIE['phoneNumber'])) {
	header("Location: menu.php");
}

//case if user has come for the first time, and filled out info on the page.
//just have to check submit field, because jquery validator checks that every input is filled out anyway
if (isset($_POST['submitCred'])) {
	
	$event = new Event();
	
	$event->set_phone_number($_POST['phoneNumber']);
	$event->set_email($_POST['email']);
	$event->set_carrier($_POST['carrier']);
	
	
	$event->changeNumber();
	
	$event->setCookies();
	
	if(isset($_COOKIE['phoneNumber'])) {
		print "the email cookie is set.<br />";
		die();
	}
	
	//relocate page to menu.php. User is done filling out credentials
	header("Location: menu.php");
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Info About You</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link type="text/css" rel="stylesheet" href="css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="css/mystyling.css" />


	</head>
	<body>

		<div class="center topmargin">
			<h1>Fill out your info!</h1>
		</div>
		
		<div class="wrapper">
			
			<form action="index.php" id="person_info" method="post">

				<!--Email Input-->
				<label>
					Email:<br />
					<input type="text" name="email" class="input-large"placeholder="myemail@example.com"/>
				</label>



				<!--Phone Number Input-->
				<label>
					Phone Number:<br />
					<input type="text" class="input-large bfh-phone" name="phoneNumber" placeholder="( XXX ) XXX-XXXX" data-format="(ddd) ddd-dddd" />
				</label>


				<!--Phone Carrier Input-->
				<label class="text">Carrier:<br />
					<select name="carrier" class="large224" id="carrier">
						<option value="verizon">Verizon</option>
						<option value="at&t">AT&T</option>
						<option value="sprint">Sprint</option>
						<option value="tmobile">T-mobile</option>
					</select>
				</label>

				<!--Submit Button-->
				<div class='center'>
					<input type="submit" value="Next Page" name="submitCred" id="submit" class="btn btn-primary"/>
				</div>
				


			</form>
		</div>

		<p id='dontCare'>Hosting provided by <a href='http://vlexofree.com/'>VlexoFree Hosting</a></p>
		
		
		<!--jquery-->
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
		<!--jquery validation-->
		<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.10.0/jquery.validate.js"></script>
		<!--my jquery validation file-->
		<script type="text/javascript" src="/js/checkCred.js" charset="UTF-8"></script>
		<!--bootstrap form helper phone script-->
		<script type="text/javascript" src="/js/bootstrap-formhelpers-phone.format.js"></script>
		<script type="text/javascript" src="/js/bootstrap-formhelpers-phone.js"></script>
	</body>
</html>