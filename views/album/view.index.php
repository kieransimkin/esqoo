<?php
$this->header(_('Albums'));
echo $this->form;
?>
<h1><?=_('Albums')?></h1>
<button data-icon-primary="ui-icon-plus" onclick="esqoo_ui.make_dialog({title:'<?=_('Create new album');?>'},'/album/add');"><?=_('Create new album');?></button>
<br>&nbsp;<br>
<script>
var esqoo_album={};
esqoo_album.actions= function (id,data) { 
	var ret = $('<div></div>');
	$('<button></button>').attr('data-icon-primary','ui-icon-pencil').html('<?=_('Manage')?>').attr('onclick','esqoo_album.manage('+id+'); return false;').appendTo(ret);
	//$('<button></button>').attr('data-icon-primary','ui-icon-trash').html('<?=_('Delete')?>').attr('onclick','esqoo_album.delete('+id+'); return false;').appendTo(ret);
	return ret;
}
esqoo_album.manage = function(id) { 
	esqoo_ui.make_dialog({singleton: true, title:'Manage Album'},'/album/manage/'+id);
}
esqoo_album.delete = function(id) { 
	console.log('delete '+id);	
}
</script>
<div class="esqoo-flexigrid" 
	data-flexigrid-url="/album/list/api"
	data-flexigrid-id-field="AlbumID"
	data-flexigrid-usepager="true"
	data-flexigrid-col0-name="Name" 
	data-flexigrid-col0-display="<?=_('Album Name');?>"
	data-flexigrid-col0-width="30%"

	data-flexigrid-col1-name="Actions" 
	data-flexigrid-col1-display="<?=_('Actions');?>"	
	data-flexigrid-col1-width="70%"
	data-flexigrid-col1-sortable="false"
	data-flexigrid-col1-js-filter="esqoo_album.actions"

	data-flexigrid-searchitem0-name="Name"
	data-flexigrid-searchitem0-display="Album Name"
	data-flexigrid-searchitem0-isdefault="true"
></div>
<?php
$this->footer();
?> 
