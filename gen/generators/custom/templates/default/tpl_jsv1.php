<?
    $primary = strtoupper($page[0]);
    $linePage = Utilities::toUnderScore($page);
    $lowCtrl = strtolower($page);
?>
$(function () {
    var <?=$app."App"?> = angular.module(_MOUDLE_NAME);
    if(!<?=$app."App"?>){
        console.e("no app");
        return;
    }
    <?=$app."App"?>.component("t<?=ucfirst($page);?>List",{
        templateUrl : "mgr/<?=$lowCtrl?>List",
        controller : function($scope,$http,Tab,TabCtrl,$rootScope,LoadData,DataSearch,TimeSelect,FormHelper,Helper,$timeout,uiGridConstants, uiGridPaginationService,ModalService){

            $(".modal").one("show.bs.modal",function(){
                $(this).appendTo("body");
            });

            $scope._LS = $rootScope._LS;

            var <?=ucfirst($page);?>ListCenter = {},TLC = <?=ucfirst($page);?>ListCenter;

            <?=$primary?>LC.grid = {};
            <?=$primary?>LC.grid.options = {};
            <?=$primary?>LC.grid.update  = function (list,sum) {
                // if(<?=$primary?>LC.grid.options){
                <?=$primary?>LC.grid.options.totalItems = sum;
                <?=$primary?>LC.grid.options.data = list;
                // }
            };
            <?=$primary?>LC.grid.init = function () {
                var config = {
                    useExternalFiltering: true,
                    enableColumnResizing: true,
                    enableCellEdit: false,
                    enablePinning: true,
                    disableCancelFilterButton: false,
                    data: <?=$primary?>LC.<?=$lowCtrl?>Log.load.itemList,
                    enableFiltering: true,
                    showGridFooter:false,
                    enablePagination:true,
                    enablePaginationControls:true,
                    columnDefs: [
                        Helper.UiGridHelper.newInput({
                            field: 'host__nickname',
                            displayName: '传播者',
                            widthName: 6
                        }),
                        Helper.UiGridHelper.newInput({
                            field: 'user__nickname',
                            displayName: '访问者',
                            widthName: 6
                        }),
                        Helper.UiGridHelper.newInput({
                            field: 'task_id',
                            displayName: '任务ID',
                            widthName: 6
                        }),
                        Helper.UiGridHelper.newRanger({
                            field: 'created',
                            displayName: '访问时间',
                            widthName: 6,
                            timer : "grid.appScope.<?=$primary?>LC.<?=$lowCtrl?>LogSearch.search.keywords.created"
                        }),
                        Helper.UiGridHelper.newInput({
                            enableFiltering: false,
                            field: 'amount',
                            displayName: '收益',
                            widthName: 6
                        })
                        // {
                        //     name: '操作',
                        //     pinnedRight: true,
                        //     enableFiltering: false,
                        //     enableColumnResizing: false,
                        //     width: 120,
                        //     maxWidth: 2000, minWidth: 120,
                        //     cellTemplate: '<button type="button" class="btn btn-link" ng-click="grid.appScope.<?=$primary?>LC.single.dealAction(row.entity,grid.appScope.<?=$primary?>LC.batch.getActionVerb(action),action);" ng-repeat="action in row.entity.actions">{{grid.appScope.<?=$primary?>LC.batch.getActionName(action)}}</button>'
                        // },
                        // Helper.UiGridHelper.newLog({
                        //     click : "grid.appScope.<?=$primary?>LC.single.dealAction(row.entity,\'log\');"
                        // })
                    ],
                    //---------------api---------------------
                    onRegisterApi: function (gridApi) {
                        $scope.gridApi = gridApi;
                        //分页按钮事件
                        gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
                            console.log("case4");
                            console.log([newPage, pageSize]);
                            var params = {};
                            // var vipstart = BLC.bdLogSearch.search.keywords.vipstart;
                            // var vipend = BLC.bdLogSearch.search.keywords.vipend;
                            // angular.forEach($scope.<?=$primary?>LC.grid.options.columnDefs, function (col, key) {
                            //     if (col.filter) {
                            //         params[col.field] = col.filter.term;
                            //     }
                            // });
                            // params.vipstart = vipstart;
                            // params.vipend = vipend;
                            // BLC.bdLogSearch.search.keywords = angular.copy(params);

                            <?=$primary?>LC.<?=$lowCtrl?>Log.load.pagesize = pageSize;
                            <?=$primary?>LC.<?=$lowCtrl?>Log.load.getPage(newPage);
                        });
                        gridApi.core.on.filterChanged($scope, function (a, b) {
                            console.log("case3");

                            var params = {};
                            var created = <?=$primary?>LC.<?=$lowCtrl?>LogSearch.search.keywords.created;
                            // var vipend = BLC.bdLogSearch.search.keywords.vipend;
                            angular.forEach($scope.<?=$primary?>LC.grid.options.columnDefs, function (col, key) {
                                if (col.filter) {
                                    params[col.field] = col.filter.term;
                                }
                            });
                            params.created = created;
                            // params.vipend = vipend;
                            <?=$primary?>LC.<?=$lowCtrl?>LogSearch.search.keywords = angular.copy(params);

                            <?=$primary?>LC.<?=$lowCtrl?>LogSearch.search.go();

                            $scope.<?=$primary?>LC.grid.options.paginationCurrentPage = 1;

                        });
                    }
                };

                <?=$primary?>LC.grid.options = $.extend({}, Helper.UiGridHelper.COMMON_CONFIG ,config)
            };

            <?=$primary?>LC.<?=$lowCtrl?>Log = new LoadData({
                loadUrl : "/get<?=$lowCtrl?>list",
                getParams : function () {
                    return angular.copy(<?=$primary?>LC.<?=$lowCtrl?>LogSearch.search.keywords);
                },
                onLoaded:function(list,page,result){
                    <?=$primary?>LC.grid.update(list,result.data.sum);
                }
            });
            <?=$primary?>LC.<?=$lowCtrl?>LogSearch = new DataSearch({
                onSearch : function () {
                    <?=$primary?>LC.<?=$lowCtrl?>Log.load.reset();
                },
                onReset : function(){
                }
            });

            <?=$primary?>LC.<?=$lowCtrl?>LogSearch.search.reset = function(){
                <?=$primary?>LC.<?=$lowCtrl?>LogSearch.search.keywords = {};
                $(".search-time-input").val("");
            }

            <?=$primary?>LC.single = {};
            <?=$primary?>LC.single.dealAction = function (<?=$lowCtrl?>,verb) {
                if ("<?=$lowCtrl?>_edit"==verb){
                    <?=$primary?>LC.edit<?=ucfirst($page);?>(<?=$lowCtrl?>.id);
                    return ;
                }
                if ("<?=$lowCtrl?>_delete"==verb){
                    <?=$primary?>LC.delete<?=ucfirst($page);?>(<?=$lowCtrl?>.id);
                    return ;
                }
            };
            <?=$primary?>LC.batch = {};
            <?=$primary?>LC.batch.dealAction = function (verb) {
                var selectedList = <?=$primary?>LC.batch.getSelectedList();
                var ids = [];
                angular.forEach(selectedList,function (value,key) {
                    ids.push(value.id);
                });
                if ("<?=$lowCtrl?>_delete"==verb){
                    <?=$primary?>LC.delete<?=ucfirst($page);?>(ids);
                    <?=$primary?>LC.batch.actions = [];
                    return ;
                }
            }
            <?=$primary?>LC.batch.getActionName = function (action) {
                var list = action.split("|");
                return list[0];
            };
            <?=$primary?>LC.batch.getActionVerb = function (action) {
                var list = action.split("|");
                return list[1];
            };
            <?=$primary?>LC.batch.actions = [];
            <?=$primary?>LC.batch.holderActions = ["删除|delete"];
            <?=$primary?>LC.batch.calcAction = function () {
                var selectedList = <?=$primary?>LC.batch.getSelectedList();
                var actions = [];
                if(selectedList && selectedList.length > 0){
                    angular.forEach(selectedList,function (val,key) {
                        actions.push(val.actions);
                    });
                }
                var commonList = [];
                if(actions && actions.length >0){
                    var ret = angular.copy(actions[0]);
                    for (var i = 1 ;i < actions.length;i++){
                        ret = ret.intersect(actions[i]);
                    }
                    commonList = angular.copy(ret);
                }
                for (var k in commonList){
                    if ("编辑|<?=$lowCtrl?>_edit" == commonList[k]){
                        commonList.splice(k, 1);
                    }
                }
                if(commonList && commonList.length){
                    <?=$primary?>LC.batch.actions = angular.copy(commonList);
                }else{
                    <?=$primary?>LC.batch.actions = [];
                }
            }
            <?=$primary?>LC.batch.ciList = [];
            // batch select
            <?=$primary?>LC.batch.select = function(ci){
                // 限制 : 同客户 同月份 同还款账户
                if(!ci._select){

                }
                //
                ci._select = !ci._select;

                <?=$primary?>LC.batch.calcAction();
            };
            <?=$primary?>LC.batch.selectAll = function(){
                if(!<?=$primary?>LC.<?=$lowCtrl?>Log.load.itemList || !<?=$primary?>LC.<?=$lowCtrl?>Log.load.itemList.length){
                    return;
                }
                var isAllSelected = <?=$primary?>LC.batch.isAllSelected();
                <?=$primary?>LC.<?=$lowCtrl?>Log.load.itemList.forEach(function(ci){
                    ci._select = !isAllSelected;
                });
                <?=$primary?>LC.batch.calcAction();
            };
            <?=$primary?>LC.batch.isAllSelected = function(){
                if(!<?=$primary?>LC.<?=$lowCtrl?>Log.load.itemList || !<?=$primary?>LC.<?=$lowCtrl?>Log.load.itemList.length){
                    return false;
                }
                return <?=$primary?>LC.<?=$lowCtrl?>Log.load.itemList.every(function(ci){
                    return ci._select;
                });
            };
            <?=$primary?>LC.batch.hasSelected = function(){
                if(!<?=$primary?>LC.<?=$lowCtrl?>Log.load.itemList || !<?=$primary?>LC.<?=$lowCtrl?>Log.load.itemList.length){
                    return false;
                }
                return <?=$primary?>LC.<?=$lowCtrl?>Log.load.itemList.some(function(ci){
                    return ci._select;
                });
            };
            <?=$primary?>LC.batch.getSelectedList = function(){
                return <?=$primary?>LC.<?=$lowCtrl?>Log.load.itemList.filter(function(ci){
                    return ci._select;
                });
            };

            <?=$primary?>LC.add<?=ucfirst($page);?> = function(){
                var addTab = new Tab({
                    name : "<?=$lowCtrl?>-add",
                    label : "添加",
                    autoopen : true,
                });
                TabCtrl.addTab(addTab);
            };

            <?=$primary?>LC.edit<?=ucfirst($page);?> = function(id){
                var editTab = new Tab({
                    name : "<?=$lowCtrl?>-edit",
                    label : "编辑",
                    autoopen : true,
                    params : {
                        id : id,
                        noclose : true,
                    }
                });
                TabCtrl.addTab(editTab);
            };

            <?=$primary?>LC.delete<?=ucfirst($page);?> = function(id){
                if (!confirm("确定删除？")){
                    return ;
                }
                var rawParams = {
                    id : id
                };
                var params = FormHelper.prepareParams(rawParams);
                $http.post($rootScope.SERVER+"/delete<?=$lowCtrl?>", params).then(function(response){
                    switch(response.data.ret){
                        case "FAIL":
                            $().message(response.data.data);
                            break;
                        case "SUCCESS":
                            $().message("删除成功");
                            <?=$primary?>LC.<?=$lowCtrl?>LogSearch.search.go();
                            break;
                        default :
                            $().message(MSG_SERVER_ERROR);
                            break;
                    }
                });
                <?=$primary?>LC.<?=$lowCtrl?>LogSearch.search.go();
            };

            <?=$primary?>LC.init = function () {
                <?=$primary?>LC.grid.init();
            };

            $scope.$watch("<?=$primary?>LC.<?=$lowCtrl?>LogSearch.search.keywords",function () {
                <?=$primary?>LC.<?=$lowCtrl?>LogSearch.search.go();
            },true);

            <?=$primary?>LC.init();
            $scope.TLC = TLC;
        }
    });

    <?=$app."App"?>.component("t<?=ucfirst($page);?>Add",{
        templateUrl : "mgr/<?=$lowCtrl?>Add",
        bindings : {
            tab : "=",
        },
        controller : function($scope, $http, $rootScope, Tab, TabCtrl, Helper, $timeout){
            var ctrl = this;
            ctrl.$onInit = function(){
                var tab = ctrl.tab;
                var <?=ucfirst($page);?>AddCenter = {},TAC = <?=ucfirst($page);?>AddCenter;
                <?=$primary?>AC.id = Helper.IdService.genId();

                <?=$primary?>AC.info = {};
                <?=$primary?>AC.canEdit = true;
                <?=$primary?>AC.info.photoConfig = {
                    uploadType : "image",
                    showDelete : function (obj) {
                        return <?=$primary?>AC.canEdit  && obj && (obj.path || obj.id);
                    }
                };

                <?=$primary?>AC.add<?=ucfirst($page);?> = function(){
                    $http.post($rootScope.SERVER+"/add<?=$lowCtrl?>", $("#form-<?=$lowCtrl?>-add").serialize()).then(function(response){
                        var result = response.data;
                        switch(result.ret){
                            case "FAIL":
                                $().message(result.data);
                                break;
                            case "SUCCESS":
                                $().message("添加成功");
                                break;
                            default :
                                $().message(MSG_SERVER_ERROR);
                                break;
                        }
                    });
                }

                <?=$primary?>AC.add<?=ucfirst($page);?> = function () {
                    $("#form-<?=$lowCtrl?>-add" + <?=$primary?>AC.id).submit();
                };

                <?=$primary?>AC.init = function () {
                    <?=$primary?>AC.initForm();
                }

                <?=$primary?>AC.initForm = function () {
                    var form =  $("#form-<?=$lowCtrl?>-add" + <?=$primary?>AC.id);
                    var btn = $("#btn-<?=$lowCtrl?>-add" + <?=$primary?>AC.id);
                    if(form && form.length && btn && btn.length){
                        form.submit(function () {
                            var form = $(this);
                            form.ajaxSubmit({
                                type : "post",
                                $http : $http,
                                btn : btn,
                                done : function(result){
                                    switch(result.ret){
                                        case "FAIL":
                                            $().message(result.data);
                                            break;
                                        case "SUCCESS":
                                            $().message("添加成功");
                                            break;
                                        default:
                                            $().message(MSG_SERVER_ERROR);
                                            break;
                                    }
                                }
                            });
                            return false;
                        });
                    }else{
                        $timeout(<?=$primary?>AC.initForm,_ID_POLL_INTERVAL);
                    }
                }

                <?=$primary?>AC.close = function(){
                    TabCtrl.closeTab(tab);
                };

                <?=$primary?>AC.init();

                $scope.TAC = TAC;
            }
        }
    });

    <?=$app."App"?>.component("t<?=ucfirst($page);?>Edit",{
        templateUrl : "mgr/<?=$lowCtrl?>Edit",
        bindings : {
            tab : "=",
        },
        controller : function($scope, $http, $rootScope, Tab, TabCtrl, Helper, $timeout){
            var ctrl = this;
            ctrl.$onInit = function(){
                var tab = ctrl.tab;
                var params = tab.params;
                var <?=ucfirst($page);?>EditCenter = {},TEC = <?=ucfirst($page);?>EditCenter;
                <?=$primary?>EC.id = Helper.IdService.genId();

                $http.get($rootScope.SERVER+"/get<?=$lowCtrl?>detail", {params: params}).then(function(response){
                    var result = response.data;
                    switch(result.ret){
                        case "FAIL":
                            $().message(result.data);
                            break;
                        case "SUCCESS":
                            <?=$primary?>EC.info = result.data;
                            <?=$primary?>EC.info.photoConfig = {
                                uploadType : "image",
                                showDelete : function (obj) {
                                    return <?=$primary?>EC.canEdit  && obj &&(obj.path || obj.id);
                                }
                            };
                            break;
                        default :
                            $().message(MSG_SERVER_ERROR);
                            break;
                    }
                });

                <?=$primary?>EC.edit<?=ucfirst($page);?> = function () {
                    if (!confirm("确认修改？")){
                        return ;
                    }
                    $("#form-<?=$lowCtrl?>-edit"+<?=$primary?>EC.id).submit();
                };

                <?=$primary?>EC.init = function () {
                    <?=$primary?>EC.initForm();
                }

                <?=$primary?>EC.initForm = function () {
                    var form =  $("#form-<?=$lowCtrl?>-edit" + <?=$primary?>EC.id);
                    var btn = $("#btn-<?=$lowCtrl?>-edit" + <?=$primary?>EC.id);
                    if(form && form.length && btn && btn.length){
                        form.submit(function () {
                            var form = $(this);
                            form.ajaxSubmit({
                                type : "post",
                                $http : $http,
                                btn : btn,
                                done : function(result){
                                    switch(result.ret){
                                        case "FAIL":
                                            $().message(result.data);
                                            break;
                                        case "SUCCESS":
                                            $().message("编辑成功");
                                            <?=$primary?>EC.canEdit = true;
                                            break;
                                        default:
                                            $().message(MSG_SERVER_ERROR);
                                            break;
                                    }
                                }
                            });
                            return false;
                        });
                    }else{
                        $timeout(<?=$primary?>EC.initForm,_ID_POLL_INTERVAL);
                    }
                }

                <?=$primary?>EC.init();

                <?=$primary?>EC.close = function(){
                    TabCtrl.closeTab(tab);
                };

                $scope.TEC = TEC;
            }
        }
    });

    <?=$app."App"?>.controller("<?=ucfirst($page);?>Controller",function($scope,Tab,TabCtrl){
        var <?=ucfirst($page);?>HomeCenter = {},THC = <?=ucfirst($page);?>HomeCenter;

        <?=$primary?>HC.initList = function(){
            var listTab = new Tab({
                name : "<?=$lowCtrl?>-list",
                label : "列表",
                closeable : false,
            });
            TabCtrl.addTab(listTab);
        };
        <?=$primary?>HC.init = function(){
            <?=$primary?>HC.initList();
        };

        $scope.TC = TabCtrl;
        $scope.THC = THC;

        <?=$primary?>HC.init();
    });
});