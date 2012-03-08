<?php
class MessageController extends LockedController { 
	public function getAPI($arg='',$input=array()) { 
		$messages=Message::getAll('user_id=? and CompleteDate is null',array($this->user->id));
		DBSQL::set_all_visible_api_fields($messages,$this->get_message_fields());
		Message::seen($this->user->id);
		return array('Messages'=>$messages,'MessageCount'=>count($messages));
	}
	private function get_message_fields() { 
		return array('id','CreateDate','Severity','Message');
	}
} 
