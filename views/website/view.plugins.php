<?php
$this->header(_('Plugins'));
?>
<h1><?=_('Plugins')?></h1>
<div class="esqoo-flexigrid" id="pluginlist"
	data-flexigrid-url="/website/plugin-list/api"
	data-flexigrid-id-field="Identifier"
	data-flexigrid-usepager="false"
	data-flexigrid-col0-name="Identifier" 
	data-flexigrid-col0-display="<?=_('Identifier');?>"
	data-flexigrid-col0-width="25%"
	
	data-flexigrid-col1-name="Name"
	data-flexigrid-col1-display="<?=_('Name');?>"
	data-flexigrid-col1-width="35%"

	data-flexigrid-col2-name="Actions" 
	data-flexigrid-col2-display="<?=_('Actions');?>"	
	data-flexigrid-col2-width="40%"
	data-flexigrid-col2-sortable="false"
	data-flexigrid-col2-js-filter="esqoo_website_plugins.actions"

	data-flexigrid-searchitem0-name="Identifier"
	data-flexigrid-searchitem0-display="<?=_('Identifier');?>"
	data-flexigrid-searchitem0-isdefault="true"
	data-flexigrid-searchitem1-name="Name"
	data-flexigrid-searchitem1-display="<?=_('Name');?>"
	data-flexigrid-searchitem1-isdefault="false"
></div>

<?php
$this->footer();
?>
