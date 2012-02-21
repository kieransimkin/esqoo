var esqoo_login = {};
var esqoo_helpers = {};
esqoo_login.login = function (c) { 
	// Prevent infinite loops:
	if (typeof(c)=='undefined') { 
		c=1;
	}
	if (c>10) { 
		return;
	}
	var fieldname='Username';
	if ($('#login-identity').val().indexOf('@')) { 
		fieldname='Email';
	}
	$.post('/auth/authenticate/api', { 
			'ResponseFormat': 'json',
			fieldname: $('#login-identity').val(),
			'ChallengeID': $('#login-challenge-id').val(),
			'HashType': 'SHA256',
			'Response': esqoo_login.generate_password_hash($('#login-challenge').val(),$('#login-password').val())
		}, function(data) { 
			var d=$.parseJSON(data);
			if (d.ErrorCount==0 && typeof(d.Token) != 'undefined') { 
//				$.cookie('UserID',d.UserID,{expires: 365, path: '/'});
//				$.cookie('TokenID',d.TokenID,{expires: 365, path: '/'});
//				$.cookie('Token',d.Token,{expires: 365, path: '/'});
				if ($('#login-forward').val().length) { 
					document.location=$('#login-forward').val();
					return;
				} else {
					document.location='/';
				}
			} else { 
				$('#login-challenge').val(d.Challenge);
				$('#login-challenge-id').val(d.ChallengeID);
				if (esqoo_helpers.errors_contains(4,d.Errors)) { 
					esqoo_login.login(c++);
				} else { 
					console.log(esqoo_helpers.format_api_errors(d.Errors));
				}
			}
		}
	);
}
esqoo_login.form_keypress = function(e) { 
	if (e.which == 13) { 
		esqoo_login.login();
	}
}
esqoo_login.generate_password_hash = function(challenge,password) { 
	return Sha256.hash(challenge+password);
}
esqoo_helpers.format_api_errors = function(errors) { 
	var ret='';
	$.each(errors,function(i,o) { 
		ret=ret+o.String+"<br />\n";
	});
	return ret;
}
esqoo_helpers.errors_contains = function(needle,haystack) { 
	var found=false;
	$.each(haystack,function(i,o) { 
		if (o.Code==needle) { 
			found=true;	
			return false;
		}
	});
	if (found) { 
		return true;
	} else { 
		return false;
	}
}
