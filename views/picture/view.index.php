<?php
$this->header(_('Pictures'));
?><div class="picture-list-table-container"><table class="picture-list-table"><tr><td class="page-form-header"><br style="clear: both;" /><?php
echo $this->form;
?>
</td></tr><tr><td class="page-content">
<div class="picture-list-rel-container"><div id="picturelist-1" style="z-index: 1;" class="picture-list-container"></div>
<div id="picturelist-2" style="z-index: 2; visibility: hidden;" class="picture-list-container"></div>
</div>
</td></tr></table></div>
<?php
$this->footer();
?>  
