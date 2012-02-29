<?php
class Controller { 
	public $api_errors=array();
	function __construct($controller,$action) { 
		$this->action=$action;
		$this->controller=$controller;
	}
	public function setView($view) {
		$this->view = new View($view);
		$this->view->controller = $this;
		$this->view->user = @$this->user;
	}

	public function render() {
		$this->view->user = $this->user;
		$this->view->jsManager = new JSManager;
		$this->view->jsFiles = $this->jsFiles;
		$this->view->cssManager = new CSSManager;
		$this->view->render();
	}

	private $jsFiles = Array();

	protected function jsFile() {
		foreach(func_get_args() as $file)
			$this->jsFiles[] = $file;
	}
	function api_response($res,$responseformat) { 
		switch ($responseformat) { 
			case 'xml':
				require_once('XML/Serializer.php');
				$options = array(
					"indent"     => "    ",
					"linebreak"  => "\n",
					"addDecl"    => true,
					"defaultTagName"  => "Item",
					"encoding" => "UTF-8",
					"rootName" => "APIResponse"
				);
				$serializer=new XML_Serializer($options);
				$serializer->serialize($res);
				echo $serializer->getSerializedData();
				die;
					
			case 'json':
			default:
				echo json_encode($res);
				die;
		}
	}
	function autoloadJS() {
		$action = $this->action;
		if(strpos($action,'..')!== false || strpos($action,'/')!==false) {
			die("Die hacker!");
		}
		// Autoload JS files for this controller and action 
		if(file_exists(realpath(dirname(__FILE__).'/../js').'/'.$this->controller.'/'.$action.".js") && @$_GET['request_type']!='json') {
			$this->view->head .= "\t<script src=\"/js/{$this->controller}/{$action}.js\"></script>\n";
		} elseif(file_exists(realpath(dirname(__FILE__).'/../js').'/'.$action.".js") && @$_GET['request_type']!='json') {
			$this->view->head .= "\t<script src=\"/js/{$action}.js\"></script>\n";
		}
	}
	function autoloadCSS() {
		$action = $this->action;
		if(strpos($action,'..')!== false || strpos($action,'/')!==false) {
			die("Die hacker!");
		}
		// Autoload JS files for this controller and action 
		if(file_exists(realpath(dirname(__FILE__).'/../css').'/'.$this->controller.'/'.$action.".css") && @$_GET['request_type']!='json') {
			$this->view->head .= "\t<link rel=\"stylesheet\" href=\"/css/{$this->controller}/{$action}.css\" />\n";
		} elseif(file_exists(realpath(dirname(__FILE__).'/../css').'/'.$action.".css") && @$_GET['request_type']!='json') {
			$this->view->head .= "\t<link rel=\"stylesheet\" href=\"/css/{$action}.css\" />\n";
		}
	}
	function api_error($id,$str) { 
		$this->api_errors[$id]=_($str);
	}
	function api_form_validation_error($form) { 
		$this->api_error($form->getId(),_('Form validation failure'));
	}
	function showMessage($string) { 
		// XXX TODO
	}
	function api_error_array() {
		$ret=array();
		foreach ($this->api_errors as $id => $str) { 
			$ret[]=array('Code'=>$id,'String'=>$str);
		}
		return $ret;
	}
	function api_validation_success() { 
		if (count($this->api_errors)>0) { 
			return false;
		} else { 
			return true;
		}
	}
	function __toString() { 
		return get_called_class();
	}
	function redirect($url) { 
		header("Location: $url");
		die;
	}
	function formFail($form,$width=null,$minwidth=null,$height=null,$minheight=null) { 
		echo $form;
		return $this->formFailAjaxResponse($width,$minwidth,$height,$minheight);
	}
	function formSuccess() { 
		return array('rettype'=>'success');
	}
	function tabbedDialogFail($dialogs=array(),$width=null,$minwidth=null,$height=null,$minheight=null,$defaulttab=null) { 
		if (!is_array($dialogs)) { 
			print $dialogs;
		} else { 
			?>
			<section class="esqoo-dialog-tabs">
				<nav>
					<ul>
			<?php	
			foreach ($dialogs as $title => $form) { 
?>						<li><a href="#<?=$form->getId();?>"><?=$title;?></a></li>
<?php
			}
			?>
					</ul>
				</nav>
			<?php
			foreach ($dialogs as $title => $form) { 
?>
				<div id="<?=$form->getId();?>"><?=$form;?></div>
<?php
			}?>
			</section><?php
		}
		return $this->formFailAjaxResponse($width,$minwidth,$height,$minheight,$defaulttab);
	}
	function formFailAjaxResponse($width=null,$minwidth=null,$height=null,$minheight=null,$defaulttab=null) { 
		$ret=array();
		$ret['rettype']='failure';
		if (!is_null($width)) { 
			$ret['width']=$width;
		} 
		if (!is_null($minwidth)) { 
			$ret['minwidth']=$minwidth;
		}
		if (!is_null($height)) { 
			$ret['height']=$height;
		} 
		if (!is_null($minheight)) { 
			$ret['minheight']=$minheight;
		}
		if (!is_null($defaulttab)) { 
			$ret['defaulttab']=$defaulttab;
		}
		return $ret;
	}
	function formTargetBlank($url) { 
		$ret=array();
		$ret['rettype']='targetblank';
		$ret['url']=$url;
		return $ret;
	}
	function formIFrame($url,$width=null,$minwidth=null,$height=null,$minheight=null) { 
		echo '<a href="'.$url.'" target="_blank">Open in new window</a><br /><iframe class="form-iframe" src="'.$url.'"></iframe>';
		return $this->formFailAjaxResponse($width,$minwidth,$height,$minheight);
	}
}
