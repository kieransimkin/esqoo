<?php
$nav=array(
	'&lambda;' => array(
		"/" => _('Dashboard'),
		"/file/upload" => _('Upload Media'),
		"/blog/post" => _('Write Blog Post'),
		"/website" => _('Visit My Website'),
		"/lightswitch/flick" => ('Flick Light Switch')
	),
	_('File') => array(
		"/file/upload" => _('Upload')
	),
	_('Account') => array(
		"popup:"._('Account Details').":save,cancel:/account/details" => _('Account Details'),
		"/account/logout" => _('Logout')
	),
	_('Help') => array(
		"/help/about" => _('About Esqoo'),
		"submenu:"._('Contents') => array(
			"/help/item1" => _('Item 1'),
			"/help/item2" => _('Item 2')
		)
	)
);?>
<nav>
<ul id="nav-one" class="nav ui-tabs-nav ui-helper-clearfix ui-widget-header ui-corner-bottom inner-padded">
<?php
function render_nav_element($url,$item,$user) {
	$content='';
	if (strpos($url,'submenu:')===0) { 
		list($submenu,$title)=explode(':',$url,2);
		$content .= "<li class=\"ui-menubar-default\"><a href=\"#\" onclick=\"return false;\">$title <div class=\"nav-float-right\">&raquo;</div></a>";
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

		$content .= "<li class=\"ui-menubar-default\"><a href=\"$realurl\" onclick=\"makeDialog({ helpid: 'navbar-dialog-$action', title: '$title', $buttonstring open_event_handler: ".$controller."_".str_replace('-','_',$action)."_open_event_handler, post_event_handler: ".$controller."_".str_replace('-','_',$action)."_post_event_handler, close: function() { reloadMessages(); $('#search_results').flexReload(); $(this).dialog('destroy').remove();}},'$realurl')(); return false;\">$item...</a></li>";

	} else if (strpos($url,"targetblank:")===0) {
		list($tgtblank,$realurl)=explode(':',$url,2);
		list($controller, $action) = explode("/", substr($realurl, 1));
		$content .= "<li class=\"ui-menubar-default\"><div style=\"z-index: 6000; position: relative; left: -8px; top: 2px; width: 20px; float: right;\"><div style=\"position: absolute; top: 0px; left: 0px;\"><span class=\"ui-icon ui-icon-extlink\"></span></div></div><a href=\"$realurl\" target=\"_blank\">$item... </a></li>";

	} else {
		list($controller, $action) = explode("/", substr($url, 1));
		$unopened=0;
		$content .= "<li class=\"ui-menubar-default\"><a href=\"$url\" onclick=\"create_page_loading_overlay();\">$item</a></li>";
	}
	return $content;
}
$first=' lambda ui-widget-header ui-corner-all';
foreach($nav as $heading => $menu) {
    	$content = '';
	foreach($menu as $url => $item) {
		$content.=render_nav_element($url,$item,$this->user);	
	}
	if($content) {
		?>
		<li class="ui-menubar-heading-default<?=$first;?>">
			<a href="#<?=str_replace(' ','-',strtolower($heading))?>" onclick="return false"><?=$heading?></a>
			<ul class="ui-state-default">
			<?=$content?>
			</ul>
		</li>
		<?
	}
	if (strpos($first,'lambda')!==FALSE) { 
		$first=' second';
	} else {
		$first='';
	}
}

?>
</ul>
</nav>
