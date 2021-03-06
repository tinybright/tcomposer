$(function () {
    var wukongApp = angular.module(_MOUDLE_NAME);
    if(!wukongApp){
        console.e("no app");
        return;
    }
    wukongApp.component("tTestdataList",{
        templateUrl : "mgr/testdataList",
        controller : function($scope,$http,Tab,TabCtrl,$rootScope,LoadData,DataSearch,TimeSelect,FormHelper,Helper,$timeout,uiGridConstants, uiGridPaginationService,ModalService){

            $(".modal").one("show.bs.modal",function(){
                $(this).appendTo("body");
            });

            $scope._LS = $rootScope._LS;

            var TestdataListCenter = {},TLC = TestdataListCenter;

            TLC.grid = {};
            TLC.grid.options = {};
            TLC.grid.update  = function (list,sum) {
                // if(TLC.grid.options){
                TLC.grid.options.totalItems = sum;
                TLC.grid.options.data = list;
                // }
            };
            TLC.grid.init = function () {
                var config = {
                    useExternalFiltering: true,
                    enableColumnResizing: true,
                    enableCellEdit: false,
                    enablePinning: true,
                    disableCancelFilterButton: false,
                    data: TLC.testdataLog.load.itemList,
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
                            timer : "grid.appScope.TLC.testdataLogSearch.search.keywords.created"
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
                        //     cellTemplate: '<button type="button" class="btn btn-link" ng-click="grid.appScope.TLC.single.dealAction(row.entity,grid.appScope.TLC.batch.getActionVerb(action),action);" ng-repeat="action in row.entity.actions">{{grid.appScope.TLC.batch.getActionName(action)}}</button>'
                        // },
                        // Helper.UiGridHelper.newLog({
                        //     click : "grid.appScope.TLC.single.dealAction(row.entity,\'log\');"
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
                            // angular.forEach($scope.TLC.grid.options.columnDefs, function (col, key) {
                            //     if (col.filter) {
                            //         params[col.field] = col.filter.term;
                            //     }
                            // });
                            // params.vipstart = vipstart;
                            // params.vipend = vipend;
                            // BLC.bdLogSearch.search.keywords = angular.copy(params);

                            TLC.testdataLog.load.pagesize = pageSize;
                            TLC.testdataLog.load.getPage(newPage);
                        });
                        gridApi.core.on.filterChanged($scope, function (a, b) {
                            console.log("case3");

                            var params = {};
                            var created = TLC.testdataLogSearch.search.keywords.created;
                            // var vipend = BLC.bdLogSearch.search.keywords.vipend;
                            angular.forEach($scope.TLC.grid.options.columnDefs, function (col, key) {
                                if (col.filter) {
                                    params[col.field] = col.filter.term;
                                }
                            });
                            params.created = created;
                            // params.vipend = vipend;
                            TLC.testdataLogSearch.search.keywords = angular.copy(params);

                            TLC.testdataLogSearch.search.go();

                            $scope.TLC.grid.options.paginationCurrentPage = 1;

                        });
                    }
                };

                TLC.grid.options = $.extend({}, Helper.UiGridHelper.COMMON_CONFIG ,config)
            };

            TLC.testdataLog = new LoadData({
                loadUrl : "/gettestdatalist",
                getParams : function () {
                    return angular.copy(TLC.testdataLogSearch.search.keywords);
                },
                onLoaded:function(list,page,result){
                    TLC.grid.update(list,result.data.sum);
                }
            });
            TLC.testdataLogSearch = new DataSearch({
                onSearch : function () {
                    TLC.testdataLog.load.reset();
                },
                onReset : function(){
                }
            });

            TLC.testdataLogSearch.search.reset = function(){
                TLC.testdataLogSearch.search.keywords = {};
                $(".search-time-input").val("");
            }

            TLC.single = {};
            TLC.single.dealAction = function (testdata,verb) {
                if ("testdata_edit"==verb){
                    TLC.editTestdata(testdata.id);
                    return ;
                }
                if ("testdata_delete"==verb){
                    TLC.deleteTestdata(testdata.id);
                    return ;
                }
            };
            TLC.batch = {};
            TLC.batch.dealAction = function (verb) {
                var selectedList = TLC.batch.getSelectedList();
                var ids = [];
                angular.forEach(selectedList,function (value,key) {
                    ids.push(value.id);
                });
                if ("testdata_delete"==verb){
                    TLC.deleteTestdata(ids);
                    TLC.batch.actions = [];
                    return ;
                }
            }
            TLC.batch.getActionName = function (action) {
                var list = action.split("|");
                return list[0];
            };
            TLC.batch.getActionVerb = function (action) {
                var list = action.split("|");
                return list[1];
            };
            TLC.batch.actions = [];
            TLC.batch.holderActions = ["删除|delete"];
            TLC.batch.calcAction = function () {
                var selectedList = TLC.batch.getSelectedList();
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
                    if ("编辑|testdata_edit" == commonList[k]){
                        commonList.splice(k, 1);
                    }
                }
                if(commonList && commonList.length){
                    TLC.batch.actions = angular.copy(commonList);
                }else{
                    TLC.batch.actions = [];
                }
            }
            TLC.batch.ciList = [];
            // batch select
            TLC.batch.select = function(ci){
                // 限制 : 同客户 同月份 同还款账户
                if(!ci._select){

                }
                //
                ci._select = !ci._select;

                TLC.batch.calcAction();
            };
            TLC.batch.selectAll = function(){
                if(!TLC.testdataLog.load.itemList || !TLC.testdataLog.load.itemList.length){
                    return;
                }
                var isAllSelected = TLC.batch.isAllSelected();
                TLC.testdataLog.load.itemList.forEach(function(ci){
                    ci._select = !isAllSelected;
                });
                TLC.batch.calcAction();
            };
            TLC.batch.isAllSelected = function(){
                if(!TLC.testdataLog.load.itemList || !TLC.testdataLog.load.itemList.length){
                    return false;
                }
                return TLC.testdataLog.load.itemList.every(function(ci){
                    return ci._select;
                });
            };
            TLC.batch.hasSelected = function(){
                if(!TLC.testdataLog.load.itemList || !TLC.testdataLog.load.itemList.length){
                    return false;
                }
                return TLC.testdataLog.load.itemList.some(function(ci){
                    return ci._select;
                });
            };
            TLC.batch.getSelectedList = function(){
                return TLC.testdataLog.load.itemList.filter(function(ci){
                    return ci._select;
                });
            };

            TLC.addTestdata = function(){
                var addTab = new Tab({
                    name : "testdata-add",
                    label : "添加",
                    autoopen : true,
                });
                TabCtrl.addTab(addTab);
            };

            TLC.editTestdata = function(id){
                var editTab = new Tab({
                    name : "testdata-edit",
                    label : "编辑",
                    autoopen : true,
                    params : {
                        id : id,
                        noclose : true,
                    }
                });
                TabCtrl.addTab(editTab);
            };

            TLC.deleteTestdata = function(id){
                if (!confirm("确定删除？")){
                    return ;
                }
                var rawParams = {
                    id : id
                };
                var params = FormHelper.prepareParams(rawParams);
                $http.post($rootScope.SERVER+"/deletetestdata", params).then(function(response){
                    switch(response.data.ret){
                        case "FAIL":
                            $().message(response.data.data);
                            break;
                        case "SUCCESS":
                            $().message("删除成功");
                            TLC.testdataLogSearch.search.go();
                            break;
                        default :
                            $().message(MSG_SERVER_ERROR);
                            break;
                    }
                });
                TLC.testdataLogSearch.search.go();
            };

            TLC.init = function () {
                TLC.grid.init();
            };

            $scope.$watch("TLC.testdataLogSearch.search.keywords",function () {
                TLC.testdataLogSearch.search.go();
            },true);

            TLC.init();
            $scope.TLC = TLC;
        }
    });

    wukongApp.component("tTestdataAdd",{
        templateUrl : "mgr/testdataAdd",
        bindings : {
            tab : "=",
        },
        controller : function($scope, $http, $rootScope, Tab, TabCtrl, Helper, $timeout){
            var ctrl = this;
            ctrl.$onInit = function(){
                var tab = ctrl.tab;
                var TestdataAddCenter = {},TAC = TestdataAddCenter;
                TAC.id = Helper.IdService.genId();

                TAC.info = {};
                TAC.canEdit = true;
                TAC.info.photoConfig = {
                    uploadType : "image",
                    showDelete : function (obj) {
                        return TAC.canEdit  && obj && (obj.path || obj.id);
                    }
                };

                TAC.addTestdata = function(){
                    $http.post($rootScope.SERVER+"/addtestdata", $("#form-testdata-add").serialize()).then(function(response){
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

                TAC.addTestdata = function () {
                    $("#form-testdata-add" + TAC.id).submit();
                };

                TAC.init = function () {
                    TAC.initForm();
                }

                TAC.initForm = function () {
                    var form =  $("#form-testdata-add" + TAC.id);
                    var btn = $("#btn-testdata-add" + TAC.id);
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
                        $timeout(TAC.initForm,_ID_POLL_INTERVAL);
                    }
                }

                TAC.close = function(){
                    TabCtrl.closeTab(tab);
                };

                TAC.init();
                
                $scope.TAC = TAC;
            }
        }
    });

    wukongApp.component("tTestdataEdit",{
        templateUrl : "mgr/testdataEdit",
        bindings : {
            tab : "=",
        },
        controller : function($scope, $http, $rootScope, Tab, TabCtrl, Helper, $timeout){
            var ctrl = this;
            ctrl.$onInit = function(){
                var tab = ctrl.tab;
                var params = tab.params;
                var TestdataEditCenter = {},TEC = TestdataEditCenter;
                TEC.id = Helper.IdService.genId();

                $http.get($rootScope.SERVER+"/gettestdatadetail", {params: params}).then(function(response){
                    var result = response.data;
                    switch(result.ret){
                        case "FAIL":
                            $().message(result.data);
                            break;
                        case "SUCCESS":
                            TEC.info = result.data;
                            TEC.info.photoConfig = {
                                uploadType : "image",
                                showDelete : function (obj) {
                                    return TEC.canEdit  && obj &&(obj.path || obj.id);
                                }
                            };
                            break;
                        default :
                            $().message(MSG_SERVER_ERROR);
                            break;
                    }
                });

                TEC.editTestdata = function () {
                    if (!confirm("确认修改？")){
                        return ;
                    }
                    $("#form-testdata-edit"+TEC.id).submit();
                };

                TEC.init = function () {
                    TEC.initForm();
                }

                TEC.initForm = function () {
                    var form =  $("#form-testdata-edit" + TEC.id);
                    var btn = $("#btn-testdata-edit" + TEC.id);
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
                                            TEC.canEdit = true;
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
                        $timeout(TEC.initForm,_ID_POLL_INTERVAL);
                    }
                }

                TEC.init();

                TEC.close = function(){
                    TabCtrl.closeTab(tab);
                };

                $scope.TEC = TEC;
            }
        }
    });

    wukongApp.controller("TestdataController",function($scope,Tab,TabCtrl){
        var TestdataHomeCenter = {},THC = TestdataHomeCenter;

        THC.initList = function(){
            var listTab = new Tab({
                name : "testdata-list",
                label : "列表",
                closeable : false,
            });
            TabCtrl.addTab(listTab);
        };
        THC.init = function(){
            THC.initList();
        };

        $scope.TC = TabCtrl;
        $scope.THC = THC;

        THC.init();
    });
});