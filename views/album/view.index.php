<?php
$this->header(_('Albums'));
echo $this->form;
?>
<h1><?=_('Albums')?></h1>
<button data-icon-primary="ui-icon-plus" onclick="esqoo_ui.make_dialog({title:'<?=_('Create new album');?>'},'/album/add');"><?=_('Create new album');?></button>
<div class="esqoo-flexigrid" 
	data-flexigrid-url="/album/list/api"
	data-flexigrid-usepager="true"
	data-flexigrid-col0-name="Name" 
	data-flexigrid-col0-display="<?=_('Album Name');?>"
	data-flexigrid-col0-width="30%"

	data-flexigrid-col1-name="Actions" 
	data-flexigrid-col1-display="<?=_('Actions');?>"	
	data-flexigrid-col1-width="70%"
></div>
<?php
$this->footer();
?> 
