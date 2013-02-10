<?php
$this->header(_('Pages'));
?>
<h1><?=_('Pages')?></h1>
<button data-icon-primary="ui-icon-plus" onclick="esqoo_ui.make_dialog({title:'<?=_('Quick Add');?>'},'/page/quick-add');"><?=_('Quick Add');?></button>
<br>&nbsp;<br>
<div class="esqoo-flexigrid" id="pagelist"
	data-flexigrid-url="/page/list/api"
	data-flexigrid-id-field="PageID"
	data-flexigrid-usepager="true"
	data-flexigrid-col0-name="Title" 
	data-flexigrid-col0-display="<?=_('Title');?>"
	data-flexigrid-col0-width="30%"

	data-flexigrid-col1-name="Actions" 
	data-flexigrid-col1-display="<?=_('Actions');?>"	
	data-flexigrid-col1-width="70%"
	data-flexigrid-col1-sortable="false"

	data-flexigrid-searchitem0-name="Title"
	data-flexigrid-searchitem0-display="<?=_('Title');?>"
	data-flexigrid-searchitem0-isdefault="true"
></div>
<?php
$this->footer();
?>
