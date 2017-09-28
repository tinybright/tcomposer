<?php
$cs=Yii::app()->clientScript;
$cs->coreScriptPosition=CClientScript::POS_HEAD;
$cs->scriptMap=array();
$baseUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('gen.assets'), false, -1, YII_DEBUG);
$cs->registerCoreScript('jquery');
$cs->registerScriptFile($baseUrl.'/js/tooltip.js');
$cs->registerScriptFile($baseUrl.'/js/fancybox/jquery.fancybox-1.3.1.pack.js');
$cs->registerCssFile($baseUrl.'/js/fancybox/jquery.fancybox-1.3.1.css');
$mainBaseUrl = Yii::app()->request->hostInfo.Yii::app()->baseUrl;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?=$baseUrl?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?=$baseUrl?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?=$baseUrl?>/css/ie.css" media="screen, projection" />
	<![endif]-->
	<link rel="stylesheet" type="text/css" href="<?=$baseUrl?>/css/jquery-ui.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

	<script type="text/javascript" src="<?=$baseUrl?>/js/main.js"></script>


	<!-- Bootstrap -->
	<link href="<?=$mainBaseUrl?>/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<script src="<?=$mainBaseUrl?>/js/bootstrap.min.js" type="text/javascript"></script>
	<!-- Normal -->
	<link href="<?=$mainBaseUrl?>/css/common.css" rel="stylesheet" type="text/css" />
	<link href="<?=$mainBaseUrl?>/css/main.css" rel="stylesheet" type="text/css" />
	<link href="<?=$mainBaseUrl?>/css/mgr.common.css" rel="stylesheet" type="text/css" />
	<link href="<?=$baseUrl?>/css/menu.main.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="<?=$baseUrl?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?=$baseUrl?>/css/custom.css" />
	<link href="<?=$mainBaseUrl?>/css/ui-bootstrap-custom-2.5.0-csp.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?$this->widget('ext.modal.UploadFormV1',[
	'params'=>[
		'formId'=>'form-upload-image',
		'type'=>'image'
	]
]);?>
<?$this->widget('ext.modal.UploadFormV1',[
	'params'=>[
		'formId'=>'form-upload-file',
		'type'=>'file'
	]
]);?>
<?$this->widget('ext.modal.UploadFormV1',[
	'params'=>[
		'formId'=>'form-upload-app',
		'type'=>'file'
	]
]);?>
<script type="text/javascript">
	var _HOST = "<?=Yii::app()->request->hostInfo.Yii::app()->baseUrl?>";
	MSG_SERVER_ERROR = "服务器错误";
	_APP_NAME = "<?=Yii::app()->name?>";
	_CTRL_NAME = "gen/default";
	_MOUDLE_NAME = _APP_NAME+"App";
	_ID_POLL_INTERVAL = 500;
</script>


<?php echo $content; ?>

<script src="<?=$mainBaseUrl?>/js/jquery.common.js" type="text/javascript"></script>
<!--custom_start-->
<!-- build:js js/base.controller.js -->
<script src="<?=$mainBaseUrl?>/js/base/app/app.js" type="text/javascript"></script>
<script src="<?=$mainBaseUrl?>/js/base/service/common.js" type="text/javascript"></script>
<script src="<?=$mainBaseUrl?>/js/base/component/common.js" type="text/javascript"></script>
<script src="<?=$mainBaseUrl?>/js/base/filter/common.js" type="text/javascript"></script>
<!-- endbuild -->
<!-- build:js js/mgr.controller.js -->
<script src="<?=$baseUrl?>/js/menu/app/config.js" type="text/javascript"></script>
<script src="<?=$baseUrl?>/js/menu/controller/user.js" type="text/javascript"></script>
<!-- endbuild -->
<script src="<?=$mainBaseUrl?>/js/jquery-ui.js" type="text/javascript"></script>
<script src="<?=$mainBaseUrl?>/js/angular.js" type="text/javascript"></script>
<script src="<?=$mainBaseUrl?>/js/ui-bootstrap-custom-2.5.0.min.js" type="text/javascript"></script>
<script src="<?=$mainBaseUrl?>/js/ui-bootstrap-custom-tpls-2.5.0.min.js" type="text/javascript"></script>
<script src="<?=$baseUrl?>/js/angular-ui-tree.js" type="text/javascript"></script>
<script src="<?=$mainBaseUrl?>/js/angular-route.js" type="text/javascript"></script>
<script src="<?=$mainBaseUrl?>/js/angular-cookies.js" type="text/javascript"></script>
<!--<script src="js/laydate.js" type="text/javascript"></script>
<script src="ue/ueditor.config.js" type="text/javascript"></script>
<script src="ue/ueditor.all.js" type="text/javascript"></script>
<script src="ue/lang/zh-cn/zh-cn.js" type="text/javascript"></script>-->

<script src="<?=$mainBaseUrl?>/js/autocomplete.js" type="text/javascript"></script>
</body>

</html>