<div> 当前状态</div>
<div style="display: none" class="info-status">
	<div class="status-name">应用名</div>
	<div class="status-value"><?=@$model->current_appname?></div>
</div>
<div style="display: none" class="info-status">
	<div class="status-name">数据库</div>
	<div class="status-value">
		<?=@$model->current_dbname?>
		(<?
		$error = '';
		try{
			$db = Yii::app()->db;
		}catch (CDbException $e){
			$error = $e->getMessage();
		};
		if($error){
			?>
			<span class="label label-danger"><span class="glyphicon glyphicon-remove"></span>(异常)</span>
			<?
		}else{
			?>
			<span class="label label-success"><span class="glyphicon glyphicon-ok"></span>(正常)</span>
			<?
		}
		if($error){
			?>
			<!--<input name="createDb" type="button" value="createDb" id="btn-create-db">-->
			<?
		}
		?>);
	</div>
</div>
<div style="display: none" class="info-status">
	<div class="status-name">盐值</div>
	<div class="status-value"><?=@$model->current_salt?></div>
</div>
<?php $form=$this->beginWidget('CCodeForm', array('model'=>$model)); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'appname'); ?>
		<?php echo $form->textField($model,'appname',array('size'=>65,'id'=>'input-appname')); ?>
		<div class="tooltip">
			This is the class that the new controller class will extend from.
			Please make sure the class exists and can be autoloaded.
		</div>
		<?php echo $form->error($model,'appname'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'dbname'); ?>
		<?php echo $form->textField($model,'dbname',array('size'=>65,'id'=>'input-dbname')); ?>
		<div>
			<input name="createDb" type="button" value="createDb" id="btn-create-db">

			<input name="admin[mobile]" value="15972971860" type="text" id="admin-mobile">
			<input name="admin[pwd]" value="pinganzhihui999" type="text" id="admin-pwd">
			<input name="createDb" type="button" value="createAdmin" id="btn-create-admin">
		</div>
		<div class="tooltip">
			This is the class that the new controller class will extend from.
			Please make sure the class exists and can be autoloaded.
		</div>
		<?php echo $form->error($model,'dbname'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'salt'); ?>
		<?php echo $form->textField($model,'salt',array('size'=>65,'id'=>'input-salt')); ?>
		<div class="tooltip">
			This is the class that the new controller class will extend from.
			Please make sure the class exists and can be autoloaded.
		</div>
		<?php echo $form->error($model,'salt'); ?>
	</div>

<?php $this->endWidget(); ?>
<script type="text/javascript">
	$(function(){
		$("#btn-create-db").click(function () {

			$.post("<?=Yii::app()->getUrlManager()->createUrl('gen/default/createdb')?>",{
				dbname : $.trim($("#input-dbname").val())
			},function (respo) {
				console.log(respo);
				switch (respo.ret){
					case "SUCCESS":
						$().message("添加成功");
						window.location.href += "";
						break;
					case "FAIL":
						$().message(respo.data+"<br>"+respo.debug);
						break;
					default:
						$().message("未知反馈");
						break;
				}
			},"json")
		});
		$("#btn-create-admin").click(function () {
			$.post("<?=Yii::app()->getUrlManager()->createUrl('gen/default/createadmin')?>",{
				mobile : $.trim($("#admin-mobile").val()),
				pwd : $.trim($("#admin-pwd").val())
			},function (respo) {
				console.log(respo);
				switch (respo.ret){
					case "SUCCESS":
						$().message("添加成功");
						break;
					case "FAIL":
						$().message(respo.data+"<br>"+respo.debug);
						break;
					default:
						$().message("未知反馈");
						break;
				}
			},"json")
		});
		if(true){
			return;
		}
		var app = angular.module('createCodeApp', ['ui.autocomplete']);

		app.controller('createCodeCtrl', function($scope,$compile,$timeout){
			var index = 4;
			$scope.fieldTotal = _fields;
			var demo = {
				zh : "",
				en : "",
				type : "input",
				width : "tb-col-"+"",
				list: true,
				add : true,
				edit : true,
			};

			if(!$scope.fieldTotal || $scope.fieldTotal.length == 0){
				$scope.fieldTotal.push(angular.copy(demo));
			}

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
								width : "tb-col-"+key,
								list: true,
								add : true,
								edit : true,
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
				var one = {
					zh : "",
					en : "",
					type : "input",
					width : "tb-col-"+"",
					list: true,
					add : true,
					edit : true,
				}
				if(!$scope.fieldTotal){
					$scope.fieldTotal = [];
				}
				$scope.fieldTotal.splice(offset+1, 0, angular.copy(one));
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

		/*$("#btn-create").click(function(){
			if (!$("#input-app").val()){
				$("#result-info").html('请填写App的名称'.fontcolor('red'));
				return ;
			}
			if (!$("#input-controller").val()){
				$("#result-info").html('请填写Controller的名称'.fontcolor('red'));
				return ;
			}
			if (!$("#input-page").val()){
				$("#result-info").html('请填写Page的名称'.fontcolor('red'));
				return ;
			}
			if (!$("#input-field-name").val()){
				$("#result-info").html('请填写字段'.fontcolor('red'));
				return ;
			}
			$.post('http://localhost/56otc/site/createCode',$("#form-create-code").serialize(),function(result){
				switch(result.ret){
					case 'FAIL':
						$("#result-info").html(result.data.fontcolor('red'));
						break;
					case 'SUCCESS':
						$("#result-info").html(result.data.fontcolor('green'));
						break;
					default :
						/!*alert('服务器错误');*!/
						break;
				}
			});
		});

		$("#btn-test-create").click(function(){
			if (!$("#input-app").val()){
				$("#result-info").html('请填写App的名称'.fontcolor('red'));
				return ;
			}
			if (!$("#input-controller").val()){
				$("#result-info").html('请填写Controller的名称'.fontcolor('red'));
				return ;
			}
			if (!$("#input-page").val()){
				$("#result-info").html('请填写Page的名称'.fontcolor('red'));
				return ;
			}
			$.ajaxSetup({
				async : false
			});
			$.post('http://localhost/56otc/gen/createHome',$("#test-create-code").serialize(),function(result){
				switch(result.ret){
					case 'FAIL':
						$("#result-info").html(result.data.fontcolor('red') + '<br>');
						break;
					case 'SUCCESS':
						$("#result-info").html(result.data.fontcolor('green') + '<br>');
						break;
					default :
						alert('服务器错误');
						break;
				}
			});
			$.post('http://localhost/56otc/gen/createList',$("#test-create-code").serialize(),function(result){
				switch(result.ret){
					case 'FAIL':
						$("#result-info").html($("#result-info").html() + result.data.fontcolor('red') + '<br>');
						break;
					case 'SUCCESS':
						$("#result-info").html($("#result-info").html() + result.data.fontcolor('green') + '<br>');
						break;
					default :
						/!*alert('服务器错误');*!/
						break;
				}
			});
			$.post('http://localhost/56otc/gen/createAdd',$("#test-create-code").serialize(),function(result){
				switch(result.ret){
					case 'FAIL':
						$("#result-info").html($("#result-info").html() + result.data.fontcolor('red') + '<br>');
						break;
					case 'SUCCESS':
						$("#result-info").html($("#result-info").html() + result.data.fontcolor('green') + '<br>');
						break;
					default :
						/!*alert('服务器错误');*!/
						break;
				}
			});
			$.post('http://localhost/56otc/gen/createEdit',$("#test-create-code").serialize(),function(result){
				switch(result.ret){
					case 'FAIL':
						$("#result-info").html($("#result-info").html() + result.data.fontcolor('red') + '<br>');
						break;
					case 'SUCCESS':
						$("#result-info").html($("#result-info").html() + result.data.fontcolor('green') + '<br>');
						break;
					default :
						/!*alert('服务器错误');*!/
						break;
				}
			});
			$.post('http://localhost/56otc/gen/createJS',$("#test-create-code").serialize(),function(result){
				switch(result.ret){
					case 'FAIL':
						$("#result-info").html($("#result-info").html() + result.data.fontcolor('red') + '<br>');
						break;
					case 'SUCCESS':
						$("#result-info").html($("#result-info").html() + result.data.fontcolor('green') + '<br>');
						break;
					default :
						/!*alert('服务器错误');*!/
						break;
				}
			});
		});*/
	});
</script>

