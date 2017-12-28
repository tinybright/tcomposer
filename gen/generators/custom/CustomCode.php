<?php

class CustomCode extends CCodeModel
{
	public $controller='';
	public $app='muum';
	public $page='';
	public $fields = [];
	public $dbmodel = '';
	public $btn_import = '';
	public $list_mode = 'normal';

	public function rules()
	{
		return array_merge(parent::rules(), array(
			array('controller, page', 'filter', 'filter'=>'trim'),
			array('controller, page,app,fields,btn_import,list_mode', 'required'),
			/*array('controller', 'match', 'pattern'=>'/^\w+[\w+\\/]*$/', 'message'=>'{attribute} should only contain word characters and slashes.'),
			array('page', 'match', 'pattern'=>'/^\w+[\w\s,]*$/', 'message'=>'{attribute} should only contain word characters, spaces and commas.'),*/
			array('app', 'sticky'),
		));
	}

	public function init()
	{
		parent::init();

		$this->controller = !$this->controller ? 'mgr' : $this->controller;

	}


	public function attributeLabels()
	{
		return array_merge(parent::attributeLabels(), array(
			'app'=>'App Class',
			'controller'=>'Controller ID',
			'page'=>'Page Name',
			'fields'=>'Fields',
			'btn_import'=>'btn_import',
			'dbmodel'=>'参考模型'
		));
	}

	public function requiredTemplates()
	{
		return array(
			'tpl_list.php',
			'controller.php',
			'view.php',

			'tpl_constant.php',
			'tpl_constant_status.php',
		);
	}

	public function successMessage()
	{
		$link=CHtml::link('去看一看', Yii::app()->createUrl('/'.$this->controller.'/index#!/data/'.$this->page), array('target'=>'_blank'));
		return "页面已生成$link.";
	}

	public function prepare()
	{
		//todo 大小写存在问题
		$this->files=array();
		$templatePath=$this->templatePath;

		$action = $this->page;

		$errors = [];
		if($this->fields){
			$statusInfos = [];
			foreach ($this->fields as $index => $field){
				$error = [];
				if('' == @$field['zh'] || '' == @$field['en']){
					$error[] = '第'.($index+1).'行中英文字段为必填';
				} else if (is_numeric(@$field['zh'][0]) || is_numeric(@$field['en'][0])){
					$error[] = '第'.($index+1).'行中文字段为不能以数字开头';
				}

				if ('select' == @$field['type']){
					//没有就添加
					if(!CheckUtil::isValueExistNoEmpty($field,'statusName')){
						//选项可以为空
						$field['statusName'] = strtoupper($this->page).'_'.strtoupper(@$field['en']).'';
					}else{
						if(is_numeric(@$field['statusName'][0])){
							$error[] = '第'.($index+1).'行下拉框名称不能以数字开头';
						}
					}
					if(!CheckUtil::isValueExistNoEmpty($field,'options')){
						/*$error[] = '第'.($index+1).'行下拉框名称及选项不得为空';*/
						//选项可以为空
					}
				}

				$errors = array_merge($errors,$error);
				if($error) continue;

				if(@$field['type'] == 'select'){
					$statusList = [];

					$pairList = Utilities::string2List($field['options'],',');
					if($pairList){
						foreach ($pairList as $pair){
							$pairAttrs = Utilities::string2List($pair,':');
							$statusList[@$pairAttrs[0]] = @$pairAttrs[1];
						}
					}
					$statusName = strtoupper(@$field['statusName']);

					if(!isset(MyStatus::$$statusName)){
						$statusInfos[] = [
							'statusName'=>@$field['statusName'],
							'statusList'=>$statusList
						];
					}
				}
			}
			if($error){
				$this->updateRet = $this->renderError($error);

			}else{
				$this->updateRet = '';
			}
			/*if($statusInfos){
				$this->files[]=new CCodeFile(
					$this->getComponentsPath('Constant.php'),
					$this->render($templatePath.'/tpl_constant.php',[
						'statusInfos'=>$statusInfos
					])
				);
			}*/
			if($statusInfos){
				$this->files[]=new CCodeFile(
					PathUtil::getCompoPath(['custom','MyStatus.php']),
					$this->render($templatePath.'/tpl_constant_gen.php',[
						'statusInfos'=>$statusInfos
					])
				);
			}
		}
//		$linePage = Utilities::toUnderScore($action);
//			$this->files[]=new CCodeFile(
//				$this->getViewFile($linePage.'-home'),
//				$this->render($templatePath.'/tpl_home.php',[
//					'app'=>$this->app,
//					'controller'=>$this->controller,
//					'page'=>$this->page,
//					'fields'=>$this->fields,
//				])E
//			);
//			$this->files[]=new CCodeFile(
//				$this->getViewFile($linePage.'-list'),
//				$this->render($templatePath.'/tpl_list.php',[
//					'app'=>$this->app,
//					'controller'=>$this->controller,
//					'page'=>$this->page,
//					'fields'=>$this->fields,
//					'btn_import'=>$this->btn_import
//				])
//			);
//			$this->files[]=new CCodeFile(
//				$this->getViewFile($linePage.'-add'),
//				$this->render($templatePath.'/tpl_add.php',[
//					'app'=>$this->app,
//					'controller'=>$this->controller,
//					'page'=>$this->page,
//					'fields'=>$this->fields,
//				])
//			);
//			$this->files[]=new CCodeFile(
//				$this->getViewFile($linePage.'-edit'),
//				$this->render($templatePath.'/tpl_edit.php',[
//					'app'=>$this->app,
//					'controller'=>$this->controller,
//					'page'=>$this->page,
//					'fields'=>$this->fields,
//				])
//			);
			//转到 js-new 使用模板testdata.js;
//			$this->files[]=new CCodeFile(
//				$this->getJsFile(strtolower($action).''),
//				$this->render($templatePath.'/tpl_jsv1.php',[
//					'app'=>$this->app,
//					'controller'=>$this->controller,
//					'page'=>$this->page,
//					'fields'=>$this->fields,
//					'btn_import'=>$this->btn_import
//				])
//			);
        $params = [
            'app'=>$this->app,
            'controller'=>$this->controller,
            'page'=>$this->page,
        ];
        $targetJsPath = PathUtil::getJsPath([$this->controller,'controller']);

        $key = 'event';

        $jsTemplatePath = $templatePath.DIRECTORY_SEPARATOR.($key == 'event'?'js':'cjs');
        $jsTemplateFiles = CFileHelper::findFiles($jsTemplatePath,array(
            'exclude'=>array(
                '.svn',
                '.gitignore'
            ),
        ));
        $sample = strtoupper($key[0]);
        foreach ($jsTemplateFiles as $file){
            if(Utilities::contain($file,'tpl_')){
                continue;
            }
            if($this->list_mode != 'new'){
                if(Utilities::contain($file,'event-list_new')){
                    continue;
                }
            }else{
                if(Utilities::contain($file,'event-list')&&!Utilities::contain($file,'event-list_new')){
                    continue;
                }
            }
            //大小写敏感 原始使用Event;
            $isJs = Utilities::contain($file,$key.'.js');

            $newFilePath = str_replace($key,"tpl_".$key,$file);
            $newFilePath = str_replace('.js','.php',$newFilePath);
            if(!file_exists($newFilePath)){
                $content = file_get_contents($file);
                if(!Utilities::contain($content,'EOF')){
                    $finalContent = '';
                    $finalContent .= "<?\n".
                        "\$ctrl = \$page".";\n".
                        "\$samplectrl = ".'strtoupper($page[0])'.";\n".
                        "\$upperctrl = ".'strtoupper($page)'.";\n".
                        "\$lowerctrl = ".'lcfirst($page)'.";\n".
                        "\$oldctrl = ".'($page)'.";\n".
                        "\$linectrl = ".'Utilities::toUnderScore($page)'.";\n".
                        "\$camelctrl = ".'ucfirst($page)'.";\n".
                        'echo <<<EOF'."\n";
                    $content = str_replace("\$","\\\$",$content);
                    $content = str_replace(ucfirst($key),'{$camelctrl}',$content);
                    $content = str_replace(lcfirst($key).'-','{$linectrl}-',$content);
                    $content = str_replace(lcfirst($key),'{$lowerctrl}',$content);
                    $content = str_replace(strtoupper($key),'{$upperctrl}',$content);
                    $content = str_replace($sample.'LC','{$samplectrl}LC',$content);
                    $content = str_replace($sample.'AC','{$samplectrl}AC',$content);
                    $content = str_replace($sample.'EC','{$samplectrl}EC',$content);
                    $content = str_replace($sample.'HC','{$samplectrl}HC',$content);
                    $finalContent .= $content;
                    $finalContent .= "\n";
                    $finalContent .= 'EOF;';
                    $finalContent .= "\n";
                    file_put_contents($newFilePath,$finalContent);
                }
            }



            if($isJs){

                $relativePath = substr($file,strlen($jsTemplatePath));
                $relativePath = str_replace('event',strtolower($this->page),$relativePath);
                $this->files[]=new CCodeFile(
                    $targetJsPath.$relativePath,
                    $this->render($newFilePath,$params)
                );
            }else{
                $relativePath = substr($file,strlen($jsTemplatePath));
                $relativePath = str_replace($key,Utilities::toUnderScore($this->page),$relativePath);

                $relativePath = str_replace('_new.php','.php',$relativePath);
                $targetViewPath = PathUtil::getPath(['protected','views',$this->controller]);
                $this->files[]=new CCodeFile(
                    $targetViewPath.$relativePath,
                    $this->render($newFilePath,$params)
                );

            }

        }
            //todo skip
//			$this->files[]=new CCodeFile(
//				$this->getComponentsPath('MenuUtil.php'),
//				$this->render($templatePath.'/tpl_menu_util.php',[
//					'app'=>$this->app,
//					'controller'=>$this->controller,
//					'page'=>$this->page,
//					'fields'=>$this->fields,
//				])
//			);


        //已自动

//			$this->files[]=new CCodeFile(
//				$this->getJsPath(@$this->controller,'app','config.js'),
//				$this->render($templatePath.'/tpl_config.php',[
//					'app'=>$this->app,
//					'controller'=>$this->controller,
//					'page'=>$this->page,
//					'fields'=>$this->fields,
//				])
//			);


            //已自动
//			$this->files[]=new CCodeFile(
//				$this->getViewsPath('layouts',@$this->controller.'.php'),
//				$this->render($templatePath.'/tpl_layout.php',[
//					'app'=>$this->app,
//					'controller'=>$this->controller,
//					'page'=>$this->page,
//					'fields'=>$this->fields,
//					'path'=>'js'.'/'.$this->controller.'/'.'controller'.'/'.$this->page.'.js'
//				])
//			);



		$compoTemplatePath = $templatePath.DIRECTORY_SEPARATOR.'components';
		$compoFiles = CFileHelper::findFiles($compoTemplatePath,array(
			'exclude'=>array(
				'.svn',
				'.gitignore'
			),
		));


		//service
        if(true){
            return;
        }
		$targetBasePath = PathUtil::getCompoPath();
		foreach ($compoFiles as $file){
			if(Utilities::contain($file,'Holder')){
				continue;
			}
			$newFilePath = str_replace('Efficiency',"Holder",$file);
			if(!file_exists($newFilePath)){
				$content = file_get_contents($file);
				if(!Utilities::contain($content,'EOF')){
					$finalContent = '';
					$finalContent .= "<?\n".
						"\$ctrl = \$page".";\n".
						"\$upperctrl = ".'strtoupper($page)'.";\n".
						"\$lowerctrl = ".'strtolower($page)'.";\n".
						"\$camelctrl = ".'ucfirst($page)'.";\n".
						'echo <<<EOF'."\n";
					$content = str_replace("\$","\\\$",$content);
					$content = str_replace('Efficiency','{$camelctrl}',$content);
					$content = str_replace('Holder','{$camelctrl}',$content);
					$content = str_replace('efficiency','{$ctrl}',$content);
					$content = str_replace('EFFICIENCY','{$upperctrl}',$content);

					$finalContent .= $content;
					$finalContent .= "\n";
					$finalContent .= 'EOF;';
					$finalContent .= "\n";
					file_put_contents($newFilePath,$finalContent);
				}
			}

			$relativePath = substr($file,strlen($compoTemplatePath));
			$relativePath = str_replace('Efficiency',ucfirst($this->page),$relativePath);

			$this->files[]=new CCodeFile(
				$targetBasePath.$relativePath,
				$this->render($newFilePath,$params)
			);
		}
		
		//js-new
		$targetJsPath = PathUtil::getJsPath([$this->controller,'controller']);
		$jsTemplatePath = $templatePath.DIRECTORY_SEPARATOR.'js';
		$jsTemplateFiles = CFileHelper::findFiles($jsTemplatePath,array(
			'exclude'=>array(
				'.svn',
				'.gitignore'
			),
		));
		foreach ($jsTemplateFiles as $file){
			if(Utilities::contain($file,'tpl_')){
				continue;
			}
			$newFilePath = str_replace('event',"tpl_event",$file);
			$newFilePath = str_replace('.js','.php',$newFilePath);
			if(!file_exists($newFilePath)){
				$content = file_get_contents($file);
				if(!Utilities::contain($content,'EOF')){
					$finalContent = '';
					$finalContent .= "<?\n".
						"\$ctrl = \$page".";\n".
						"\$samplectrl = ".'strtoupper($page[0])'.";\n".
						"\$upperctrl = ".'strtoupper($page)'.";\n".
						"\$lowerctrl = ".'strtolower($page)'.";\n".
						"\$camelctrl = ".'ucfirst($page)'.";\n".
						'echo <<<EOF'."\n";
					$content = str_replace("\$","\\\$",$content);
					$content = str_replace('Testdata','{$camelctrl}',$content);
					$content = str_replace('testdata','{$lowerctrl}',$content);
					$content = str_replace('TESTDATA','{$upperctrl}',$content);
					$content = str_replace('TLC','{$samplectrl}LC',$content);
					$content = str_replace('TAC','{$samplectrl}AC',$content);
					$content = str_replace('TEC','{$samplectrl}EC',$content);
					$content = str_replace('THC','{$samplectrl}HC',$content);
					$finalContent .= $content;
					$finalContent .= "\n";
					$finalContent .= 'EOF;';
					$finalContent .= "\n";
					file_put_contents($newFilePath,$finalContent);
				}
			}

			$relativePath = substr($file,strlen($jsTemplatePath));
			$relativePath = str_replace('testdata',strtolower($this->page),$relativePath);

			$this->files[]=new CCodeFile(
				$targetJsPath.$relativePath,
				$this->render($newFilePath,$params)
			);
		}

		//todo skip
		//api
//		$targetApiFile = PathUtil::getControllerPath(ucfirst($this->controller).'apiController.php');
//		$tplApiFile = $templatePath.DIRECTORY_SEPARATOR.'tpl_api.php';
//		$this->files[]=new CCodeFile(
//			$targetApiFile,
//			$this->render($tplApiFile,$params)
//		);

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
		return $this->getProtectedPath('components',$firstLvl,$secondLvl);
	}
	public $updateRet = [];
	public function save()
	{
		$ret =  parent::save();
		if($ret || true){
		}
		return $ret;
	}

	public static function renderTpl($path,$params = []){
		$exist = file_exists($path);
		if(!$exist){
			return '';
		}
		$content = file_get_contents($path);
		if($params){
			foreach ($params as $key=>$param){
				$content = str_replace($key,$param,$content);
			}
		}
		return $content;
	}

}