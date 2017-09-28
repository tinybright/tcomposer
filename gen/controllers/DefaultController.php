<?php
require_once dirname(dirname(__FILE__)).'/components/GenTool.php';
class DefaultController extends CController
{
	public $layout='/layouts/column1';

	public function getPageTitle()
	{
		return "Hello";
	}

	public function actionIndex()
	{
		if(true){
			$this->redirect(Yii::app()->createUrl('/gen/app/index'));
			return;
		}
		$this->render('index');
	}

	public function actionMenuIndex(){
		BaseSessionUtil::setCookie('gen/default',1,2);
		$this->layout = "/layouts/main-menu";
		$ret = MenuService::initRootMenu();
		$this->render('menu-index');
	}

	

	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=Yii::createComponent('gen.models.LoginForm');

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login()){
				$this->redirect(Yii::app()->createUrl('/gen/app/index'));
				return;
			}

		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout(false);
		$this->redirect(array('index'));
	}

	public function actionCreateDb(){
		$dbName = CheckUtil::getValue($_POST,'dbname');
		$ret = GenUtil::createDb($dbName);
		echo json_encode($ret);
	}

	public function actionCreateAdmin(){
		$ret = GenUtil::createAdmin($_POST);
		echo json_encode($ret);
	}

	public function actionLog($clearlog = 0){
		
		$logPath = PathUtil::getPath(['protected','runtime','application.log']);
		if($clearlog == 3){
			file_put_contents($logPath,'');
			return;
		}
		$content = file_get_contents($logPath);
		echo '<pre>';
		echo $content;
	}

	public function missingAction($actionID){
		//aBC->a-b-c
		$viewName = Utilities::toUnderScore($actionID);
		if(($viewFile=$this->getViewFile($viewName))!==false){
			$this->layout = false;
			$this->render($viewName);
		}else{
			parent::missingAction($actionID);
		}
	}
}