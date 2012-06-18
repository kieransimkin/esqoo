<?php
$nav2=new SQ_Class_Menu(array(
	new SQ_Class_Menu('&lambda;',array(
		new SQ_Class_MenuLeafNode_Go('/',_('Dashboard')),
		new SQ_Class_MenuLeafNode_Popup(
			'/content/quick-upload', 					// URL
			_('Quick Media Upload'), 					// Dialog Title
			new SQ_Class_MenuLeafNode_Popup_Buttons(''),				// Buttons
			array('singleton'=>'true')					// Options
			,_('Quick Upload')						// Menu item label
		),
		new SQ_Class_MenuLeafNode_Popup(
			'/blog/quick-post',						// URL
			_('Quick Post'),						// Dialog Title	
			new SQ_Class_MenuLeafNode_Popup_Buttons('save,cancel'),			// Buttons
			array('singleton'=>'true'),					// Options
			_('Quick Post')							// Menu item label
		),
		new SQ_Class_MenuLeafNode_TargetBlank('/website/',_('Visit My Website')),
		new SQ_Class_MenuLeafNode_JSAction('esqoo_ui.flick_light_switch();',_('Flick Light Switch'))
	)),
	new SQ_Class_Menu(_('Content'),array(
		new SQ_Class_Menu(_('Media'), array(
			new SQ_Class_MenuLeafNode_Go('/content/upload',_('Upload Media')),
			new SQ_Class_MenuLeafNode_Go('/album/',_('Albums')),
			new SQ_Class_MenuLeafNode_Go('/picture/',_('Pictures')),
			new SQ_Class_MenuLeafNode_Go('/video/',_('Video')),
			new SQ_Class_MenuLeafNode_Go('/audio/',_('Audio'))
		)),
		new SQ_Class_Menu(_('Posts and Pages'),array(
			new SQ_Class_MenuLeafNode_Go('/blog/post',_('Write Blog Post')),
			new SQ_Class_MenuLeafNode_Go('/page/add',_('Add Page')),
			new SQ_Class_MenuLeafNode_Go('/blog/',_('Blog Posts')),
			new SQ_Class_MenuLeafNode_Go('/page/',_('Pages')),
			new SQ_Class_MenuLeafNode_Go('/link/',_('Links'))
		)),
		new SQ_Class_Menu(_('Keyword Tags'),array(
			new SQ_Class_MenuLeafNode_Popup(
				'/tag/add',							// URL
				_('Add Keyword'),						// Dialog Title	
				new SQ_Class_MenuLeafNode_Popup_Buttons('save,cancel'),			// Buttons
				array('singleton'=>'true'),					// Options
				_('Add Keyword')						// Menu item label
			),
			new SQ_Class_MenuLeafNode_Go('/tag/',_('Keywords'))
		))
	)),
	new SQ_Class_Menu(_('Website'),array(
		new SQ_Class_MenuLeafNode_Go('/website/templates',_('Templates')),
		new SQ_Class_MenuLeafNode_Go('/website/menus',_('Menus')),
		new SQ_Class_Menu(_('Settings'),array(
			new SQ_Class_MenuLeafNode_Go('/website/picture-sizes',_('Picture Sizes')),
			new SQ_Class_MenuLeafNode_Go('/website/plugins',_('Plugins'))
		))
	)),
	new SQ_Class_Menu(_('Account'),array(
		new SQ_Class_MenuLeafNode_Popup(
			'/account/details',	 					// URL
			_('Account Details'),	 					// Dialog Title
			new SQ_Class_MenuLeafNode_Popup_Buttons('save,cancel'),			// Buttons
			array('singleton'=>'true')					// Options
			,_('Account Details')						// Menu item label
		),
		new SQ_Class_MenuLeafNode_Popup(
			'/account/settings',	 					// URL
			_('Account Settings'),	 					// Dialog Title
			new SQ_Class_MenuLeafNode_Popup_Buttons('save,cancel'),			// Buttons
			array('singleton'=>'true')					// Options
			,_('Account Settings')						// Menu item label
		),
		new SQ_Class_MenuLeafNode_Popup(
			'/account/password',	 					// URL
			_('Change Password'), 						// Dialog Title
			new SQ_Class_MenuLeafNode_Popup_Buttons('save,cancel'),			// Buttons
			array('singleton'=>'true')					// Options
			,_('Change Password')						// Menu item label
		),
		new SQ_Class_MenuLeafNode_Go('/account/logout',_('Logout'))
	))
));
$nav=$nav2->export();
if (!is_null($this->custommenu)) { 
	print "got here";
}
?>
<nav>
<ul id="nav-one" class="nav ui-tabs-nav ui-helper-clearfix ui-widget-header ui-corner-bottom inner-padded">
<?php
function render_nav_element($item,$user) {
	$content='';
	if ($item['leaftype']=='jsaction') { 
		$action=$item['action'];
		$title=$item['title'];
		$content .= "<li class=\"ui-menubar-default ui-corner-all\"><a href=\"#\" onclick=\"$action return false;\">$title</a></li>";
		
	} else if ($item['leaftype']=='popup') {
		$title=$item['title'];
		$popuptitle=$item['popuptitle'];
		$buttonlist=$item['buttons'];
		$realurl=$item['url'];
		list($controller, $action) = explode("/", substr($realurl, 1));
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
		if (array_key_exists('singleton',$item['properties']) && $item['properties']['singleton']!==false) { 
			$buttonstring.="singleton: true, ";
		}
		$content .= "<li class=\"ui-menubar-default ui-corner-all\"><a href=\"$realurl\" onclick=\"esqoo_ui.make_dialog({ $buttonstring title: '$popuptitle' },'$realurl'); return false;\">$title...</a></li>";

	} else if ($item['leaftype']=='targetblank') {
		$realurl=$item['url'];
		$title=$item['title'];
		list($controller, $action) = explode("/", substr($realurl, 1));
		$content .= "<li class=\"ui-menubar-default ui-corner-all\"><a href=\"$realurl\" target=\"_blank\">$title... </a><div class=\"nav-float-right\"><span class=\"ui-icon ui-icon-extlink\"></span></div></li>";

	} else if ($item['leaftype']=='go') {
		$title=$item['title'];
		$url=$item['url'];
		$content .= "<li class=\"ui-menubar-default ui-corner-all\"><a href=\"$url\" onclick=\"return esqoo_ui.browse_to_new_url($(this).attr('href'));\">$title</a></li>";
	} else { 
		$title=$item['title'];
		$content .= "<li class=\"ui-menubar-default ui-corner-all\"><a href=\"#\" onclick=\"return false;\">$title <div class=\"nav-float-right\">&raquo;</div></a>";
		$content .= "<ul class=\"ui-state-default ui-corner-all\">";
		$count=0;
		foreach($item['menuitems'] as $titem) {
			$thiscontent=render_nav_element($titem,$user);	
			$content.=$thiscontent;
			if (strlen($thiscontent)>0) { 
				++$count;
			}
		}
		$content .= "</ul></li>";
		if ($count==0) { 
			return '';
		}
	}
	return $content;
}
$first=' lambda ui-widget-header ui-corner-bottom';
foreach($nav['menuitems'] as $val) {
    	$content = '';
	foreach($val['menuitems'] as $item) {
		$content.=render_nav_element($item,$this->user);	
	}
	$heading=$val['title'];
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
