<?php
class Ui_theme extends DBSQL { 
	static function get_menu($daystate) { 
		if ($daystate=='Nighttime') { 
			$res=UI_theme::getAll('ui_state=\'night\' or ui_state=\'both\'');
		} else if ($daystate=='Daytime') { 

			$res=UI_theme::getAll('ui_state=\'day\' or ui_state=\'both\'');
		} else { 
			throw new Exception('argument must be either night time or day time');
			return;
		}
		$ret=array();
		if (is_array($res)) { 
			foreach ($res as $item) { 
				$ret[$item->id]=$item->Tag;
			}
		}
		return $ret;
	}
}
