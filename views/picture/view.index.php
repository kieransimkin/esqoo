<?php
$this->header(_('Pictures'));
?><div class="picture-list-table-container"><table class="picture-list-table"><tr><td class="page-form-header"><br style="clear: both;" /><?php
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
