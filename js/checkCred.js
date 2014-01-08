$(document).ready(function() {
	$('#person_info').validate({
		onfocusout: false,
		onready: false,
		onclick: false,
		onkeyup: false,
		focusInvalid: false,
		rules: {
			
			email: {
				required: true,
				email: true
			},
			phoneNumber: {
				required: true,
				minlength: 14,
				maxlength: 14
			},
			carrier: {
				required: true
			}
		},
		messages: {
			email: {
				required: "Email is required.",
				email: "Enter valid email."
			},
			phoneNumber: {
				required: "Please enter phone number.",
				minlength: "Enter valid phone number.",
				maxlength: "Enter valid phone number."
			}
		}
	});
});