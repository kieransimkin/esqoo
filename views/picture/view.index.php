<?php
$this->header(_('Pictures'));
if (count($this->picturesizes)>0) { 
	?>
	<script>
	<?php
	foreach ($this->picturesizes as $picturesize) { 
		print "esqoo_picture_index.picturesizes['".$picturesize->picture_size_type."']='".$picturesize->size."';\n";
	}
	?>
	</script>
	<?php
}
?><div class="picture-list-table-container"><table class="picture-list-table" cellpadding="0" cellspacing="0"><tr><td class="page-form-header"><br style="clear: both;" /><?php
echo $this->form;
?>
</td></tr><tr><td class="page-content">
<div class="picture-list-rel-container"><div id="picturelist-flexigrid" style="z-index: 1; display: none;" class="picture-list-container"><div></div></div>
<div id="picturelist-thumbnailbrowse" style="z-index: 2; display: none;" class="picture-list-container"></div>
<div id="picturelist-mediaslide" style="z-index: 2; display: none;" class="picture-list-container"></div>
</div>
</td></tr></table></div>
<?php
$this->footer();
?>  
