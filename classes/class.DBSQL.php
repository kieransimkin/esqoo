<?php
require_once(dirname(__FILE__).'/dbsq/dbsq.class.php');
class DBSQL extends DBSQ { 
	private $_visiblefields=array();
	function delete() { 
		$this->DeleteDate=date("c");
		$this->save();
	}
	function save($nomodifydate=false) { 
		if (!$nomodifydate) { 
			$this->ModifyDate=date("c");
		}
		parent::save();
	}
	function set_visible_api_fields($fields=array()) { 
		if (!is_array($fields)) { 
			throw new DBSQ_Exception('Fields array is empty');
			return;
		}
		$this->_visiblefields=$fields;
		return $this;
	}
	function getFilteredDataArray() { 
		$ldata=$this->getDataArray();
		$ret=array();
		foreach ($this->_visiblefields as $field) { 
			if ($field==='id') { 
				$varname=ucfirst(self::_getTableName());
				if (strpos($varname,'_')) { 
					$bits=explode('_',$varname);
					foreach ($bits as &$bit) { 
						$bit=ucfirst($bit);
					}
					$varname=implode('',$bits);
				}
				$ret[$varname.'ID']=$ldata['id'];
			} else if (substr($field,-3)=='_id') { 
				$key=substr($field,0,strlen($field)-3);
				$bits=explode('__',$key,2);
				$prefix='';
				$varname=ucfirst($key);
				if (count($bits)>1) { 
					$prefix=ucfirst($bits[0]);
					$varname=ucfirst($bits[1]);
				}
				if (strpos($varname,'_')) { 
					$bits=explode('_',$varname);
					foreach ($bits as &$bit) { 
						$bit=ucfirst($bit);
					}
					$varname=implode('',$bits);
				}
				$ret[$prefix.$varname.'ID']=$ldata[$field];
			} else { 
				$ret[$field]=$ldata[$field];
			}
		}
		return $ret;
	}
	static function getSqlSuffix($input) {
		foreach(array("Page","ResultsPerPage","SortField","SortOrder") as $a) {
			$input[$a] = mysql_real_escape_string($input[$a]);
		}

		if (!$input['SortField']) $input['SortField']='id';
		if (!$input['SortOrder']) $input['SortOrder'] = 'desc';

		$sort = "ORDER BY ".$input['SortField']." ".$input['SortOrder'];

		if (!$input['Page']) $input['Page'] = 1;
		if (!$input['ResultsPerPage']) $input['ResultsPerPage'] = 15;

		$start = (($input['Page']-1) * $input['ResultsPerPage']);

		$limit = "LIMIT $start, ".$input['ResultsPerPage'];
		return "$sort $limit";
	}

}
