$(function () {
    var wukongApp = angular.module(_MOUDLE_NAME);
    if(!wukongApp){
        console.e("no app");
        return;
    }
    wukongApp.component("tConfigList",{
        templateUrl : "mgr/configList",
        controller : function($scope,$http,Tab,TabCtrl,$rootScope,LoadData,DataSearch,TimeSelect,FormHelper,Helper,$timeout,uiGridConstants, uiGridPaginationService,ModalService){

            $(".modal").one("show.bs.modal",function(){
                $(this).appendTo("body");
            });

            $scope._LS = $rootScope._LS;
            $scope.ARRS = $rootScope.ARRS;
            var ConfigListCenter = {},CLC = ConfigListCenter;

            CLC.grid = {};
            CLC.grid.options = {};
            CLC.grid.update  = function (list,sum) {
                // if(CLC.grid.options){
                CLC.grid.options.totalItems = sum;
                CLC.grid.options.data = list;
                // }
            };
            CLC.grid.init = function () {
                var config = {
                    useExternalFiltering: true,
                    enableColumnResizing: true,
                    enableCellEdit: false,
                    enablePinning: true,
                    disableCancelFilterButton: false,
                    data: CLC.configLog.load.itemList,
                    enableFiltering: true,
                    showGridFooter:false,
                    enablePagination:true,
                    enablePaginationControls:true,
                    columnDefs: [
                        {
                            field : 'key',
                            width : 120,
                            displayName:"名称",
                            enableColumnMenu : true,
                            filter:{
                                term : "",
                                type : uiGridConstants.filter.SELECT,
                                selectOptions : Helper.UiGridHelper.getSelectOptions(ARRS.CONFIG_NAME),
                                disableCancelFilterButton: false
                            },
                            cellTemplate:"<div class='ui-grid-cell-contents'>{{row.entity.key | CONFIG_NAME}}</div>"
                        },
                        Helper.UiGridHelper.newInput({
                            field: 'value',
                            displayName: '值',
                            widthName: 6
                        }),

                        // Helper.UiGridHelper.newInput({
                        //     field: 'key',
                        //     displayName: '键',
                        //     widthName: 6
                        // }),

                        // Helper.UiGridHelper.newInput({
                        //     field: 'user__nickname',
                        //     displayName: '相关人',
                        //     widthName: 6
                        // }),

                        Helper.UiGridHelper.newInput({
                            field: 'creator__nickname',
                            displayName: '创建人',
                            widthName: 6
                        }),

                        Helper.UiGridHelper.newRanger({
                            field: 'updated',
                            displayName: '更新时间',
                            widthName: 6,
                            timer : "grid.appScope.CLC.configLogSearch.search.keywords.updated"
                        }),

                        {
                            name: '操作',
                            pinnedRight: true,
                            enableFiltering: false,
                            enableColumnResizing: false,
                            width: 120,
                            maxWidth: 2000, minWidth: 120,
                            cellTemplate: '<button type="button" class="btn btn-link" ng-click="grid.appScope.CLC.single.dealAction(row.entity,grid.appScope.CLC.batch.getActionVerb(action),action);" ng-repeat="action in row.entity.actions">{{grid.appScope.CLC.batch.getActionName(action)}}</button>'
                        }
                        // ,
                        // Helper.UiGridHelper.newLog({
                        //     click : "grid.appScope.CLC.single.dealAction(row.entity,\'log\');"
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
                            // angular.forEach($scope.CLC.grid.options.columnDefs, function (col, key) {
                            //     if (col.filter) {
                            //         params[col.field] = col.filter.term;
                            //     }
                            // });
                            // params.vipstart = vipstart;
                            // params.vipend = vipend;
                            // BLC.bdLogSearch.search.keywords = angular.copy(params);

                            CLC.configLog.load.pagesize = pageSize;
                            CLC.configLog.load.getPage(newPage);
                        });
                        gridApi.core.on.filterChanged($scope, function (a, b) {
                            console.log("case3");

                            var params = {};
                            var created = CLC.configLogSearch.search.keywords.created;
                            // var vipend = BLC.bdLogSearch.search.keywords.vipend;
                            angular.forEach($scope.CLC.grid.options.columnDefs, function (col, key) {
                                if (col.filter) {
                                    params[col.field] = col.filter.term;
                                }
                            });
                            params.created = created;
                            // params.vipend = vipend;
                            CLC.configLogSearch.search.keywords = angular.copy(params);

                            CLC.configLogSearch.search.go();

                            $scope.CLC.grid.options.paginationCurrentPage = 1;

                        });
                    }
                };

                CLC.grid.options = $.extend({}, Helper.UiGridHelper.COMMON_CONFIG ,config)
            };

            CLC.configLog = new LoadData({
                loadUrl : "/getConfiglist",
                getParams : function () {
                    return angular.copy(CLC.configLogSearch.search.keywords);
                },
                onLoaded:function(list,page,result){
                    CLC.grid.update(list,result.data.sum);
                }
            });
            CLC.configLogSearch = new DataSearch({
                onSearch : function () {
                    CLC.configLog.load.reset();
                },
                onReset : function(){
                }
            });

            CLC.configLogSearch.search.reset = function(){
                CLC.configLogSearch.search.keywords = {};
                $(".search-time-input").val("");
            }

            CLC.single = {};
            CLC.single.dealAction = function (config,verb) {
                if ("config_edit"==verb){
                    CLC.editConfig(config.id);
                    return ;
                }
                if ("config_delete"==verb){
                    CLC.deleteConfig(config.id);
                    return ;
                }
                if("log" == verb){
                    ModalService.showLog({
                        objtype :"task_user" ,
                        objid :taskuser.id,
                        // category: "task_user"
                    });
                    return;
                }
            };
            CLC.batch = {};
            CLC.batch.dealAction = function (verb) {
                var selectedList = CLC.batch.getSelectedList();
                var ids = [];
                angular.forEach(selectedList,function (value,key) {
                    ids.push(value.id);
                });
                if ("config_delete"==verb){
                    CLC.deleteConfig(ids);
                    CLC.batch.actions = [];
                    return ;
                }
            }
            CLC.batch.getActionName = function (action) {
                var list = action.split("|");
                return list[0];
            };
            CLC.batch.getActionVerb = function (action) {
                var list = action.split("|");
                return list[1];
            };
            CLC.batch.actions = [];
            CLC.batch.holderActions = ["删除|delete"];
            CLC.batch.calcAction = function () {
                var selectedList = CLC.batch.getSelectedList();
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
                    if ("编辑|config_edit" == commonList[k]){
                        commonList.splice(k, 1);
                    }
                }
                if(commonList && commonList.length){
                    CLC.batch.actions = angular.copy(commonList);
                }else{
                    CLC.batch.actions = [];
                }
            }
            CLC.batch.ciList = [];
            // batch select
            CLC.batch.select = function(ci){
                // 限制 : 同客户 同月份 同还款账户
                if(!ci._select){

                }
                //
                ci._select = !ci._select;

                CLC.batch.calcAction();
            };
            CLC.batch.selectAll = function(){
                if(!CLC.configLog.load.itemList || !CLC.configLog.load.itemList.length){
                    return;
                }
                var isAllSelected = CLC.batch.isAllSelected();
                CLC.configLog.load.itemList.forEach(function(ci){
                    ci._select = !isAllSelected;
                });
                CLC.batch.calcAction();
            };
            CLC.batch.isAllSelected = function(){
                if(!CLC.configLog.load.itemList || !CLC.configLog.load.itemList.length){
                    return false;
                }
                return CLC.configLog.load.itemList.every(function(ci){
                    return ci._select;
                });
            };
            CLC.batch.hasSelected = function(){
                if(!CLC.configLog.load.itemList || !CLC.configLog.load.itemList.length){
                    return false;
                }
                return CLC.configLog.load.itemList.some(function(ci){
                    return ci._select;
                });
            };
            CLC.batch.getSelectedList = function(){
                return CLC.configLog.load.itemList.filter(function(ci){
                    return ci._select;
                });
            };

            CLC.addConfig = function(){
                var addTab = new Tab({
                    name : "config-add",
                    label : "添加",
                    autoopen : true,
                    params : {
                        doLoad: function () {
                            CLC.configLog.load.reset();
                        }
                    }
                });
                TabCtrl.addTab(addTab);
            };

            CLC.editConfig = function(id){
                var editTab = new Tab({
                    name : "config-edit",
                    label : "编辑",
                    autoopen : true,
                    params : {
                        id : id,
                        noclose : true,
                        doLoad: function () {
                            CLC.configLog.load.reset();
                        }
                    }

                });
                TabCtrl.addTab(editTab);
            };

            CLC.deleteConfig = function(id){
                if (!confirm("确定删除？")){
                    return ;
                }
                var rawParams = {
                    id : id
                };
                var params = FormHelper.prepareParams(rawParams);
                $http.post($rootScope.SERVER+"/deleteConfig", params).then(function(response){
                    switch(response.data.ret){
                        case "FAIL":
                            $().message(response.data.data);
                            break;
                        case "SUCCESS":
                            $().message("删除成功");
                            CLC.configLogSearch.search.go();
                            break;
                        default :
                            $().message(MSG_SERVER_ERROR);
                            break;
                    }
                });
                CLC.configLogSearch.search.go();
            };

            CLC.init = function () {
                CLC.grid.init();
            };

            $scope.$watch("CLC.configLogSearch.search.keywords",function () {
                CLC.configLogSearch.search.go();
            },true);

            CLC.init();
            $scope.CLC = CLC;
        }
    });

    wukongApp.component("tConfigAdd",{
        templateUrl : "mgr/configAdd",
        bindings : {
            tab : "=",
        },
        controller : function($scope, $http, $rootScope, Tab, TabCtrl, Helper, $timeout,ModalService){
            var ctrl = this;
            ctrl.$onInit = function(){
                $scope.ARRS = $rootScope.ARRS;
                var tab = ctrl.tab;
                var ConfigAddCenter = {},CAC = ConfigAddCenter;
                CAC.id = Helper.IdService.genId();
                var params = tab.params;
                CAC.info = {};
                // CAC.info.uid = tab.params.uid?tab.params.uid:0;
                CAC.canEdit = true;
                CAC.info.photoConfig = {
                    uploadType : "image",
                    showDelete : function (obj) {
                        return CAC.canEdit  && obj && (obj.path || obj.id);
                    }
                };

                CAC.addConfig = function(){
                    $http.post($rootScope.SERVER+"/addConfig", $("#form-config-add").serialize()).then(function(response){
                        var result = response.data;
                        switch(result.ret){
                            case "FAIL":
                                $().message(result.data);
                                break;
                            case "SUCCESS":
                                $().message("添加成功");
                                params.doLoad();
                                break;
                            default :
                                $().message(MSG_SERVER_ERROR);
                                break;
                        }
                    });
                }

                CAC.pickUser = function () {
                    ModalService.showSuperUserList({
                        onConfirm :function (user) {
                            console.log("confirm");
                            if(user!=null){
                                CAC.info.uid = user.id;
                                CAC.info.user__nickname = user.nickname;
                            }
                        },
                        title : "用户名单"
                    });
                }

                CAC.addConfig = function () {
                    $("#form-config-add" + CAC.id).submit();
                };

                CAC.init = function () {
                    CAC.initForm();
                }

                CAC.initForm = function () {
                    var form =  $("#form-config-add" + CAC.id);
                    var btn = $("#btn-config-add" + CAC.id);
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
                                            params.doLoad();
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
                        $timeout(CAC.initForm,_ID_POLL_INTERVAL);
                    }
                }

                CAC.close = function(){
                    TabCtrl.closeTab(tab);
                };

                CAC.init();

                $scope.CAC = CAC;
            }
        }
    });

    wukongApp.component("tConfigEdit",{
        templateUrl : "mgr/configEdit",
        bindings : {
            tab : "=",
        },
        controller : function($scope, $http, $rootScope, Tab, TabCtrl, Helper, $timeout){
            var ctrl = this;
            ctrl.$onInit = function(){
                var tab = ctrl.tab;
                var params = tab.params;
                $scope.ARRS = $rootScope.ARRS;
                var ConfigEditCenter = {},CEC = ConfigEditCenter;
                CEC.id = Helper.IdService.genId();

                $http.get($rootScope.SERVER+"/getConfig", {params: {
                    id:params.id
                }}).then(function(response){
                    var result = response.data;
                    switch(result.ret){
                        case "FAIL":
                            $().message(result.data);
                            break;
                        case "SUCCESS":
                            CEC.info = result.data;
                            CEC.info.photoConfig = {
                                uploadType : "image",
                                showDelete : function (obj) {
                                    return CEC.canEdit  && obj &&(obj.path || obj.id);
                                }
                            };
                            break;
                        default :
                            $().message(MSG_SERVER_ERROR);
                            break;
                    }
                });

                CEC.editConfig = function () {
                    if (!confirm("确认修改？")){
                        return ;
                    }
                    $("#form-config-edit"+CEC.id).submit();
                };

                CEC.init = function () {
                    CEC.initForm();
                }

                CEC.initForm = function () {
                    var form =  $("#form-config-edit" + CEC.id);
                    var btn = $("#btn-config-edit" + CEC.id);
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
                                            CEC.canEdit = true;
                                            params.doLoad();
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
                        $timeout(CEC.initForm,_ID_POLL_INTERVAL);
                    }
                }

                CEC.init();

                CEC.close = function(){
                    TabCtrl.closeTab(tab);
                };

                $scope.CEC = CEC;
            }
        }
    });

    wukongApp.controller("ConfigController",function($scope,Tab,TabCtrl){
        var ConfigHomeCenter = {},CHC = ConfigHomeCenter;

        CHC.initList = function(){
            var listTab = new Tab({
                name : "config-list",
                label : "列表",
                closeable : false,
            });
            TabCtrl.addTab(listTab);
        };
        CHC.init = function(){
            CHC.initList();
        };

        $scope.TC = TabCtrl;
        $scope.CHC = CHC;

        CHC.init();
    });
});