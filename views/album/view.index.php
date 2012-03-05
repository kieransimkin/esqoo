<?php
$this->header(_('Albums'));
echo $this->form;
?>
<h1><?=_('Albums')?></h1>
<button data-icon-primary="ui-icon-plus" onclick="esqoo_ui.make_dialog({title:'<?=_('Create new album');?>'},'/album/add');"><?=_('Create new album');?></button>
<div class="esqoo-flexigrid" data-flexigrid-url="/album/list/api"></div>
<?php
$this->footer();
?> 
