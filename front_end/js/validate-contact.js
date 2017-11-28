$(function()
{
	// Validation
	$("#contact-form").validate(
	{					
		// Rules for form validation
		rules:
		{
			name:
			{
				required: true
			},
			email:
			{
				required: true,
				email: true
			},
			message:
			{
				required: true,
				minlength: 10
			},
			captcha:
			{
				required: false,
				remote: '../php/captcha/process.php'
			}
		},
							
		// Messages for form validation
		messages:
		{
			name:
			{
				required: 'Please enter your name',
			},
			email:
			{
				required: 'Please enter your email address',
				email: 'Please enter a VALID email address'
			},
			message:
			{
				required: 'Please enter your message'
			},
			captcha:
			{
				required: 'Please enter characters',
				remote: 'Correct captcha is required'
			}
		},
							
		// Ajax form submition					
		submitHandler: function(form)
		{
			$(form).ajaxSubmit(
			{
				beforeSend: function()
				{
					$('#contact-form button[type="submit"]').attr('disabled', true);
				},
				success: function()
				{
					$("#contact-form").addClass('submited');
				}
			});
		},
		
		// Do not change code below
		errorPlacement: function(error, element)
		{
			error.insertAfter(element.parent());
		}
	});
});