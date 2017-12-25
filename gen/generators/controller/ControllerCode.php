<?php

class ControllerCode extends CCodeModel
{
	public $controller='';
	public $app='wukong';
	public $page='test';
	public $fields = [];

	public function rules()
	{
		return array_merge(parent::rules(), array(
			array('controller', 'filter', 'filter'=>'trim'),
			array('controller', 'required'),
		));
	}

	public function attributeLabels()
	{
		return array_merge(parent::attributeLabels(), array(
			'controller'=>'Controller ID',
		));
	}

	public function requiredTemplates()
	{
		return array(
			'components/tpl_session_filter.php'
		);
	}

	public function successMessage()
	{
		$link=CHtml::link('去生成页面', Yii::app()->createUrl('/gen/custom/index'), []);
		return "controller已生产$link.";
	}

	public function prepare()
	{
		$this->files=array();
		$templatePath=$this->templatePath;


		$this->files=array();
		$templatePath=$this->templatePath;

		$ctrlPath = $this->getProtectedPath('controllers');
		$ctrlFilePath = $this->getProtectedPath('controllers',ucfirst($this->controller).'Controller.php');
		$apiCtrlFilePath = $this->getProtectedPath('controllers',ucfirst($this->controller).'apiController.php');

		$ctrlTplPath = $templatePath.DIRECTORY_SEPARATOR.'controllers';

		//ctrl
		$this->files[]=new CCodeFile(
			$ctrlPath.DIRECTORY_SEPARATOR.ucfirst($this->controller).'Controller.php',
			$this->render($ctrlTplPath.DIRECTORY_SEPARATOR.'CtrlController.php',[
				'controller' => $this->controller
			])
		);

		$this->files[]=new CCodeFile(
			$ctrlPath.DIRECTORY_SEPARATOR.ucfirst($this->controller).'apiController.php',
			$this->render($ctrlTplPath.DIRECTORY_SEPARATOR.'CtrlapiController.php',[
				'controller' => $this->controller
			])
		);
		$jsTemplatePath = $templatePath.DIRECTORY_SEPARATOR.'js';
		$jsFiles=CFileHelper::findFiles($templatePath.DIRECTORY_SEPARATOR.'js',array(
			'exclude'=>array(
				'.svn',
				'.gitignore'
			),
		));
		$params = [
			'app'=>$this->app,
			'controller'=>$this->controller,
			'page'=>$this->page,
		];
		//js
		$targetJsPath = $this->getJsPath($this->controller);
		foreach ($jsFiles as $file){
			if(CFileHelper::getExtension($file)==='php')
				$content=$this->render($file,$params);
			elseif(basename($file)==='.gitkeep')  // an empty directory
			{
				$file=dirname($file);
				$content=null;
			}
			else
				$content=file_get_contents($file);
			$this->files[]=new CCodeFile(
				$targetJsPath.str_replace('php','js',substr($file,strlen($jsTemplatePath))),
				$content
			);
		}
		//view
		$this->files[]=new CCodeFile(
			$this->getViewsPath($this->controller,'login.php'),
			$this->render($templatePath.'/views/ctrl/login.php',$params)
		);
		$this->files[]=new CCodeFile(
			PathUtil::getViewPath([$this->controller,'index.php']),
			$this->render($templatePath.'/views/ctrl/tpl_index.php',$params)
		);

		$this->files[]=new CCodeFile(
			$this->getViewsPath('layouts',$this->controller.'.php'),
			$this->render($templatePath.'/views/layouts/ctrl.php',$params)
		);

		//MenuUtil
        $menuPath = PathUtil::getPath(['proteced','components','custom','MenuUtil.php']);
		$this->files[]=new CCodeFile(
            $menuPath,
			$this->render($templatePath.'/components/tpl_menu_util.php',$params)
		);
		//SessionFilter
		$this->files[]=new CCodeFile(
			$this->getProtectedPath( 'filters',ucfirst($this->controller).'SessionFilter.php'),
			$this->render($templatePath.'/components/tpl_session_filter.php',$params)
		);
		//extensions/modal/views/constant-ctrl.php
		$this->files[]=new CCodeFile(
			PathUtil::getCompoPath([ucfirst($this->controller).'SignService.php']),
			$this->render($templatePath.'/components/tpl_sign_service.php',$params)
		);


		/*$modulePath=$this->modulePath;
		$moduleTemplateFile=$templatePath.DIRECTORY_SEPARATOR.'module.php';

		$this->files[]=new CCodeFile(
			$modulePath.'/'.$this->moduleClass.'.php',
			$this->render($moduleTemplateFile)
		);

		$files=CFileHelper::findFiles($templatePath,array(
			'exclude'=>array(
				'.svn',
				'.gitignore'
			),
		));

		foreach($files as $file)
		{
			if($file!==$moduleTemplateFile)
			{
				if(CFileHelper::getExtension($file)==='php')
					$content=$this->render($file);
				elseif(basename($file)==='.gitkeep')  // an empty directory
				{
					$file=dirname($file);
					$content=null;
				}
				else
					$content=file_get_contents($file);
				$this->files[]=new CCodeFile(
					$modulePath.substr($file,strlen($templatePath)),
					$content
				);
			}
		}


		$action = $this->page;
			$this->files[]=new CCodeFile(
				$this->getViewFile($action.'-home'),
				$this->render($templatePath.'/tpl_home.php',[
					'app'=>$this->app,
					'controller'=>$this->controller,
					'page'=>$this->page,
					'fields'=>$this->fields,
				])
			);
			$this->files[]=new CCodeFile(
				$this->getViewFile($action.'-list'),
				$this->render($templatePath.'/tpl_list.php',[
					'app'=>$this->app,
					'controller'=>$this->controller,
					'page'=>$this->page,
					'fields'=>$this->fields,
				])
			);
			$this->files[]=new CCodeFile(
				$this->getViewFile($action.'-add'),
				$this->render($templatePath.'/tpl_add.php',[
					'app'=>$this->app,
					'controller'=>$this->controller,
					'page'=>$this->page,
					'fields'=>$this->fields,
				])
			);
			$this->files[]=new CCodeFile(
				$this->getViewFile($action.'-edit'),
				$this->render($templatePath.'/tpl_edit.php',[
					'app'=>$this->app,
					'controller'=>$this->controller,
					'page'=>$this->page,
					'fields'=>$this->fields,
				])
			);
			$this->files[]=new CCodeFile(
				$this->getJsFile($action.''),
				$this->render($templatePath.'/tpl_js.php',[
					'app'=>$this->app,
					'controller'=>$this->controller,
					'page'=>$this->page,
					'fields'=>$this->fields,
				])
			);

			$this->files[]=new CCodeFile(
				$this->getComponentsPath('MenuUtil.php'),
				$this->render($templatePath.'/tpl_menu_util.php',[
					'app'=>$this->app,
					'controller'=>$this->controller,
					'page'=>$this->page,
					'fields'=>$this->fields,
				])
			);

			$this->files[]=new CCodeFile(
				$this->getJsPath(@$this->controller,'app','config.js'),
				$this->render($templatePath.'/tpl_config.php',[
					'app'=>$this->app,
					'controller'=>$this->controller,
					'page'=>$this->page,
					'fields'=>$this->fields,
				])
			);

			$this->files[]=new CCodeFile(
				$this->getViewsPath('layouts',@$this->controller.'.php'),
				$this->render($templatePath.'/tpl_layout.php',[
					'app'=>$this->app,
					'controller'=>$this->controller,
					'page'=>$this->page,
					'fields'=>$this->fields,
					'path'=>'js'.'/'.$this->controller.'/'.'controller'.'/'.$this->page.'.js'
				])
			);*/
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
		return $this->getPath('js',$firstLvl,$secondLvl,$thirdLvl);
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


}