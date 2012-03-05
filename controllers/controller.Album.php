<?php
class AlbumController extends LockedController { 
	/**********************************************
	 *  ╻ ╻┏━┓┏━╸┏━┓   ╻┏┓╻╺┳╸┏━╸┏━┓┏━╸┏━┓┏━╸┏━╸  *
	 *  ┃ ┃┗━┓┣╸ ┣┳┛   ┃┃┗┫ ┃ ┣╸ ┣┳┛┣╸ ┣━┫┃  ┣╸   *
	 *  ┗━┛┗━┛┗━╸╹┗╸   ╹╹ ╹ ╹ ┗━╸╹┗╸╹  ╹ ╹┗━╸┗━╸  *
	 **********************************************/
	function indexUI($arg='',$input=array()) { 

	}
	/*****************************************
	 *  ┏━┓┏━┓╻   ┏━╸╻ ╻┏┓╻┏━╸╺┳╸╻┏━┓┏┓╻┏━┓  *
	 *  ┣━┫┣━┛┃   ┣╸ ┃ ┃┃┗┫┃   ┃ ┃┃ ┃┃┗┫┗━┓  *
	 *  ╹ ╹╹  ╹   ╹  ┗━┛╹ ╹┗━╸ ╹ ╹┗━┛╹ ╹┗━┛  *
	 *****************************************/
	function listAPI($arg='',$input=array()) { 
		$suffix=DBSQL::getSqlSuffix($input);
		if ($input['SearchField']=='Name' && strlen($input['SearchQuery'])>0) { 
			$albums=Album::getAll('user_id=? and DeleteDate is null and Name like ?',array($this->user->id,'%'.$input['SearchQuery'].'%'),null,$suffix);
		} else { 
			$albums=Album::getAll('user_id=? and DeleteDate is null',array($this->user->id),null,$suffix);
		}
		DBSQL::set_all_visible_api_fields($albums,array('Name','id'));
		$numrows=DBSQL::foundRows();
		return $this->flexigridResponse($albums,$input['Page'],$numrows);
	}
}
