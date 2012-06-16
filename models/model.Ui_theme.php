<?php
class SQ_Ui_theme extends SQ_Class_DBSQ { 
	static function get_menu($daystate) { 
		if ($daystate=='Nighttime') { 
			$res=SQ_Ui_theme::getAll('ui_state=\'night\' or ui_state=\'both\'');
		} else if ($daystate=='Daytime') { 

			$res=SQ_Ui_theme::getAll('ui_state=\'day\' or ui_state=\'both\'');
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
