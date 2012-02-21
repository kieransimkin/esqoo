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
			if (d.ErrorCount==0 && typeof(d.Token) != 'undefined') { 
				if ($('#login-forward').val().length) { 
					document.location=$('#login-forward').val();
					return;
				} else {
					document.location='/';
				}
			} else { 
				$('#login-challenge').val(d.Challenge);
				$('#login-challenge-id').val(d.ChallengeID);
				console.log(esqoo_format_api_errors(d.Errors));
			}
		}
	);
}
function esqoo_generate_password_hash(challenge,password) { 
	return Sha256.hash(challenge+password);
}
function esqoo_format_api_errors(errors) { 
	var ret='';
	$.each(errors,function(o,i) { 
		console.log('o: '+o+' i: '+i);
	});
	return ret;
}
