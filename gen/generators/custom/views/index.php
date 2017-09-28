<?php $form=$this->beginWidget('CCodeForm', array('model'=>$model)); ?>

	<div class="row sticky">
		<?php echo $form->labelEx($model,'app'); ?>
		<?php echo $form->textField($model,'app',array('size'=>65,'id'=>'input-app','disabled'=>'disabled')); ?>
		<div class="tooltip">
			This is the class that the new controller class will extend from.
			Please make sure the class exists and can be autoloaded.
		</div>
		<?php echo $form->error($model,'app'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'controller'); ?>
		<?php echo $form->dropDownList($model,'controller',Utilities::getCtrls()); ?>
		<div class="tooltip">
			Controller ID is case-sensitive. Below are some examples:
			<ul>
				<li><code>post</code> generates <code>PostController.php</code></li>
				<li><code>postTag</code> generates <code>PostTagController.php</code></li>
				<li><code>admin/user</code> generates <code>admin/UserController.php</code>.
					If the application has an <code>admin</code> module enabled,
					it will generate <code>UserController</code> within the module instead.
					Make sure to write module name in the correct case if it has a camelCase name.
				</li>
			</ul>
		</div>
		<?php echo $form->error($model,'controller'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'page'); ?>
		<?php echo $form->textField($model,'page',array('size'=>65,'id'=>'input-page')); ?>
		<div class="tooltip">
			Action IDs are case-insensitive. Separate multiple action IDs with commas or spaces.
		</div>
		<?php echo $form->error($model,'page'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'是否导入'); ?>
		<?php echo $form->checkBox($model,'btn_import'); ?>
		<div class="tooltip">
			Action IDs are case-insensitive. Separate multiple action IDs with commas or spaces.
		</div>
		<?php echo $form->error($model,'btn_import'); ?>
	</div>
	
	<?
	$dbModelList = GenUtil::getModelNameList();
	$dbModelList = array_merge(['0'=>[
		'value'=>'empty',
		'attrs'=>[

		]
	]],$dbModelList);
	$dbNameList = array_keys($dbModelList);
	$dbNameList = [];
	foreach ($dbModelList as $dbModel){
		$dbNameList[] = $dbModel['value'];
	}
	?>
	<div class="row">
		<?php echo $form->labelEx($model,'dbmodel'); ?>
		<?php echo $form->dropDownList($model,'dbmodel',$dbNameList,[
			'id'=>'input-dbmodel'
		],[
			'unselectValue'=>'33',
			'empty'=>'eee'
		]); ?>
		<div class="tooltip">
			Action IDs are case-insensitive. Separate multiple action IDs with commas or spaces.
		</div>
		<?php echo $form->error($model,'dbmodel'); ?>
	</div>
	<div class="row" ng-app="createCodeApp" ng-controller="createCodeCtrl">
		<div class="field" ng-repeat="offset in fieldTotal">
			<div class="feature">
				<select class="pull-left" id="field-{{$index}}-type" name="CustomCode[fields][{{$index}}][type]" ng-model="offset.type">
					<option value="input">输入</option>
					<option value="select">下拉</option>
					<option value="time">时间</option>
					<option value="text">文本</option>
					<option value="photo">图片</option>
					<option value="editor">编辑器</option>
				</select>

				<div class="operate pull-right">
					<input class="pull-left" type="checkbox" ng-model="offset.list" id="list-{{$index}}-page" name="CustomCode[fields][{{$index}}][list]" ng-checked="'on' == offset.list"><label class="pull-left" for="list-{{$index}}-page">列表</label>
					<input class="pull-left" type="checkbox" ng-model="offset.add" id="add-{{$index}}-page" name="CustomCode[fields][{{$index}}][add]" ng-checked="'on' == offset.add"><label class="pull-left" for="add-{{$index}}-page">添加</label>
					<input class="pull-left" type="checkbox" ng-model="offset.edit" id="edit-{{$index}}-page" name="CustomCode[fields][{{$index}}][edit]" ng-checked="'on' == offset.edit"><label class="pull-left" for="edit-{{$index}}-page">编辑</label>
					<button type="button" id="field-{{$index}}-plus" class="pull-left glyphicon glyphicon-plus" ng-click="addLine($index);"></button>
					<button type="button" id="field-{{$index}}-minus" class="pull-left glyphicon glyphicon-minus" ng-click="deleteLine($index);"></button>
				</div>
			</div>
			<div class="row row-input">
				<div class="field-zh col-sm-4">
					<input type="text" class="form-control" name="CustomCode[fields][{{$index}}][zh]" placeholder="输入中文名称" ng-model="offset.zh">
				</div>
				<div class="field-en col-sm-4">
					<input type="text" class="form-control" name="CustomCode[fields][{{$index}}][en]" placeholder="输入英文名称" ng-model="offset.en">
				</div>
				<div class="field-width col-sm-4">
					<input type="text" class="form-control" name="CustomCode[fields][{{$index}}][width]" ng-model="offset.width" placeholder="输入字段宽度">
				</div>

			</div>
			<div class="select-container row row-input" ng-show="offset.type == 'select'">
				<div class="field-status col-sm-4">
					<input type="text" class="form-control" name="CustomCode[fields][{{$index}}][statusName]" ng-model="offset.statusName" placeholder="输入下拉框状态名称">
				</div>
				<div class="field-options col-sm-8">
					<input type="text" class="form-control" name="CustomCode[fields][{{$index}}][options]" ng-model="offset.options" placeholder="输入下拉框字段">
				</div>
			</div>

		</div>

	</div>
<?php $this->endWidget(); ?>
<script type="text/javascript">
	_fields = <?=json_encode($model->fields)?>;
	_MODELS = <?=json_encode($dbModelList)?>;
	$(function(){
		var app = angular.module('createCodeApp', ['ui.autocomplete']);

		app.controller('createCodeCtrl', function($scope,$compile,$timeout){

			/*$scope.select = function(){
				$scope.import_select = !$scope.import_select;
				$scope.btn_import = $scope.import_select?"on":"off";
			}
			$scope.select();*/

			var index = 4;
			$scope.fieldTotal = _fields;
			var demo = {
				zh : "",
				en : "",
				type : "input",
				width : "col-"+"",
				list: "on",
				add : "on",
				edit : "on",
				btn_import : "on",
			};

			if(!$scope.fieldTotal || $scope.fieldTotal.length == 0){
				$scope.fieldTotal.push(angular.copy(demo));
			}
			$("#input-dbmodel").change(function () {
				$timeout(function () {
					var selectIndex= $("#input-dbmodel").val();
					if(selectIndex == 0){
						return;
					}
					var attrs = _MODELS[$("#input-dbmodel").val()]['attrs'];
					if(attrs ){
						/*if(confirm("确认使用模型？")){*/
							$scope.fieldTotal = [];

							angular.forEach(attrs,function (val, key) {
								var field = angular.copy(demo);
								field.zh = val;
								field.en = key;
								field.width = "col-n-"+key,
								$scope.fieldTotal.push(field);
							});
						/*}*/
					}
				});
			});
			$scope.myOption = {
				options: {
					html: true,
					focusOpen: false,
					onlySelectValid: true,
					source: function (request, response) {
						$timeout(function () {
							if(!$scope.fieldTotal){
								$scope.fieldTotal = [];
							}else{
								$scope.fieldTotal = [];
							}
							$scope.fieldTotal.push(angular.copy(demo));
						});

						console.log(request);
						var data = _MODELS;
						data = $scope.myOption.methods.filter(data, request.term);

						if (!data.length) {
							/*data.push({
							 label: 'not found',
							 value: ''
							 });*/
						}
						/*// add "Add Language" button to autocomplete menu bottom
						 data.push({
						 label: $compile('<a class="btn btn-link ui-menu-add" ng-click="addLanguage()">Add Language</a>')($scope),
						 value: ''
						 });*/
						console.log(data);
						response(data);
					}
				},
				methods: {}
			};

			$scope.myOption.events = {
				change: function( event, ui ) {
					console.log(event,ui);
					if(!$scope.fieldTotal){
						$scope.fieldTotal = [];
					}else{
						$scope.fieldTotal = [];
					}
					if(ui.item && ui.item.attrs){
						angular.forEach(ui.item.attrs,function (value, key) {
							var one = {
								zh : value,
								en : key,
								type : "input",
								width : "col-n-"+key,
								list: "on",
								add : "on",
								edit : "on",
							}
							$scope.fieldTotal.push(one);
						});
					}else{
						$scope.fieldTotal.push(angular.copy(demo));
					}
				},
				close: function( event, ui ) {
					console.log(event,ui);

				},
			};

			$scope.addLine = function(offset){
				if(!$scope.fieldTotal){
					$scope.fieldTotal = [];
				}
				$scope.fieldTotal.splice(offset+1, 0, angular.copy(demo));
			}
			var ListHelper = {};
			ListHelper.removeByIndex = function (list,index) {
				if(!list || !list.length){
					return;
				}
				try {
					if(list.hasOwnProperty(index)){
						list.splice(index,1);
					}
				}catch (e){
				}
			};
			$scope.deleteLine = function(offset){
				if($scope.fieldTotal && $scope.fieldTotal.length == 1){
					$().message("至少留一个");
				}else{
					ListHelper.removeByIndex($scope.fieldTotal ,offset);
				}
			}
		});
	});
</script>