$(document).ready(function() { 
	$('#edit-picture-container').doq();
	esqoo_ui.make_dialog({title:'Dialog 2',closebutton:0,savebutton:0,doq: $('#edit-picture-container')},'/picture/navigator');
	esqoo_ui.make_dialog({title:'Dialog 3',closebutton:0,savebutton:0,doq: $('#edit-picture-container')},'/picture/navigator');
	esqoo_ui.make_dialog({title:'Dialog 1',closebutton:0,savebutton:0,doq: $('#edit-picture-container')},'/picture/navigator');
}); 
