<?php
$this->header(_('Tags'));
?>
<h1><?=_('Keyword Tags');?></h1>
<button data-icon-primary="ui-icon-plus" onclick="esqoo_ui.make_dialog({title:'<?=_('Create new tag');?>'},'/tag/add');"><?=_('Create new tag');?></button>
<br>&nbsp;<br>
<div class="esqoo-flexigrid" id="taglist"
	data-flexigrid-url="/tag/list/api"
	data-flexigrid-id-field="TagID"
	data-flexigrid-usepager="true"
	data-flexigrid-col0-name="Name" 
	data-flexigrid-col0-display="<?=_('Tag');?>"
	data-flexigrid-col0-width="30%"

	data-flexigrid-col1-name="Actions" 
	data-flexigrid-col1-display="<?=_('Actions');?>"	
	data-flexigrid-col1-width="70%"
	data-flexigrid-col1-sortable="false"
	data-flexigrid-col1-js-filter="esqoo_tag_index.actions"

	data-flexigrid-searchitem0-name="Name"
	data-flexigrid-searchitem0-display="<?=_('Tag');?>"
	data-flexigrid-searchitem0-isdefault="true"
></div>

<?php
$this->footer();
?> 
