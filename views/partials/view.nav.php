<?php
$nav2=new Menu(array(
	new Menu('&lambda;',array(
		new MenuLeafNode_Go('/',_('Dashboard')),
		new MenuLeafNode_Popup(
			'/content/quick-upload', 					// URL
			_('Quick Media Upload'), 					// Dialog Title
			new MenuLeafNode_Popup_Buttons(''),				// Buttons
			array('singleton'=>'true')					// Options
			,_('Quick Upload')						// Menu item label
		),
		new MenuLeafNode_Popup(
			'/blog/quick-post',						// URL
			_('Quick Post'),						// Dialog Title	
			new MenuLeafNode_Popup_Buttons('save,cancel'),			// Buttons
			array('singleton'=>'true'),					// Options
			_('Quick Post')							// Menu item label
		),
		new MenuLeafNode_TargetBlank('/website/',_('Visit My Website')),
		new MenuLeafNode_JSAction('esqoo_ui.flick_light_switch();',_('Flick Light Switch'))
	)),
	new Menu(_('Content'),array(
		new Menu(_('Media'), array(
			new MenuLeafNode_Go('/content/upload',_('Upload Media')),
			new MenuLeafNode_Go('/album/',_('Albums')),
			new MenuLeafNode_Go('/picture/',_('Pictures')),
			new MenuLeafNode_Go('/video/',_('Video')),
			new MenuLeafNode_Go('/audio/',_('Audio'))
		)),
		new Menu(_('Posts and Pages'),array(
			new MenuLeafNode_Go('/blog/post',_('Write Blog Post')),
			new MenuLeafNode_Go('/page/add',_('Add Page')),
			new MenuLeafNode_Go('/blog/',_('Blog Posts')),
			new MenuLeafNode_Go('/page/',_('Pages')),
			new MenuLeafNode_Go('/link/',_('Links'))
		)),
		new Menu(_('Keyword Tags'),array(
			new MenuLeafNode_Popup(
				'/tag/add',							// URL
				_('Add Keyword'),						// Dialog Title	
				new MenuLeafNode_Popup_Buttons('save,cancel'),			// Buttons
				array('singleton'=>'true'),					// Options
				_('Add Keyword')						// Menu item label
			),
			new MenuLeafNode_Go('/tag/',_('Keywords'))
		))
	)),
	new Menu(_('Website'),array(
		new MenuLeafNode_Go('/website/templates',_('Templates')),
		new MenuLeafNode_Go('/website/menus',_('Menus')),
		new Menu(_('Settings'),array(
			new MenuLeafNode_Go('/website/picture-sizes',_('Picture Sizes')),
			new MenuLeafNode_Go('/website/plugins',_('Plugins'))
		))
	)),
	new Menu(_('Account'),array(
		new MenuLeafNode_Popup(
			'/account/details',	 					// URL
			_('Account Details'),	 					// Dialog Title
			new MenuLeafNode_Popup_Buttons('save,cancel'),			// Buttons
			array('singleton'=>'true')					// Options
			,_('Account Details')						// Menu item label
		),
		new MenuLeafNode_Popup(
			'/account/settings',	 					// URL
			_('Account Settings'),	 					// Dialog Title
			new MenuLeafNode_Popup_Buttons('save,cancel'),			// Buttons
			array('singleton'=>'true')					// Options
			,_('Account Settings')						// Menu item label
		),
		new MenuLeafNode_Popup(
			'/account/password',	 					// URL
			_('Change Password'), 						// Dialog Title
			new MenuLeafNode_Popup_Buttons('save,cancel'),			// Buttons
			array('singleton'=>'true')					// Options
			,_('Change Password')						// Menu item label
		),
		new MenuLeafNode_Go('/account/logout',_('Logout'))
	))
));
var_dump( $nav2->json_export());
$nav=array(
	'&lambda;' => array(
		"/" => _('Dashboard'),
		"popup:"._('Quick Media Upload').":singleton:/content/quick-upload" => _('Quick Upload'),
		"popup:"._('Quick Post').":save,cancel,singleton:/blog/quick-post" => _('Quick Post'),
		"targetblank:/website/" => _('Visit My Website'),
		"jsaction:esqoo_ui.flick_light_switch();" => ('Flick Light Switch')
	),
	_('Content') => array(
		"submenu:"._('Media') => array(
			"/content/upload" => _('Upload Media'),
			"/album/" => _('Albums'),
			"/picture/" => _('Pictures'),
			"/video/" => _('Video'),
			"/audio/" => _('Audio')
		),
		"submenu:"._('Posts and Pages') => array(
			"/blog/post" => _('Write Blog Post'),
			"/page/add" => _('Add Page'),
			"/blog/" => _('Blog Posts'),
			"/page/" => _('Pages'),
			"/link/" => _('Links')
		),
		"submenu:"._('Keyword Tags') => array(
			"popup:"._('Add Keyword').":save,cancel,singleton:/tag/add" => _('Add Keyword'),
			"/tag/" => _('Keywords')
		)
	),
	_('Website') => array(
		"/website/templates" => _('Templates'),
		"/website/menus" => _('Menus'),
		"submenu:"._('Settings') => array(
			"/website/picture-sizes" => _('Picture Sizes'),
			"/website/plugins" => _('Plugins')
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
	if (strpos($url,"jsaction:")===0) { 
		list($jsaction,$action)=explode(':',$url,2);
		$content .= "<li class=\"ui-menubar-default ui-corner-all\"><a href=\"#\" onclick=\"$action return false;\">$item</a></li>";
		
	} else if (strpos($url,'submenu:')===0) { 
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
		$content .= "<li class=\"ui-menubar-default ui-corner-all\"><a href=\"$url\" onclick=\"return esqoo_ui.browse_to_new_url($(this).attr('href'));\">$item</a></li>";
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
<li class="ui-menubar-heading-default ui-corner-all esqoo-ui-menubar-fullscreen-button esqoo-qtip" data-qtip-content="Click to toggle fullscreen mode" data-qtip-position-my="top right" data-qtip-position-at="bottom left" onclick="esqoo_ui.toggle_fullscreen(); return false;" style="float: right;"><span class="ui-icon ui-icon-maximize"></span></li>
</ul>
</nav>
