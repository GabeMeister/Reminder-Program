$(document).ready(function() {
	$('#event_form').validate({
		onfocusout: false,
		onready: false,
		onclick: false,
		onkeyup: false,
		focusInvalid: false,
		rules: {
			eventName: {
				required: true
			},
			eventDate: {
				required: true,
				date: true,
				past_date: true
			},
			reminder: {
				required: true,
				past_reminder: true
			},
			timepicker: {
				required: true
			},
			frequency: {
				required: true
			}


		},
		messages: {
			timepicker: {
				required: "This field is required."
			}
		}
	});
});

$.validator.addMethod("past_date",
		function(value, element, params) {
			var time = $('#timepicker').val();
			var event_date = new Date(value + " " + $('#timepicker').val());
			var today = new Date();
			
			
			return event_date > today;
		},
		'Event must happen in future.');



$.validator.addMethod("past_reminder",
		function(value, element, params) {
			var event_date = new Date($('#eventDate').val() + " " + $('#timepicker').val());
			var remind_date = new Date(event_date);
			var now = new Date();
			var reminder = value;
			
			//subtract time before from date
			if(reminder === "start") {
				//don't do anything, because it is the same time
			}
			else if(reminder === "ten_before") {
				remind_date.setMinutes(event_date.getMinutes() - 10);
			}
			else if(reminder === "thirty_before") {
				remind_date.setMinutes(event_date.getMinutes() - 30);
			}
			else if(reminder === "hour_before") {
				remind_date.setHours(event_date.getHours() - 1);
			}
			else if(reminder === "day_before") {
				remind_date.setDate(event_date.getDate() - 1);
			}
			else if(reminder === "week_before") {
				remind_date.setDate(event_date.getDate() - 7);
			}
			else {
				die("Checking reminder time had an error.<br />");
			}
			
			return remind_date > now;
		},
		"Reminder must happen in future and before event.");