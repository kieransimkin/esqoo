var esqoo_ui = {};
esqoo_ui.make_dialog = function(options,url,params,modal) { 
	var dialog=$("<div></div>").dialog($.extend({
		autoOpen: true,
		modal: modal,
		title: "",
		position:'center',
		buttons: [],
		open: function() {
		}	

	},options || {}));
}
