<?php
$this->header(_('Plugins'));
?>
<h1><?=_('Plugins')?></h1>
<div class="esqoo-flexigrid" id="pluginlist"
	data-flexigrid-url="/website/plugin-list/api"
	data-flexigrid-id-field="Identifier"
	data-flexigrid-usepager="true"
	data-flexigrid-col0-name="Identifier" 
	data-flexigrid-col0-display="<?=_('Plugin Name');?>"
	data-flexigrid-col0-width="30%"

	data-flexigrid-col1-name="Actions" 
	data-flexigrid-col1-display="<?=_('Actions');?>"	
	data-flexigrid-col1-width="70%"
	data-flexigrid-col1-sortable="false"

	data-flexigrid-searchitem0-name="Identifier"
	data-flexigrid-searchitem0-display="<?=_('Plugin Name');?>"
	data-flexigrid-searchitem0-isdefault="true"
></div>

<?php
$this->footer();
?>
