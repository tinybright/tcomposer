<div> 当前状态</div>
<div class="info-status">
	<div class="status-name">对比</div>
</div>

<?php $form=$this->beginWidget('CCodeForm', array('model'=>$model)); ?>

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
	});
</script>

