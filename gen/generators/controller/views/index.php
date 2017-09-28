<?php $form=$this->beginWidget('CCodeForm', array('model'=>$model)); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'controller'); ?>
		<?php echo $form->textField($model,'controller',array('size'=>65,'id'=>'input-controller')); ?>
		<div class="tooltip">
			This is the class that the new controller class will extend from.
			Please make sure the class exists and can be autoloaded.
		</div>
		<?php echo $form->error($model,'controller'); ?>
	</div>

<?php $this->endWidget(); ?>
<script type="text/javascript">
	$(function(){
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

