<?php
$this->header(_('Tags'));
?>
<h1><?=_('Keyword Tags');?></h1>
<button data-icon-primary="ui-icon-plus" onclick="esqoo_ui.make_dialog({title:'<?=_('Create new tag');?>'},'/tag/add');"><?=_('Create new tag');?></button>
<?php
$this->footer();
?> 
