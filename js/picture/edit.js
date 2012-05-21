$(document).ready(function() { 
	$('#edit-picture-container').doq();
	esqoo_ui.make_dialog({closebutton:0,savebutton:0,doq: $('#edit-picture-container')},'/picture/navigator');
}); 
