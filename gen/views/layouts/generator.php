<?php $this->beginContent('gen.views.layouts.main'); ?>
<?
$names = [
	'app'=>'应用信息',
	'controller'=>'控制器',
	'custom'=>'页面',
	'modelv1'=>'模型',
	'patch'=>'补丁'
]
?>
<div class="container">
	<div class="span-4">
		<div id="sidebar">
		<?php $this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>'生成器',
		)); ?>
			<ul>
				<?php foreach($this->module->controllerMap as $name=>$config): ?>
				<li class="<?=('gen/'.$name.'/index' ==  $this->route)?'active':''?>"><?php echo CHtml::link(ucwords(CHtml::encode(@$names[$name])),array($name.'/index'));?></li>
				<?php endforeach; ?>
			</ul>
		<?php $this->endWidget(); ?>
		</div><!-- sidebar -->
	</div>
	<div class="span-16">
		<div id="content">
			<?php echo $content; ?>
		</div><!-- content -->
	</div>
	<div class="span-4 last">
		&nbsp;
	</div>
</div>
<?php $this->endContent(); ?>