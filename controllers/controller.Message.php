<?php
class MessageController extends LockedController { 
	public function getAPI($arg='',$input=array()) { 
		$messages=Message::getAll('user_id=? and CompleteDate is null',array($this->user->id));
		DBSQL::set_all_visible_api_fields($messages,$this->get_message_fields());
		return array('Messages'=>$messages,'MessageCount'=>count($messages));
	}
	public function seenAPI($arg='',$input=array()) { 
		if (strlen(@$input['MessageID'])<1 || $input['MessageID']!=(int)$input['MessageID']) { 
			$this->api_error(1,_("MessageID must be specified and must be an integer"));
		}
		$message=null;
		try { 
			$message=Message::get($input['MessageID']);
			if ($message->user_id!=$this->user->id) { 
				$this->api_error(2,_('MessageID not found'));
				$message=null;
			}
		} catch (DBSQ_Exception $e) { 
			$this->api_error(2,_('MessageID not found'));
			$mesasge=null;
		}
		if ($this->api_validation_success()) { 
			$message->seen();
			$message->set_visible_api_fields($this->get_message_fields());
		}
		return $message;
	}
	private function get_message_fields() { 
		return array('id','CreateDate','Severity','Message','CompleteDate');
	}
} 
