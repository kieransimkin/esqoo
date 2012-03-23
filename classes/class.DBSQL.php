<?php
require_once(dirname(__FILE__).'/dbsq/dbsq.class.php');
class DBSQL extends DBSQ { 
	private $_visiblefields=array();
	public static $_cachedfields=array();
	function __get($key) { 
		if (in_array($key,static::$_cachedfields) && !isset($this->$key)) {
			$ret=Cache::getKey('DB-'.strtolower(get_called_class()).'-'.strtolower($this->_get_lazyLoadIndexName()),$this->_get_lazyLoadId().'-'.strtolower($key));
			if ($ret instanceof CacheError) {
				Site::loadAndConnect();
				return parent::__get($key);
			} else { 
				$this->$key=$ret;
				return $ret;
			}

		}
		if (substr($key,-3,3)!='_id') { 
			$newkey=$key.'_id';
			if (in_array($newkey,static::$_cachedfields)) { 
				$ret=Cache::getKey('DB-'.strtolower(get_called_class()).'-'.strtolower($this->_get_lazyLoadIndexName()),$this->_get_lazyLoadId().'-'.strtolower($newkey));
				if ($ret instanceof CacheError) {
					Site::loadAndConnect();
					return parent::__get($key);
				} else { 
					print "got here".$newkey.'-'.$ret;
					$this->$newkey=$ret;
					return parent::__get($key);
				}
			}
		}
		return parent::__get($key);
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
//					$data=parent::__get($cachedfield);
					$data='f';
					Cache::setKey('DB-'.strtolower(get_called_class()).'-'.strtolower($this->_get_lazyLoadIndexName()), $this->_get_lazyLoadId().'-'.strtolower($cachedfield),'f');
					//Cache::setKey('DB-'.strtolower(get_called_class()).'-'.strtolower($this->_get_lazyLoadIndexName()), $this->_get_lazyLoadId().'-'.strtolower($cachedfield),parent::__get($cachedfield));
				} catch (DBSQ_Exception $e) { 
					var_dump($e);
					//noop
				}
			}
		}
		print '---'.$this->id.'---';
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
