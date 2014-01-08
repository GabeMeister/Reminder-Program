$(document).ready(function() {
	$('#eventDate').datepicker({});
	$('#timepicker').timepicker({
		step: 30,
		timeFormat: "h:i A"
	});
});