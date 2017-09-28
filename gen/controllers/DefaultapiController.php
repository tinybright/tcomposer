<?php
require_once dirname(dirname(__FILE__)).'/components/GenTool.php';
class DefaultapiController extends CController
{
	public $layout='/layouts/column1';

	public function getPageTitle()
	{
		if($this->action->id==='index')
			return 'Gii: a Web-based code generator for Yii';
		else
			return 'Gii - '.ucfirst($this->action->id).' Generator';
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
		$this->layout = "/layouts/main";
		$this->render('menu-index');
	}

	public function actionGetMenuList(){
		$uid = Yii::app()->user->id;
		$roleList = [
			1=>['数据','用户'],
		];
		$menuTitleList = [];
		foreach ($roleList as $role => $titleList) {
			$menuTitleList = array_merge($menuTitleList,$titleList);
		}
		$menuList = [];
		if($menuTitleList){
			foreach (GenTool::$MENU_MENU_LIST as $menu) {
				$menuList[] = $menu;
			}
		}
		echo json_encode(['ret'=>'SUCCESS','data'=>$menuList]);
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

	public function actionGetChildList($parentid = -1,$page = 1,$pagesize = -1){
		$childRet = MenuService::getChildList($parentid,$page,$pagesize);

		if($childRet['list']){
			$list = [];
			foreach ($childRet['list'] as $org){
				$org = KCache::getMenu($org->id);
				$orgAttrs = BaseUtil::obj2Array($org);
				$list[] = $orgAttrs;
			}
			$childRet['list'] = $list;
		};
		echo json_encode(['ret'=>'SUCCESS','data'=>$childRet]);
	}

	public function actionDeleteMenu(){
		$uid = Yii::app()->user->id;
		$deleteRet = MenuService::deleteMenu($uid,CheckUtil::getValue($_POST,'nodeid'),CheckUtil::getValue($_POST,'force'));
		echo json_encode($deleteRet);
	}

	public function actionAddMenu(){
		$uid = Yii::app()->user->id;
		$addRet = MenuService::createMenu($uid,CheckUtil::getValue($_POST,'node'));
		if($addRet['ret'] == 'SUCCESS'){
			$menu = KCache::getMenu($addRet['data']);
			$menuAttrs = MMenuUtil::menu2Array($menu,'detail');
			$addRet['data'] = $menuAttrs;
		}
		echo json_encode($addRet);
	}

	public function actionEditMenu(){
		$uid = Yii::app()->user->id;
		$node = CheckUtil::getValue($_POST,'node');
		$editRet = MenuService::editMenu($uid,CheckUtil::getValue($node,'menuid'),$node);
		echo json_encode($editRet);
	}

	public function actionMoveMenu(){
		$uid = Yii::app()->user->id;
		$node = $_POST;
		$editRet = MenuService::moveMenu($uid,CheckUtil::getValue($node,'id'),CheckUtil::getValue($node,'targetid'));
		echo json_encode($editRet);
	}

	public function actionAddFeature(){
		$uid = Yii::app()->user->id;
		$params = CheckUtil::getValue($_POST,'feature');
		$addRet = FeatureService::addFeature($uid,$params);
		echo json_encode($addRet);
	}

	public function actionEditFeature(){
		$uid = Yii::app()->user->id;
		$params = CheckUtil::getValue($_POST,'feature');
		$addRet = FeatureService::editFeature($uid,$params);
		echo json_encode($addRet);
	}

	public function actionGetFeatureList($page = 1,$pagesize = Constant::DEFAULT_PAGESIZE){
		$uid = Yii::app()->user->id;
		$featureRet = FeatureService::getFeatureList($uid,$_GET,$page,$pagesize);
		if($featureRet['list']){
			$list = [];
			foreach ($featureRet['list'] as $feature){
				$featureAttrs = FeatureUtil::feature2Array($feature,'list');
				$list[] = $featureAttrs;
			}
			$featureRet['list'] = $list;
		}
		echo json_encode(['ret'=>'SUCCESS','data'=>$featureRet]);
	}

	public function actionGetFeature($id = ''){
		$feature = KCache::getFeature($id);
		if(!CheckUtil::isExist($feature)){
			echo json_encode(['ret'=>'FAIL','data'=>'功能点不存在']);
		}
		$featureAttrs = FeatureUtil::feature2Array($feature,'detail');
		echo json_encode(['ret'=>'SUCCESS','data'=>$featureAttrs]);
	}

	public function actionSyncMenu(){
		$uid = Yii::app()->user->id;
		$syncRet = MenuService::exportMenu($uid,CheckUtil::getValue($_POST,'menuid'));
		echo json_encode(['ret'=>'SUCCESS','data'=>'']);
	}

	public function actionImportMenu(){
		$name = CheckUtil::getValue($_POST,'name');
		$displayName = CheckUtil::getValue($_POST,'displayname');
		$menus = [];
		eval('$menus='.CheckUtil::getValue($_POST,'menus').';');
		$importRet = MenuService::importMenu($name,$displayName,$menus);
		echo json_encode($importRet);
	}
}