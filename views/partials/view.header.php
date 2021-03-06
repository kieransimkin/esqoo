<!DOCTYPE html>
<html>
<head>
	<title><?=$this->title?$this->title:'Dashboard'?> - Esqoo</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
<?php
	$this->cssManager->add("site","superfish","nav","jquery.ui.selectmenu","flexigrid","jquery.qtip");
	$this->cssManager->add("template.".$this->template);
//	$this->cssManager->add("query.ui.base","themes/base/jquery.ui.core","themes/base/jquery.ui.dialog");
	$this->cssManager->display();
	if (!is_null($this->user)) { 
		if ($this->user->DayState=='Daytime') { 
			$theme=$this->user->daytime__ui_theme->Tag;
		} else {
			$theme=$this->user->nighttime__ui_theme->Tag;
		}
	} else { 
		$theme='cupertino';
	}
	?>
	<link rel="stylesheet" href="/css/themes/<?=$theme?>/jquery.ui.all.css" />
<?php
	$this->cssManager->display();
	$this->jsManager->add("jquery-1.7.1.min", "jquery-ui-1.8.17.custom","modernizr-2.5.3","sha256","jquery.livequery","esqoo_helpers","esqoo_login","esqoo_ui","superfish","supersubs","jquery.ui.selectmenu","jquery.ui.uploadq","jquery.ui.combobox","flexigrid","jquery.pxem","jquery.timers","jquery.ui.qrichedit","jquery.qtip","jquery.ui.sqtip","jquery.ui.thumbnailbrowse","jquery.ui.doq","mediaslide/jquery.ui.mediaslide","fullscreenapi","jquery.history","jquery.ui.qdialog");
	$this->jsManager->add("site");
    	//$this->jsManager->add("jquery.livequery", "jquery.bgiframe.min", "jquery.tools.min", "flexigrid/flexigrid", "jquery.delayedobserver", "jquery.multiselects-0.3", "jquery.tmpl.1.1.1", "jquery.ui.selectmenu", "ui.checkbox", "jquery.blockUI", "ui.multiselect", "flot/jquery.flot", "flot/jquery.flot.pie", "flot/jquery.flot.selection", "flot/jquery.flot.navigate", "uploadify/swfobject", "uploadify/jquery.uploadify.v2.1.4.min", "superfish/superfish", "superfish/supersubs", "jquery.include", "jquery.timeago", "site", "ui", "tiny_mce/tiny_mce", "date");
	$this->jsManager->display();
	foreach ($this->jsFiles as $file)
		$this->jsManager->add($file);
	$this->jsManager->display();?>
<?=isset($this->head)?$this->head:''?>
<?
if ((isset($this->js) && $this->js) || (isset($this->jsOnloads) && $this->jsOnloads)) { ?>
	<script type="text/javascript">
	<!--
<?php 
	foreach ($this->js as $js) echo $js."\n\n";

	if ($this->jsOnloads) { ?>
		$(document,'ready',function() {
			<?php foreach ($this->jsOnloads as $js) echo $js."\n\n"; ?>
		});
	<?php } ?>
	-->
	</script>
<?php } ?>
<?php if ((array)$this->css) { ?>
	<style type="text/css">
	<?php foreach ($this->css as $css) echo $css."\n\n"; ?>
	</style>
<?php } ?>
	<script>
	WebFontConfig = {
		google: { families: [ 'Cantarell' ] }
	};
	esqoo_ui.visual_editor='<?=@$this->user->visual__rich_editor->Tag;?>';
	esqoo_ui.code_editor='<?=@$this->user->code__rich_editor->Tag;?>';
	esqoo_ui.nighttime_theme='<?=@$this->user->nighttime__ui_theme->Tag;?>';
	esqoo_ui.daytime_theme='<?=@$this->user->daytime__ui_theme->Tag;?>';
	esqoo_ui.daystate='<?=@$this->user->DayState;?>';
	</script>
	<script src="http://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js"></script>
</head>
<body class="controller-<?=substr(strtolower($this->controller),14);?> action-<?=substr(strtolower($this->controller),14).'-'.strtolower($this->controller->action);?> template-<?=strtolower($this->template);?> <?=strtolower($this->user->DayState);?>">

