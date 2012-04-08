<?php
$this->header(_('Pictures'));
echo $this->form;
?>
<br style="clear: both;" />
<div class="esqoo-picture-list-mediaslide-container" style="display: none;">
	MediaSlide
	<div class="esqoo-mediaslide" id="picturelist-mediaslide"
	data-mediaslide-url="/album/list-pictures/api"
	></div>
</div>
<div class="esqoo-picture-list-thumbnailbrowse-container" style="display: none;">
	Thumbnail Browse
	<div class="esqoo-thumbnailbrowse" id="picturelist-thumbnailbrowse"
	data-thumbnailbrowse-url="/album/list-pictures/api"
	></div>
</div>
<div class="esqoo-picture-list-flexigrid-container" style="display: none;">
	<div class="esqoo-flexigrid" id="picturelist-flexigrid"
		data-flexigrid-url="/album/list-pictures/api"
		data-flexigrid-id-field="PictureID"
		data-flexigrid-usepager="true"
		data-flexigrid-col0-name="Name" 
		data-flexigrid-col0-display="<?=_('Picture Name');?>"
		data-flexigrid-col0-width="30%"

		data-flexigrid-col1-name="Actions" 
		data-flexigrid-col1-display="<?=_('Actions');?>"	
		data-flexigrid-col1-width="70%"
		data-flexigrid-col1-sortable="false"
		data-flexigrid-col1-js-filter="esqoo_picture_index.actions"

		data-flexigrid-searchitem0-name="Name"
		data-flexigrid-searchitem0-display="<?=_('Picture Name');?>"
		data-flexigrid-searchitem0-isdefault="true"
	></div>
</div>
<?php
$this->footer();
?>  
