$(function () {
    var muumApp = angular.module(_MOUDLE_NAME);
    if(!muumApp){
        console.e("no app");
        return;
    }
    muumApp.component("tEventList",{
        templateUrl : "mgr/eventList",
        controller : function($scope,$http,Tab,TabCtrl,$rootScope,LoadData,DataSearch,TimeSelect,FormHelper,Helper,$timeout,uiGridConstants, uiGridPaginationService,ModalService,ScrollLoad,$window,DelayService){

            $(".modal").one("show.bs.modal",function(){
                $(this).appendTo("body");
            });

            $scope._LS = $rootScope._LS;
            $scope.ARRS = $rootScope.ARRS;
            var EventListCenter = {},ELC = EventListCenter;
            ELC.id = Helper.IdService.genId();
            ELC.grid = {};
            ELC.grid.options = {};
            ELC.grid.update  = function (list,sum) {
                // if(ELC.grid.options){
                ELC.grid.options.totalItems = sum;
                ELC.grid.options.data = list;
                // }
            };
            ELC.grid.init = function () {
                var config = {
                    useExternalFiltering: true,
                    enableColumnResizing: true,
                    enableCellEdit: false,
                    enablePinning: true,
                    disableCancelFilterButton: false,
                    data: ELC.eventLog.load.itemList,
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
                            timer : "grid.appScope.ELC.eventLogSearch.search.keywords.created"
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
                        //     cellTemplate: '<button type="button" class="btn btn-link" ng-click="grid.appScope.ELC.single.dealAction(row.entity,grid.appScope.ELC.batch.getActionVerb(action),action);" ng-repeat="action in row.entity.actions">{{grid.appScope.ELC.batch.getActionName(action)}}</button>'
                        // },
                        // Helper.UiGridHelper.newLog({
                        //     click : "grid.appScope.ELC.single.dealAction(row.entity,\'log\');"
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
                            // angular.forEach($scope.ELC.grid.options.columnDefs, function (col, key) {
                            //     if (col.filter) {
                            //         params[col.field] = col.filter.term;
                            //     }
                            // });
                            // params.vipstart = vipstart;
                            // params.vipend = vipend;
                            // BLC.bdLogSearch.search.keywords = angular.copy(params);

                            ELC.eventLog.load.pagesize = pageSize;
                            ELC.eventLog.load.getPage(newPage);
                        });
                        gridApi.core.on.filterChanged($scope, function (a, b) {
                            console.log("case3");

                            var params = {};
                            var created = ELC.eventLogSearch.search.keywords.created;
                            // var vipend = BLC.bdLogSearch.search.keywords.vipend;
                            angular.forEach($scope.ELC.grid.options.columnDefs, function (col, key) {
                                if (col.filter) {
                                    params[col.field] = col.filter.term;
                                }
                            });
                            params.created = created;
                            // params.vipend = vipend;
                            ELC.eventLogSearch.search.keywords = angular.copy(params);

                            ELC.eventLogSearch.search.go();

                            $scope.ELC.grid.options.paginationCurrentPage = 1;

                        });
                    }
                };

                ELC.grid.options = $.extend({}, Helper.UiGridHelper.COMMON_CONFIG ,config)
            };

            ELC.eventLog = new LoadData({
                pagesize :5,
                loadMode:"scroll",
                loadUrl : "/getEventList",
                getParams : function () {
                    var params =  angular.copy(ELC.eventLogSearch.search.keywords);
                    params.mode = ELC.mode;
                    return params;
                },
                onLoaded:function(list,page,result){
                    ELC.grid.update(list,result.data.sum);
                }
            });
            ELC.resetList = _.debounce(
                function () {
                    console.log("done");
                    ELC.eventLogSearch.search.go();
                    return "111";
                },
                500
            );

            ELC.eventLogSearch = new DataSearch({
                onSearch : function () {
                    ELC.eventLog.load.reset();
                },
                onReset : function(){
                }
            });

            ELC.eventLogSearch.search.keywords.period = "";
            ELC.mode = "period";
            ELC.openPeriod  = function () {
                ELC.mode = "period";
            };
            ELC.date = {};
            ELC.date.getDayClass = function(data) {
                var date = data.date,
                    mode = data.mode;
                if (mode === 'day') {
                    var dayToCheck = new Date(date).setHours(0,0,0,0);

                    // for (var i = 0; i < $scope.events.length; i++) {
                    //     var currentDay = new Date($scope.events[i].date).setHours(0,0,0,0);
                    //
                    //     if (dayToCheck === currentDay) {
                    //         return $scope.events[i].status;
                    //     }
                    // }
                }

                return '';
            }
            // ELC.eventLogSearch.search.keywords = new Date();
            ELC.date.current_date = null;
            ELC.date.today = function () {
                ELC.date.current_date = new Date();
            };
            ELC.date.clear = function () {
                ELC.date.current_date = null;
            };
            ELC.date.disabled = function(data) {
                if(true){
                    return false;
                }
                var date = data.date,
                    mode = data.mode;
                return mode === 'day' && (date.getDay() === 0 || date.getDay() === 6);
            };
            ELC.date.dateOptions = {
                dateDisabled: ELC.date.disabled,
                formatYear: 'yy',
                maxDate: new Date(2020, 5, 22),
                minDate: new Date(1980,1,1),
                startingDay: 1
            };
            ELC.date.opened = false;
            ELC.date.format = "yyyy-MM-dd";
            ELC.date.open = function () {
                ELC.mode = "date";
                ELC.date.opened = true;
            }
            ELC.date.init = function () {

            };

            $scope.inlineOptions = {
                customClass: ELC.date.getDayClass,
                minDate: new Date(),
                showWeeks: true
            };

            $scope.setDate = function(year, month, day) {
                $scope.dt = new Date(year, month, day);
            };

            ELC.date.altInputFormats = ['M!/d!/yyyy'];

            // var tomorrow = new Date();
            // tomorrow.setDate(tomorrow.getDate() + 1);
            // var afterTomorrow = new Date();
            // afterTomorrow.setDate(tomorrow.getDate() + 1);
            // $scope.events = [
            //     {
            //         date: tomorrow,
            //         status: 'full'
            //     },
            //     {
            //         date: afterTomorrow,
            //         status: 'partially'
            //     }
            // ];

            ELC.eventLogSearch.search.reset = function(){
                ELC.eventLogSearch.search.keywords = {};
                $(".search-time-input").val("");
            }

            ELC.single = {};
            ELC.single.dealAction = function (event,verb) {
                if ("event_edit"==verb){
                    ELC.editEvent(event.id);
                    return ;
                }
                if ("event_delete"==verb){
                    ELC.deleteEvent(event.id);
                    return ;
                }
            };
            ELC.batch = {};
            ELC.batch.dealAction = function (verb) {
                var selectedList = ELC.batch.getSelectedList();
                var ids = [];
                angular.forEach(selectedList,function (value,key) {
                    ids.push(value.id);
                });
                if ("event_delete"==verb){
                    ELC.deleteEvent(ids);
                    ELC.batch.actions = [];
                    return ;
                }
            }
            ELC.batch.getActionName = function (action) {
                var list = action.split("|");
                return list[0];
            };
            ELC.batch.getActionVerb = function (action) {
                var list = action.split("|");
                return list[1];
            };
            ELC.batch.actions = [];
            ELC.batch.holderActions = ["删除|delete"];
            ELC.batch.calcAction = function () {
                var selectedList = ELC.batch.getSelectedList();
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
                    if ("编辑|event_edit" == commonList[k]){
                        commonList.splice(k, 1);
                    }
                }
                if(commonList && commonList.length){
                    ELC.batch.actions = angular.copy(commonList);
                }else{
                    ELC.batch.actions = [];
                }
            }
            ELC.batch.ciList = [];
            // batch select
            ELC.batch.select = function(ci){
                // 限制 : 同客户 同月份 同还款账户
                if(!ci._select){

                }
                //
                ci._select = !ci._select;

                ELC.batch.calcAction();
            };
            ELC.batch.selectAll = function(){
                if(!ELC.eventLog.load.itemList || !ELC.eventLog.load.itemList.length){
                    return;
                }
                var isAllSelected = ELC.batch.isAllSelected();
                ELC.eventLog.load.itemList.forEach(function(ci){
                    ci._select = !isAllSelected;
                });
                ELC.batch.calcAction();
            };
            ELC.batch.isAllSelected = function(){
                if(!ELC.eventLog.load.itemList || !ELC.eventLog.load.itemList.length){
                    return false;
                }
                return ELC.eventLog.load.itemList.every(function(ci){
                    return ci._select;
                });
            };
            ELC.batch.hasSelected = function(){
                if(!ELC.eventLog.load.itemList || !ELC.eventLog.load.itemList.length){
                    return false;
                }
                return ELC.eventLog.load.itemList.some(function(ci){
                    return ci._select;
                });
            };
            ELC.batch.getSelectedList = function(){
                return ELC.eventLog.load.itemList.filter(function(ci){
                    return ci._select;
                });
            };

            ELC.addEvent = function(){
                var addTab = new Tab({
                    name : "event-add",
                    label : "添加",
                    autoopen : true,
                    params : {
                        resetList:function(){

                            ELC.resetList();
                        }
                    }
                });
                TabCtrl.addTab(addTab);
            };

            ELC.editEvent = function(id){
                var editTab = new Tab({
                    name : "event-edit",
                    label : "编辑",
                    autoopen : true,
                    params : {
                        id : id,
                        noclose : true,
                        resetList:function(){
                            ELC.resetList();
                        }
                    }
                });
                TabCtrl.addTab(editTab);
            };

            ELC.deleteEvent = function(id){
                if (!confirm("确定删除？")){
                    return ;
                }
                var rawParams = {
                    id : id
                };
                var params = FormHelper.prepareParams(rawParams);
                $http.post($rootScope.SERVER+"/deleteEvent", params).then(function(response){
                    switch(response.data.ret){
                        case "FAIL":
                            $().message(response.data.data);
                            break;
                        case "SUCCESS":
                            $().message("删除成功");
                            ELC.eventLogSearch.search.go();
                            break;
                        default :
                            $().message(MSG_SERVER_ERROR);
                            break;
                    }
                });
                ELC.eventLogSearch.search.go();
            };
            ELC.getHeight = function () {
                var winowHeight = $window.innerHeight; //获取窗口高度
                var content_padding = 6*2;
                var header_height = 50;
                var tab_height  = 42;
                var page_padding = 10*2;
                var action_height = 32;
                var action_padding = 21;
                var scorll_con_margin = 10;
                var fix_padding = 10;

                return winowHeight - content_padding - header_height - tab_height - page_padding - action_height - action_padding - scorll_con_margin - fix_padding;
            };
            ELC.init = function () {


                new DelayService().init({
                    selector : "#event-con-"+ELC.id,
                    done:function () {
                        ScrollLoad.init({
                            parent: $("#event-con-"+ELC.id),
                            load : function(){
                                ELC.eventLog.load.getPage(ELC.eventLog.load.page + 1);
                            }
                        });
                    }
                })
            };

            $scope.$watch("ELC.eventLogSearch.search.keywords",function () {
                console.log("try do");
                ELC.resetList();
            },true);
            $scope.$watch("ELC.date.current_date",function (newVal) {
                ELC.eventLogSearch.search.keywords.date = ELC.date.current_date ? ELC.date.current_date.format("yyyy-MM-dd"):"";
            });
            ELC.init();
            $scope.ELC = ELC;
        }
    });

    muumApp.component("tEventAdd",{
        templateUrl : "mgr/eventAdd",
        bindings : {
            tab : "=",
        },
        controller : function($scope, $http, $rootScope, Tab, TabCtrl, Helper, $timeout){
            var ctrl = this;
            ctrl.$onInit = function(){
                var tab = ctrl.tab;
                var EventAddCenter = {},EAC = EventAddCenter;
                EAC.id = Helper.IdService.genId();
                $scope.ARRS = $rootScope.ARRS;
                EAC.info = {};
                EAC.canEdit = true;
                EAC.info.photoConfig = {
                    uploadType : "image",
                    showDelete : function (obj) {
                        return EAC.canEdit  && obj && (obj.path || obj.id);
                    }
                };

                EAC.addEvent = function(){
                    $http.post($rootScope.SERVER+"/addEvent", $("#form-event-add").serialize()).then(function(response){
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

                EAC.addEvent = function () {
                    $("#form-event-add" + EAC.id).submit();
                };

                EAC.init = function () {
                    EAC.initForm();
                }

                EAC.initForm = function () {
                    var form =  $("#form-event-add" + EAC.id);
                    var btn = $("#btn-event-add" + EAC.id);
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
                                            tab.params.resetList();
                                            TabCtrl.closeTab(tab);
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
                        $timeout(EAC.initForm,_ID_POLL_INTERVAL);
                    }
                }

                EAC.close = function(){
                    TabCtrl.closeTab(tab);
                };

                EAC.init();

                $scope.EAC = EAC;
            }
        }
    });

    muumApp.component("tEventEdit",{
        templateUrl : "mgr/eventEdit",
        bindings : {
            tab : "=",
        },
        controller : function($scope, $http, $rootScope, Tab, TabCtrl, Helper, $timeout){
            var ctrl = this;
            ctrl.$onInit = function(){
                $scope.ARRS = $rootScope.ARRS;
                var tab = ctrl.tab;
                var params = tab.params;
                var EventEditCenter = {},EEC = EventEditCenter;
                EEC.id = Helper.IdService.genId();

                $http.get($rootScope.SERVER+"/getEvent", {params: params}).then(function(response){
                    var result = response.data;
                    switch(result.ret){
                        case "FAIL":
                            $().message(result.data);
                            break;
                        case "SUCCESS":
                            EEC.info = result.data;
                            EEC.info.photoConfig = {
                                uploadType : "image",
                                showDelete : function (obj) {
                                    return EEC.canEdit  && obj &&(obj.path || obj.id);
                                }
                            };
                            break;
                        default :
                            $().message(MSG_SERVER_ERROR);
                            break;
                    }
                });

                EEC.editEvent = function () {
                    if (!confirm("确认修改？")){
                        return ;
                    }
                    $("#form-event-edit"+EEC.id).submit();
                };

                EEC.init = function () {
                    EEC.initForm();
                }

                EEC.initForm = function () {
                    var form =  $("#form-event-edit" + EEC.id);
                    var btn = $("#btn-event-edit" + EEC.id);
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
                                            EEC.canEdit = true;
                                            tab.params.resetList();
                                            TabCtrl.closeTab(tab);
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
                        $timeout(EEC.initForm,_ID_POLL_INTERVAL);
                    }
                }

                EEC.init();

                EEC.close = function(){
                    TabCtrl.closeTab(tab);
                };

                $scope.EEC = EEC;
            }
        }
    });

    muumApp.controller("EventController",function($scope,Tab,TabCtrl){
        var EventHomeCenter = {},EHC = EventHomeCenter;

        EHC.initList = function(){
            var listTab = new Tab({
                name : "event-list",
                label : "列表",
                closeable : false,
            });
            TabCtrl.addTab(listTab);
        };
        EHC.init = function(){
            EHC.initList();
        };

        $scope.TC = TabCtrl;
        $scope.EHC = EHC;

        EHC.init();
    });
});