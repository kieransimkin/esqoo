<?php
require_once(dirname(__FILE__).'/dbsq/dbsq.class.php');
class DBSQL extends DBSQ { 
	private $_visiblefields=array();
	private $_computedfields=array();
	public static $_cachedfields=array();
	function __get($key) { 
		if (isset($this->$key)) { 
			return $this->rawGet($key);
		}
		if (substr($key,-3,3)=='_id') { 
			$newkey=substr($key,0,strlen($key)-3);
			if (isset($this->$newkey)) {
				return $this->rawGet($newkey);
			}
		}
		if (in_array($key,static::$_cachedfields) && !isset($this->$key)) {
			$ret=Cache::getKey('DB-'.strtolower(self::_strip_SQ(get_called_class())).'-'.strtolower($this->_get_lazyLoadIndexName()),$this->_get_lazyLoadId().'-'.strtolower($key));
			if ($ret instanceof CacheError) {
				Site::connect();
				Cache::setKey('DB-'.strtolower(self::_strip_SQ(get_called_class())).'-'.strtolower($this->_get_lazyLoadIndexName()),$this->_get_lazyLoadId().'-'.strtolower($key),parent::__get($key));
				return parent::__get($key);
			} else { 
				$this->$key=$ret;
				return $ret;
			}

		}
		if (substr($key,-3,3)!='_id') { 
			$newkey=$key.'_id';
			if (in_array($newkey,static::$_cachedfields)) { 
				$ret=Cache::getKey('DB-'.strtolower(self::_strip_SQ(get_called_class())).'-'.strtolower($this->_get_lazyLoadIndexName()),$this->_get_lazyLoadId().'-'.strtolower($newkey));
				if ($ret instanceof CacheError) {
					Site::connect();
					Cache::setKey('DB-'.strtolower(self::_strip_SQ(get_called_class())).'-'.strtolower($this->_get_lazyLoadIndexName()),$this->_get_lazyLoadId().'-'.strtolower($newkey),parent::__get($key));
					return parent::__get($key);
				} else { 
					$this->$newkey=$ret;
					if (is_null($ret)) { 
						return null;
					} else { 
						return parent::__get($key);
					}
				}
			}
		}
		return parent::__get($key);
	}
	private static function _strip_SQ($var) { 
		if (substr($var,0,3)=='SQ_') { 
			return substr($var,3);
		} else { 
			return $var;
		}
	}
	function delete() { 
		$this->DeleteDate=date("c");
		$this->save();
	}
	function save($nomodifydate=false) { 
		if (!$nomodifydate) { 
			$this->ModifyDate=date("c");
		}
		$ret=parent::save();
		if (count(static::$_cachedfields)>0) { 
			foreach (static::$_cachedfields as $cachedfield) { 
				try { 
					$data=$this->$cachedfield;
				} catch (DBSQ_Exception $e) { 
					$data=null;
				}
				if (is_object($data) && substr($cachedfield,-3,3)=='_id') { 
					Cache::setKey('DB-'.strtolower(self::_strip_SQ(get_called_class())).'-'.strtolower($this->_get_lazyLoadIndexName()), $this->_get_lazyLoadId().'-'.strtolower($cachedfield),$data->id);
				} else { 
					Cache::setKey('DB-'.strtolower(self::_strip_SQ(get_called_class())).'-'.strtolower($this->_get_lazyLoadIndexName()), $this->_get_lazyLoadId().'-'.strtolower($cachedfield),$data);
				}
			}
		}
		return $ret;
	}
	function set_visible_api_fields($fields=array()) { 
		if (!is_array($fields)) { 
			throw new DBSQ_Exception('Fields array is empty');
			return;
		}
		$this->_visiblefields=$fields;
		return $this;
	}
	static function set_all_visible_api_fields($dblist,$fields=array()) { 
		foreach ($dblist as &$db) { 
			$db->set_visible_api_fields($fields);
		}
	}
	function add_computed_field($name,$func) { 
		$this->_computedfields[$name]=$func;
		return $this;
	}
	static function add_all_computed_field($dblist,$name,$func) { 
		foreach ($dblist as &$db) { 
			$db->add_computed_field($name,$func);
		}
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
		foreach ($this->_computedfields as $name => $func) { 
			if (method_exists($this,$func)) { 
				$ret[$name]=$this->$func();
			}
		}	
		return $ret;
	}
	static function getSqlSuffix($input) {
		foreach(array("Page","ResultsPerPage","SortField","SortOrder") as $a) {
			$input[$a] = mysql_real_escape_string($input[$a]);
		}

		if (!$input['SortField'] || $input['SortField']=='undefined') $input['SortField']='id';
		if (!$input['SortOrder'] || $input['SortOrder']=='undefined') $input['SortOrder'] = 'desc';

		$sort = "ORDER BY ".$input['SortField']." ".$input['SortOrder'];

		if (!$input['Page']) $input['Page'] = 1;
		if (!$input['ResultsPerPage']) $input['ResultsPerPage'] = 15;

		$start = (($input['Page']-1) * $input['ResultsPerPage']);

		$limit = "LIMIT $start, ".$input['ResultsPerPage'];
		return "$sort $limit";
	}

}
