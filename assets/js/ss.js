	submitHandler: function(form) {		
					var $submit = $('.submitting'),
					waitText = 'Submitting...';

					$.ajax({   	
						url: "mail-send.php",
						type: "POST",
						data:  new FormData('#contactForm'),
						contentType: false,
						cache: false,
						processData:false,

						beforeSend: function() { 
							$submit.css('display', 'block').text(waitText);
						},
						success: function(msg) {
							if (msg == 'OK') {
								$('#form-message-warning').hide();
								setTimeout(function(){
									$('#contactForm').fadeOut();
								}, 1000);
								setTimeout(function(){
									$('#form-message-success').fadeIn();   
								}, 1400);
								
							} else {
								$('#form-message-warning').html(msg);
								$('#form-message-warning').fadeIn();
								$submit.css('display', 'none');
							}
						},
						error: function() {
							$('#form-message-warning').html("Something went wrong. Please try again.");
							$('#form-message-warning').fadeIn();
							$submit.css('display', 'none');
						}
					});    		
				}