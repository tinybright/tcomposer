<?
$upper = ucfirst($controller);
$filterName = $upper.'SessionFilter';
$ctrlName = $upper.'apiController';
$sessionUtil = $upper.'SessionUtil';
$signService = $upper.'SignService';
$menuList = strtoupper($controller.'_MENU_LIST');
echo <<<EOT
<?

class $ctrlName extends BaseapiController{

	public \$filterConfig = [
		[
			'$filterName','-',['login','captcha']
		],
		[
			'$filterName','+',['demoimport'],'page'
		]
	];

	public function actionGetMenuList(){
		\$uid = Yii::app()->user->id;
		\$user = User::model()->findByPk(\$uid);

		\$adminCheck = UserUtil::checkAdmin(\$user);
		if(\$adminCheck['ret'] == 'FAIL'){
			echo json_encode(\$adminCheck);
		}
		\$roleList = [
			1=>['数据','用户'],
		];
		\$menuTitleList = [];
		foreach (\$roleList as \$role => \$titleList) {
			\$menuTitleList = array_merge(\$menuTitleList,\$titleList);
		}
		\$menuList = [];
		if(\$menuTitleList){
			foreach (MenuUtil::\$$menuList as \$menu) {
				if(in_array(\$menu[0],\$menuTitleList)){
					\$menuList[] = \$menu;
				}
			}
		}
		echo json_encode(['ret'=>'SUCCESS','data'=>\$menuList]);
	}

	public function actionLogin(){
		\$checkRet = CheckUtil::checkParams(\$_POST,[
			'mobile','pwd'
		],[
			'账号不能为空','密码不能为空'
		]);
		/*\$checkRet = CheckUtil::checkParams(\$_POST,[
			'mobile','pwd','imgcaptcha'
		],[
			'手机号不能为空','密码不能为空','验证码不能为空'
		]);*/
		if(\$checkRet['ret'] == 'FAIL'){
			echo json_encode(\$checkRet);
			return;
		}
		/*\$imgcaptcha = \$_POST['imgcaptcha'];
		\$ca = \$this->createAction('captcha');
		if(!\$ca->validate(\$imgcaptcha,false)){
			echo json_encode(['ret'=>'FAIL','data'=>'图形验证码错误']);
			return ;
		}
		\$ca->getVerifyCode(true);*/
		\$result = $signService::login(\$_POST['mobile'],\$_POST['pwd']);
		if(\$result['ret'] == 'SUCCESS'){
			\$aid = \$result['data']['id'];
			\$account = KCache::getUser(\$aid);
			if(!\$account || \$account->deleted){
				echo json_encode(['ret'=>'FAIL','data'=>'账号不存在']);
				return;
			}
			if(\$account->status == Constant::\$USER_STATUS['ban']){
				echo json_encode(['ret'=>'FAIL','data'=>'用户被禁用']);
				return;
			}

			\$apiRet = BaseSessionUtil::setSession(\$account->id);
			if(\$apiRet['ret'] == 'FAIL'){
				echo json_encode(\$apiRet);
				return;
			}
			BaseSessionUtil::setCookie('$controller',\$account->id,\$apiRet['data']);
			unset(\$result['data']);
		}
		echo json_encode(\$result);
	}

	public function actionGetActionLogList(){
		header('Content-Type: application/json');
		if (!\$_GET['page']){
			echo json_encode(['ret'=>'FAIL', 'data'=>'未得到页数']);
			return ;
		}
		if (!\$_GET['pagesize']){
			echo json_encode(['ret'=>'FAIL', 'data'=>'未设置每页条目数']);
			return ;
		}
		\$page = \$_GET['page'];
		\$pagesize = \$_GET['pagesize'];
		\$result = MyUtil::getRandOperation();
		\$sum = count(\$result);
		\$sumpage = 0 == \$sum%\$pagesize ? \$sum/\$pagesize : floor(\$sum/\$pagesize+1);
		\$result = ['sum'=>\$sum, 'sumpage'=>\$sumpage, 'list'=>array_slice(\$result,(\$page-1)*\$pagesize,\$pagesize)];
		echo json_encode(['ret'=>'SUCCESS', 'data'=>\$result]);
	}
}
EOT;
