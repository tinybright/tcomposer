<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<?
echo <<<EOF

		<?\$this->widget('ext.modal.SimpleModalGroup',[
			'layouts'=>[
				'common-head'
			]
		]);?>
EOF;

		?>

		<link href="css/mgr.common.css" rel="stylesheet" type="text/css" />
		<link href="css/mgr.main.css" rel="stylesheet" type="text/css" />
		<?
echo <<<EOF

		<title><?=\$this->pageTitle?></title>
		
EOF;

		?>
	</head>
	<?
echo <<<EOF

	<?\$this->widget('ext.modal.SimpleModalGroup',[
		'layouts'=>[
			'constant'
		]
	]);?>
	<?=\$content?>
EOF;

	?>
	<script src="js/jquery.common.js" type="text/javascript"></script>
	<!--custom_start-->
	<!-- build:js js/base.controller.js -->
	<script src="js/base/app/app.js" type="text/javascript"></script>
	<script src="js/base/service/common.js" type="text/javascript"></script>
	<script src="js/base/component/common.js" type="text/javascript"></script>
	<script src="js/base/filter/common.js" type="text/javascript"></script>
	<!-- endbuild -->
	<!-- build:js js/<?=$controller?>.controller.js -->
	<script src="js/<?=$controller?>/app/config.js" type="text/javascript"></script>
	<!-- endbuild -->
	<!--custom_end-->
	<script src="js/nzone.min.js" type="text/javascript"></script>
	<script src="js/angular.js" type="text/javascript"></script>
	<script src="js/angular-route.min.js" type="text/javascript"></script>
	<script src="js/laydate.js" type="text/javascript"></script>
	<script src="ue/ueditor.config.js" type="text/javascript"></script>
	<script src="ue/ueditor.all.js" type="text/javascript"></script>
	<script src="ue/lang/zh-cn/zh-cn.js" type="text/javascript"></script>

</html>