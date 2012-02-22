<?php
class DashboardController extends LockedController { 
	function indexUI() { 
		var_dump($this->user->firstname);
	}
}
