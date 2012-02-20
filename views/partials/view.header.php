<!DOCTYPE html>
<html>
<head>
	<title><?=$this->title?$this->title:'Dashboard'?> - Esqoo</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
<?php
//	$this->cssManager->add("query.ui.base","themes/base/jquery.ui.core","themes/base/jquery.ui.dialog");
	$this->cssManager->display();
	$theme=@$this->options['theme'];
	if (strlen($theme)<1) { 
		$theme='ui-darkness';
	}
	?>
	<link rel="stylesheet" href="/css/themes/<?=$theme?>/jquery.ui.all.css" />
<?php
	$this->cssManager->display();
	$this->jsManager->add("jquery-1.7.1.min", "jquery-ui-1.8.17.custom");
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

</head>
<body class="<?=$this->controller;?> <?=$this->controller.'-'.$this->action;?>">
