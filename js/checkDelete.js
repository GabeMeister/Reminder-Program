$(document).ready(function() {
	$("deleteForm").submit(function() {
		if (!confirm('Are you sure you want to delete this event?')) {
			event.preventDefault();
		}
	});
});