<?php
$cs=Yii::app()->clientScript;
$cs->coreScriptPosition=CClientScript::POS_HEAD;
$cs->scriptMap=array();
$baseUrl=$this->module->assetsUrl;
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

	<title><?='代码生成' ?></title>

	<script type="text/javascript" src="<?=$baseUrl?>/js/main.js"></script>


	<!-- Bootstrap -->
	<link href="<?=$mainBaseUrl?>/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<!--<script src="<?/*=$mainBaseUrl*/?>/js/bootstrap.min.js" type="text/javascript"></script>-->
	<!-- Normal -->
	<link href="<?=$mainBaseUrl?>/css/common.css" rel="stylesheet" type="text/css" />
	<link href="<?=$mainBaseUrl?>/css/main.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="<?=$baseUrl?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?=$baseUrl?>/css/custom.css" />
</head>

<body>

<div class="container" id="page">
	<div id="header">
		<div class="top-menus">
		<?php echo CHtml::link('菜单管理',Yii::app()->createUrl('/gen/default/menuindex')); ?> |
		<?php /*echo CHtml::link('help','http://www.yiiframework.com/doc/guide/topics.gen'); */?><!-- |
		<?php /*echo CHtml::link('webapp',Yii::app()->homeUrl); */?> |-->
		<?php echo CHtml::link('日志',['default/log',['clearlog'=>2]],['target'=>'_blank']); ?> |
		<!--<a href="http://www.yiiframework.com">yii</a>
		<?php /*if(!Yii::app()->user->isGuest): */?>
			| <?php /*echo CHtml::link('logout',array('default/logout')); */?>
		--><?php /*endif; */?>
		</div>
		<div id="logo"><?php echo CHtml::link(CHtml::image(false?($mainBaseUrl.DIRECTORY_SEPARATOR.'false'):($this->module->assetsUrl.'/images/logo.png')),array('default/index')); ?></div>
	</div><!-- header -->

	<?php echo $content; ?>

</div><!-- page -->

<div id="footer">
	<?php echo Yii::powered(); ?>
	<br/>A product of <a href="http://www.yiisoft.com">Yii Software LLC</a>.
</div><!-- footer -->
<script src="<?=$mainBaseUrl?>/js/jquery.common.js" type="text/javascript"></script>
<script src="<?=$mainBaseUrl?>/js/jquery-ui.js" type="text/javascript"></script>
<script src="<?=$mainBaseUrl?>/js/angular.min.js" type="text/javascript"></script>
<script src="<?=$mainBaseUrl?>/js/autocomplete.js" type="text/javascript"></script>
</body>

</html>