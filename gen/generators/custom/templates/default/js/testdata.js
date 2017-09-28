$(function () {
    var wukongApp = angular.module(_MOUDLE_NAME);
    if(!wukongApp){
        console.e("no app");
        return;
    }
    wukongApp.component("tTestdataList",{
        templateUrl : "mgr/testdataList",
        controller : function($scope,$http,Tab,TabCtrl,$rootScope,LoadData,DataSearch,TimeSelect,FormHelper,Helper,$timeout){

            $(".modal").one("show.bs.modal",function(){
                $(this).appendTo("body");
            });

            $scope._LS = $rootScope._LS;

            var TestdataListCenter = {},TLC = TestdataListCenter;

            TLC.testdataLog = new LoadData({
                loadUrl : "/gettestdatalist",
                getParams : function () {
                    return angular.copy(TLC.testdataLogSearch.search.keywords);
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