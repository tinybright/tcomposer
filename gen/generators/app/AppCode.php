<?php

class AppCode extends CCodeModel
{

	public $direct = true;
	public $controller = 'site';
	public $dbname = '';
	public $appname = '';
	public $salt = '';
	public $current_dbname = '';
	public $current_appname = '';
	public $current_salt = '';

	public function init()
	{
		parent::init();
		$config = $this->getProtectedPath('config','custom.php');
		require ($config);

		$this->dbname = !$this->dbname?@$dbName:$this->dbname;
		$this->appname = !$this->appname ? @$appName:$this->appname;
		$this->salt = !$this->salt ? @$salt : $this->salt;
		$this->current_dbname = CheckUtil::getValue($_REQUEST,'dbname',$this->dbname);
		$this->current_appname = @$appName;
		$this->current_salt = @$salt;
	}


	public function rules()
	{
		return array_merge(parent::rules(), array(
			array('appname, dbname ,salt', 'filter', 'filter'=>'trim'),
			array('appname, dbname ,salt',  'required'),
		));
	}

	public function successMessage()
	{
		$link=CHtml::link('去添加controller', Yii::app()->createUrl('/gen/controller/index'), []);
		$link2 = CHtml::link('去生成模型', Yii::app()->createUrl('/gen/modelv1/index'), []);
		return "$link<br>$link2";
	}

	public function attributeLabels()
	{
		return array_merge(parent::attributeLabels(), array(
			'appname'=>'应用名',
			'dbname'=>'数据库名',
			'salt'=>'盐值',
			'current_appname'=>'应用名',
			'current_dbname'=>'数据库名',
			'current_salt'=>'盐值',
		));
	}

	public function requiredTemplates()
	{
		return array(
			'tpl_config_custom.php',
		);
	}

	public function prepare()
	{
		$this->files=array();
		$templatePath=$this->templatePath;

			$this->files[]=new CCodeFile(
				$this->getProtectedPath('config','custom.php'),
				$this->render($templatePath.'/tpl_config_custom.php',[
					'controller'=>$this->controller,
					'appname'=>$this->appname,
					'dbname'=>$this->dbname,
					'salt'=>$this->salt,
				])
			);
		$filterNames = GenUtil::getFilterNames();
		$this->files[]=new CCodeFile(
			PathUtil::getJsPath(['base','filter','gen.js']),
			$this->render($templatePath.'/tpl_filter.php',[
				'constants'=>$filterNames
			])
		);
		$this->files[]=new CCodeFile(
			PathUtil::getPath(['Constants.java']),
			$this->render($templatePath.'/tpl_java.php',[
				'constants'=>$filterNames
			])
		);
		$this->files[]=new CCodeFile(
			PathUtil::getProtectedPath(['extensions','modal','views','gen.php']),
			$this->render($templatePath.'/tpl_gen.php',[
				'constants'=>$filterNames
			])
		);
	}

	/*public function getActionIDs()
	{
		$page=preg_split('/[\s,]+/',$this->page,-1,PREG_SPLIT_NO_EMPTY);
		$page=array_unique($page);
		sort($page);
		return $page;
	}*/

	public function getControllerClass()
	{
		if(($pos=strrpos($this->controller,'/'))!==false)
			return ucfirst(substr($this->controller,$pos+1)).'Controller';
		else
			return ucfirst($this->controller).'Controller';
	}

	public function getModule()
	{
		if(($pos=strpos($this->controller,'/'))!==false)
		{
			$id=substr($this->controller,0,$pos);
			if(($module=Yii::app()->getModule($id))!==null)
				return $module;
		}
		return Yii::app();
	}

	public function getControllerID()
	{
		return $this->controller;
		if($this->getModule()!==Yii::app())
			$id=substr($this->controller,strpos($this->controller,'/')+1);
		else
			$id=$this->controller;
		if(($pos=strrpos($id,'/'))!==false)
			$id[$pos+1]=strtolower($id[$pos+1]);
		else
			$id[0]=strtolower($id[0]);
		return $id;
	}

	public function getUniqueControllerID()
	{
		$id=$this->controller;
		if(($pos=strrpos($id,'/'))!==false)
			$id[$pos+1]=strtolower($id[$pos+1]);
		else
			$id[0]=strtolower($id[0]);
		return $id;
	}

	public function getControllerFile()
	{
		$module=$this->getModule();
		$id=$this->getControllerID();
		if(($pos=strrpos($id,'/'))!==false)
			$id[$pos+1]=strtoupper($id[$pos+1]);
		else
			$id[0]=strtoupper($id[0]);
		return $module->getControllerPath().'/'.$id.'Controller.php';
	}

	public function getViewFile($action)
	{
		$module=$this->getModule();
		return $module->getViewPath().'/'.$this->getControllerID().'/'.$action.'.php';
	}
	public function getJsFile($action)
	{
		$module=$this->getModule();
		$viewPath = $module->getViewPath();
		$protectedPath = dirname($viewPath);
		$basePath = dirname($protectedPath);
		return $basePath.'/'.'js'.'/'.$this->getControllerID().'/'.'controller'.'/'.$action.'.js';
	}

	public function getBasePath(){
		$module=$this->getModule();
		$viewPath = $module->getViewPath();
		$protectedPath = dirname($viewPath);
		$basePath = dirname($protectedPath);
		return $basePath;
	}

	public function getPath($firstLvl = '',$secondLvl = '',$thirdLvl = '',$fourthLvl = ''){
		$basePath = $this->getBasePath();
		if($firstLvl){
			$basePath .= DIRECTORY_SEPARATOR.$firstLvl;
		}
		if($secondLvl){
			$basePath .= DIRECTORY_SEPARATOR.$secondLvl;
		}
		if($thirdLvl){
			$basePath .= DIRECTORY_SEPARATOR.$thirdLvl;
		}
		if($fourthLvl){
			$basePath .= DIRECTORY_SEPARATOR.$fourthLvl;
		}
		return $basePath;
	}
	public function getJsPath($firstLvl = '',$secondLvl = '',$thirdLvl = ''){
		return $this->getPath('js',$firstLvl,$secondLvl).DIRECTORY_SEPARATOR.$thirdLvl;
	}
	public function getProtectedPath($firstLvl = '',$secondLvl = '',$thirdLvl = ''){
		return $this->getPath('protected',$firstLvl,$secondLvl,$thirdLvl);
	}
	public function getViewsPath($firstLvl = '',$secondLvl = '',$thirdLvl = ''){
		return $this->getProtectedPath('views',$firstLvl,$secondLvl,$thirdLvl);
	}

	public function getComponentsPath($firstLvl = '',$secondLvl = '',$thirdLvl = ''){
		return $this->getProtectedPath('components',$firstLvl);
	}
	public $updateRet = [];
	public function save()
	{
		$ret =  parent::save();
		if($ret || true){
		}
		return $ret;
	}

	public function saveV1()
	{
		$this->prepare();
		$ret =  parent::save();
		if($ret || true){
		}
		return $ret;
	}
}