function esqoo_login() { 
	var fieldname='Username';
	if ($('#login-identity').val().indexOf('@')) { 
		fieldname='Email';
	}
	$.getJSON( 
		'/auth/authenticate/api',
		{ 
			'ResponseFormat': 'json',
			fieldname: $('#login-identity').val(),
			'ChallengeID': $('#login-challenge-id').val(),
			'ResponseHashType': 'SHA256',
			'Response': esqoo_generate_password_hash($('#login-challenge').val(),$('#login-password').val())
		}, function(data) { 
			console.log(data);
		}
	);
}
function esqoo_generate_password_hash(challenge,password) { 
	return challenge+password;
}
