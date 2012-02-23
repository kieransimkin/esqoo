var esqoo_helpers = {};
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
