<?
$upper = ucfirst($controller);
$filterName = $upper.'SessionFilter';
$ctrlName = $upper.'Controller';
echo <<<EOT
<?

class $ctrlName extends PageController{
	public \$layout = false;
	public \$pageTitle = Constant::APP_NAME;
	public \$filterConfig = [
		[
			'$filterName','-',['index','login']
		],
		[
			'$filterName','+',['index'],'page'
		]
	];

	public function actionIndex(){
		\$uid = Yii::app()->user->id;
		\$user = User::model()->findByPk(\$uid);
		\$adminCheck = UserUtil::checkAdmin(\$user);
		if(\$adminCheck['ret'] == 'FAIL'){
			throw new CHttpException(404,\$adminCheck['data']);
		}
		\$this->layout = '//layouts/$controller';
		\$this->render('index',[
			'user'=>\$user,
		]);
	}

	public function actionLogin(\$from = ''){
		\$this->layout = '//layouts/$controller';
		if(!\$from){
			\$from = Yii::app()->createUrl('/$controller/index');
		}
		\$this->render("login",[
			'from'=>\$from
		]);
	}
}
EOT;
