function esqoo_login() { 
	var fieldname='Username';
	if ($('#login-identity').val().indexOf('@')) { 
		fieldname='Email';
	}
	$.post('/auth/authenticate/api/', { 
			'ResponseFormat': 'json',
			fieldname: $('#login-identity').val(),
			'ChallengeID': $('#login-challenge-id').val(),
			'HashType': 'SHA256',
			'Response': esqoo_generate_password_hash($('#login-challenge').val(),$('#login-password').val())
		}, function(data) { 
			var d=$.parseJSON(data);
			console.log(d.ErrorCount);
			console.log(d.Token.length);
			if (d.ErrorCount==0 && d.Token.length) { 
				console.log('Login successful');
			} else { 
				$('#login-challenge').val(d.Challenge);
				$('#login-challenge-id').val(d.ChallengeID);
				console.log('Login failed');
			}
		}
	);
}
function esqoo_generate_password_hash(challenge,password) { 
	return Sha256.hash(challenge+password);
}
