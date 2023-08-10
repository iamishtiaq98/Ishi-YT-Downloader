function showAlert(alertType, message) {
	var alertDiv = document.createElement('div');
	alertDiv.className = 'alert '+alertType+' alert-dismissible fade show';
	var messageSpan = document.createElement('span');
	messageSpan.innerHTML = message;
	var closeButton = document.createElement('button');
	closeButton.type = 'button';
	closeButton.className = 'btn-close';
	closeButton.setAttribute('data-bs-dismiss', 'alert');
	closeButton.setAttribute('aria-label', 'Close');
	var closeIcon = document.createElement('span');
	closeIcon.setAttribute('aria-hidden', 'true');
	closeButton.appendChild(closeIcon);
	alertDiv.appendChild(messageSpan);
	alertDiv.appendChild(closeButton);
	var alertContainer = document.getElementById('mail-status');
	alertContainer.appendChild(alertDiv);
}
$( "#contactForm" ).validate({
	rules: {
		s_name: "required",
		s_email: {
			required: true,
			email: true
		},
		s_phone: "required",
		s_subject: "required",
		s_message: {
			required: true,
			minlength: 5
		},
	},
	messages: {
		s_name: "Please enter your name",
		s_email: "Please enter a valid email address",
		s_phone: "Please enter a phone",
		s_subject: "Please enter subject",
		s_message: "Please enter a message",
	},
});
$(document).ready(function (e){
	$("#contactForm").on('submit',(function(e){
		e.preventDefault();
		if($("#contactForm").valid()) {
			$.ajax({
				url: "mail-send.php",
				type: "POST",
				data:  new FormData(this),
				dataType: 'json',
				contentType: false,
				cache: false,
				processData:false,
			}).done(function (result) {
				if (result.msg == 'success') {
					$('#contactForm')[0].reset();
					showAlert('alert alert-success' , `${result.response}`);
				} else if(result.msg == 'error') {
					showAlert('alert alert-danger' , `${result.response}`);
				}
			});
		} else {
			const inputElements = document.querySelectorAll('.contact-form .contact-form-wrapper .form-group .form-control');
			inputElements.forEach(inputElement => {
			
				if (inputElement.getAttribute('type') === 'file' ) {
				} else {
					inputElement.style.border = '2px solid red';
				}
			});
		}
	}));
});