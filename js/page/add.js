$(document).ready(function() { 
	$('#edit-page-container').doq({fixed: true});
	esqoo_ui.make_dialog({fixed: true, doq: $('#edit-page-container'), title:'Dialog 2',closebutton:0,savebutton:0},'/picture/navigator');
});
