<?php
$nav=array(
	'&lambda;' => array(
		"/" => _('Dashboard'),
		"popup:"._('Quick Media Upload').":save,cancel,singleton:/content/quick-upload" => _('Quick Upload'),
		"popup:"._('Quick Post').":save,cancel,singleton:/blog/quick-post" => _('Quick Post'),
		"popup:"._('Visit My Website').":cancel,continue,singleton:/website" => _('Visit My Website'),
		"/lightswitch/flick" => ('Flick Light Switch')
	),
	_('Content') => array(
		"submenu:"._('Media') => array(
			"/content/upload" => _('Upload Media'),
			"/content/manage-images" => _('My Images'),
			"/content/manage-video" => _('My Video'),
			"/content/manage-audio" => _('My Audio')
		),
		"submenu:"._('Posts and Pages') => array(
			"/blog/post" => _('Write Blog Post'),
			"/page/add-page" => _('Add Page'),
			"/blog/manage-posts" => _('Blog Posts'),
			"/page/manage-pages" => _('My Pages'),
			"/links" => _('My Links')
		),
	),
	_('Websites') => array(
		"/website/manage-websites" => _('My Websites'),
		"/website/menus" => _('Menus'),
		"submenu:"._('Keyword Tags') => array(
			"/tag/add" => _('Add Keyword'),
			"/tag" => _('My Keywords')
		)
	),
	_('Account') => array(
		"popup:"._('Account Details').":save,cancel,singleton:/account/details" => _('Account Details'),
		"popup:"._('Account Settings').":save,cancel,singleton:/account/settings" => _('Settings'),
		"popup:"._('Change Password').":save,cancel,singleton:/account/password" => _('Change Password'),
		"/account/logout" => _('Logout')
	)
);?>
<nav>
<ul id="nav-one" class="nav ui-tabs-nav ui-helper-clearfix ui-widget-header ui-corner-bottom inner-padded">
<?php
function render_nav_element($url,$item,$user) {
	$content='';
	if (strpos($url,'submenu:')===0) { 
		list($submenu,$title)=explode(':',$url,2);
		$content .= "<li class=\"ui-menubar-default ui-corner-all\"><a href=\"#\" onclick=\"return false;\">$title <div class=\"nav-float-right\">&raquo;</div></a>";
		$content .= "<ul class=\"ui-state-default ui-corner-all\">";
		$count=0;
		foreach($item as $turl => $titem) {
			$thiscontent=render_nav_element($turl,$titem,$user);	
			$content.=$thiscontent;
			if (strlen($thiscontent)>0) { 
				++$count;
			}
		}
		$content .= "</ul></li>";
		if ($count==0) { 
			return '';
		}
	} else if (strpos($url,"popup:")===0) {
		list($popup,$title,$buttons,$realurl)=explode(':',$url,5);
		list($controller, $action) = explode("/", substr($realurl, 1));
		$buttonlist=explode(",",$buttons);
		$buttonstring='';
		if (!in_array('save',$buttonlist)) {
			$buttonstring.="savebutton: 0, ";
		}
		if (!in_array('close',$buttonlist)) { 
			$buttonstring.="closebutton: 0, ";
		}
		if (in_array('ok',$buttonlist)) { 
			$buttonstring.="okbutton: 1, ";
		}
		if (in_array('continue',$buttonlist)) { 
			$buttonstring.="continuebutton: 1, ";
		} 
		if (in_array('post',$buttonlist)) { 
			$buttonstring.="postbutton: 1, ";
		} 
		if (in_array('done',$buttonlist)) { 
			$buttonstring.="donebutton: 1, ";
		}
		if (in_array('cancel',$buttonlist)) { 
			$buttonstring.="cancelbutton: 1, ";
		}
		if (in_array('singleton',$buttonlist)) { 
			$buttonstring.="singleton: true, ";
		}
		$content .= "<li class=\"ui-menubar-default ui-corner-all\"><a href=\"$realurl\" onclick=\"esqoo_ui.make_dialog({ $buttonstring title: '$title' },'$realurl'); return false;\">$item...</a></li>";

	} else if (strpos($url,"targetblank:")===0) {
		list($tgtblank,$realurl)=explode(':',$url,2);
		list($controller, $action) = explode("/", substr($realurl, 1));
		$content .= "<li class=\"ui-menubar-default ui-corner-all\"><a href=\"$realurl\" target=\"_blank\">$item... </a><div class=\"nav-float-right\"><span class=\"ui-icon ui-icon-extlink\"></span></div></li>";

	} else {
		list($controller, $action) = explode("/", substr($url, 1));
		$unopened=0;
		$content .= "<li class=\"ui-menubar-default ui-corner-all\"><a href=\"$url\" onclick=\"create_page_loading_overlay();\">$item</a></li>";
	}
	return $content;
}
$first=' lambda ui-widget-header ui-corner-bottom';
foreach($nav as $heading => $menu) {
    	$content = '';
	foreach($menu as $url => $item) {
		$content.=render_nav_element($url,$item,$this->user);	
	}
	if($content) {
		?>
		<li class="ui-menubar-heading-default<?=$first;?>">
			<a class="menubar-title-heading" href="#<?=str_replace(' ','-',strtolower($heading))?>" onclick="return false"><?=$heading?></a>
			<ul class="ui-state-default ui-corner-bottom ui-corner-tr">
			<?=$content?>
			</ul>
		</li>
		<?
	}
	if (strpos($first,'lambda')!==FALSE) { 
		$first=' second ui-corner-top';
	} else {
		$first=' ui-corner-top';
	}
}

?>
</ul>
</nav>
