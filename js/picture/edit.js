$(document).ready(function() { 
	$('#edit-picture-container').doq();
	esqoo_ui.make_dialog({doq: $('#edit-picture-container')},'/picture/navigator');
}); 
