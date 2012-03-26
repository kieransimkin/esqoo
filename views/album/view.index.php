<?php
$this->header(_('Albums'));
?>
<h1><?=_('Albums')?></h1>
<button data-icon-primary="ui-icon-plus" onclick="esqoo_ui.make_dialog({title:'<?=_('Create new album');?>'},'/album/add');"><?=_('Create new album');?></button>
<br>&nbsp;<br>
<div class="esqoo-flexigrid" id="albumlist"
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
	data-flexigrid-col1-js-filter="esqoo_album_index.actions"

	data-flexigrid-searchitem0-name="Name"
	data-flexigrid-searchitem0-display="<?=_('Album Name');?>"
	data-flexigrid-searchitem0-isdefault="true"
></div>
<?php
$this->footer();
?> 
