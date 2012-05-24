<?php
$this->header(_('Blog Entries'));
?>
<h1><?=_('Blog Entries')?></h1>
<button data-icon-primary="ui-icon-plus" onclick="esqoo_ui.make_dialog({title:'<?=_('Quick Post');?>'},'/blog/quick-post');"><?=_('Quick Post');?></button>
<br>&nbsp;<br>
<div class="esqoo-flexigrid" id="blogentrylist"
	data-flexigrid-url="/blog/list/api"
	data-flexigrid-id-field="EntryID"
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
