<?
$ctrl = $page;
$samplectrl = strtoupper($page[0]);
$upperctrl = strtoupper($page);
$lowerctrl = lcfirst($page);
$oldctrl = ($page);
$linectrl = Utilities::toUnderScore($page);
$camelctrl = ucfirst($page);
echo <<<EOF
\$(function () {
    var wukongApp = angular.module(_MOUDLE_NAME);
    if(!wukongApp){
        console.e("no app");
        return;
    }
    wukongApp.component("t{$camelctrl}List",{
        templateUrl : "mgr/{$lowerctrl}List",
        controller : function(\$scope,\$http,Tab,TabCtrl,\$rootScope,LoadData,DataSearch,TimeSelect,FormHelper,Helper,\$timeout,uiGridConstants, uiGridPaginationService,ModalService,ScrollLoad,\$window,DelayService){

            \$(".modal").one("show.bs.modal",function(){
                \$(this).appendTo("body");
            });

            \$scope._LS = \$rootScope._LS;
            \$scope.ARRS = \$rootScope.ARRS;
            var {$camelctrl}ListCenter = {},{$samplectrl}LC = {$camelctrl}ListCenter;
            {$samplectrl}LC.id = Helper.IdService.genId();
            {$samplectrl}LC.grid = {};
            {$samplectrl}LC.grid.options = {};
            {$samplectrl}LC.grid.update  = function (list,sum) {
                // if({$samplectrl}LC.grid.options){
                {$samplectrl}LC.grid.options.totalItems = sum;
                {$samplectrl}LC.grid.options.data = list;
                // }
            };
            {$samplectrl}LC.grid.init = function () {
                var config = {
                    useExternalFiltering: true,
                    enableColumnResizing: true,
                    enableCellEdit: false,
                    enablePinning: true,
                    disableCancelFilterButton: false,
                    data: {$samplectrl}LC.{$lowerctrl}Log.load.itemList,
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
                            timer : "grid.appScope.{$samplectrl}LC.{$lowerctrl}LogSearch.search.keywords.created"
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
                        //     cellTemplate: '<button type="button" class="btn btn-link" ng-click="grid.appScope.{$samplectrl}LC.single.dealAction(row.entity,grid.appScope.{$samplectrl}LC.batch.getActionVerb(action),action);" ng-repeat="action in row.entity.actions">{{grid.appScope.{$samplectrl}LC.batch.getActionName(action)}}</button>'
                        // },
                        // Helper.UiGridHelper.newLog({
                        //     click : "grid.appScope.{$samplectrl}LC.single.dealAction(row.entity,\'log\');"
                        // })
                    ],
                    //---------------api---------------------
                    onRegisterApi: function (gridApi) {
                        \$scope.gridApi = gridApi;
                        //分页按钮事件
                        gridApi.pagination.on.paginationChanged(\$scope, function (newPage, pageSize) {
                            console.log("case4");
                            console.log([newPage, pageSize]);
                            var params = {};
                            // var vipstart = BLC.bdLogSearch.search.keywords.vipstart;
                            // var vipend = BLC.bdLogSearch.search.keywords.vipend;
                            // angular.forEach(\$scope.{$samplectrl}LC.grid.options.columnDefs, function (col, key) {
                            //     if (col.filter) {
                            //         params[col.field] = col.filter.term;
                            //     }
                            // });
                            // params.vipstart = vipstart;
                            // params.vipend = vipend;
                            // BLC.bdLogSearch.search.keywords = angular.copy(params);

                            {$samplectrl}LC.{$lowerctrl}Log.load.pagesize = pageSize;
                            {$samplectrl}LC.{$lowerctrl}Log.load.getPage(newPage);
                        });
                        gridApi.core.on.filterChanged(\$scope, function (a, b) {
                            console.log("case3");

                            var params = {};
                            var created = {$samplectrl}LC.{$lowerctrl}LogSearch.search.keywords.created;
                            // var vipend = BLC.bdLogSearch.search.keywords.vipend;
                            angular.forEach(\$scope.{$samplectrl}LC.grid.options.columnDefs, function (col, key) {
                                if (col.filter) {
                                    params[col.field] = col.filter.term;
                                }
                            });
                            params.created = created;
                            // params.vipend = vipend;
                            {$samplectrl}LC.{$lowerctrl}LogSearch.search.keywords = angular.copy(params);

                            {$samplectrl}LC.{$lowerctrl}LogSearch.search.go();

                            \$scope.{$samplectrl}LC.grid.options.paginationCurrentPage = 1;

                        });
                    }
                };

                {$samplectrl}LC.grid.options = \$.extend({}, Helper.UiGridHelper.COMMON_CONFIG ,config)
            };

            {$samplectrl}LC.{$lowerctrl}Log = new LoadData({
                pagesize :5,
                loadMode:"scroll",
                loadUrl : "/get{$camelctrl}List",
                getParams : function () {
                    var params =  angular.copy({$samplectrl}LC.{$lowerctrl}LogSearch.search.keywords);
                    params.mode = {$samplectrl}LC.mode;
                    return params;
                },
                onLoaded:function(list,page,result){
                    {$samplectrl}LC.grid.update(list,result.data.sum);
                }
            });
            {$samplectrl}LC.resetList = _.debounce(
                function () {
                    console.log("done");
                    {$samplectrl}LC.{$lowerctrl}LogSearch.search.go();
                    return "111";
                },
                500
            );

            {$samplectrl}LC.{$lowerctrl}LogSearch = new DataSearch({
                onSearch : function () {
                    {$samplectrl}LC.{$lowerctrl}Log.load.reset();
                },
                onReset : function(){
                }
            });

            {$samplectrl}LC.{$lowerctrl}LogSearch.search.keywords.period = "";
            {$samplectrl}LC.mode = "period";
            {$samplectrl}LC.openPeriod  = function () {
                {$samplectrl}LC.mode = "period";
            };
            {$samplectrl}LC.date = {};
            {$samplectrl}LC.date.getDayClass = function(data) {
                var date = data.date,
                    mode = data.mode;
                if (mode === 'day') {
                    var dayToCheck = new Date(date).setHours(0,0,0,0);

                    // for (var i = 0; i < \$scope.{$lowerctrl}s.length; i++) {
                    //     var currentDay = new Date(\$scope.{$lowerctrl}s[i].date).setHours(0,0,0,0);
                    //
                    //     if (dayToCheck === currentDay) {
                    //         return \$scope.{$lowerctrl}s[i].status;
                    //     }
                    // }
                }

                return '';
            }
            // {$samplectrl}LC.{$lowerctrl}LogSearch.search.keywords = new Date();
            {$samplectrl}LC.date.current_date = null;
            {$samplectrl}LC.date.today = function () {
                {$samplectrl}LC.date.current_date = new Date();
            };
            {$samplectrl}LC.date.clear = function () {
                {$samplectrl}LC.date.current_date = null;
            };
            {$samplectrl}LC.date.disabled = function(data) {
                if(true){
                    return false;
                }
                var date = data.date,
                    mode = data.mode;
                return mode === 'day' && (date.getDay() === 0 || date.getDay() === 6);
            };
            {$samplectrl}LC.date.dateOptions = {
                dateDisabled: {$samplectrl}LC.date.disabled,
                formatYear: 'yy',
                maxDate: new Date(2020, 5, 22),
                minDate: new Date(1980,1,1),
                startingDay: 1
            };
            {$samplectrl}LC.date.opened = false;
            {$samplectrl}LC.date.format = "yyyy-MM-dd";
            {$samplectrl}LC.date.open = function () {
                {$samplectrl}LC.mode = "date";
                {$samplectrl}LC.date.opened = true;
            }
            {$samplectrl}LC.date.init = function () {

            };

            \$scope.inlineOptions = {
                customClass: {$samplectrl}LC.date.getDayClass,
                minDate: new Date(),
                showWeeks: true
            };

            \$scope.setDate = function(year, month, day) {
                \$scope.dt = new Date(year, month, day);
            };

            {$samplectrl}LC.date.altInputFormats = ['M!/d!/yyyy'];

            // var tomorrow = new Date();
            // tomorrow.setDate(tomorrow.getDate() + 1);
            // var afterTomorrow = new Date();
            // afterTomorrow.setDate(tomorrow.getDate() + 1);
            // \$scope.{$lowerctrl}s = [
            //     {
            //         date: tomorrow,
            //         status: 'full'
            //     },
            //     {
            //         date: afterTomorrow,
            //         status: 'partially'
            //     }
            // ];

            {$samplectrl}LC.{$lowerctrl}LogSearch.search.reset = function(){
                {$samplectrl}LC.{$lowerctrl}LogSearch.search.keywords = {};
                \$(".search-time-input").val("");
            }

            {$samplectrl}LC.single = {};
            {$samplectrl}LC.single.dealAction = function ({$lowerctrl},verb) {
                if ("{$lowerctrl}_edit"==verb){
                    {$samplectrl}LC.edit{$camelctrl}({$lowerctrl}.id);
                    return ;
                }
                if ("{$lowerctrl}_delete"==verb){
                    {$samplectrl}LC.delete{$camelctrl}({$lowerctrl}.id);
                    return ;
                }
            };
            {$samplectrl}LC.batch = {};
            {$samplectrl}LC.batch.dealAction = function (verb) {
                var selectedList = {$samplectrl}LC.batch.getSelectedList();
                var ids = [];
                angular.forEach(selectedList,function (value,key) {
                    ids.push(value.id);
                });
                if ("{$lowerctrl}_delete"==verb){
                    {$samplectrl}LC.delete{$camelctrl}(ids);
                    {$samplectrl}LC.batch.actions = [];
                    return ;
                }
            }
            {$samplectrl}LC.batch.getActionName = function (action) {
                var list = action.split("|");
                return list[0];
            };
            {$samplectrl}LC.batch.getActionVerb = function (action) {
                var list = action.split("|");
                return list[1];
            };
            {$samplectrl}LC.batch.actions = [];
            {$samplectrl}LC.batch.holderActions = ["删除|delete"];
            {$samplectrl}LC.batch.calcAction = function () {
                var selectedList = {$samplectrl}LC.batch.getSelectedList();
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
                    if ("编辑|{$lowerctrl}_edit" == commonList[k]){
                        commonList.splice(k, 1);
                    }
                }
                if(commonList && commonList.length){
                    {$samplectrl}LC.batch.actions = angular.copy(commonList);
                }else{
                    {$samplectrl}LC.batch.actions = [];
                }
            }
            {$samplectrl}LC.batch.ciList = [];
            // batch select
            {$samplectrl}LC.batch.select = function(ci){
                // 限制 : 同客户 同月份 同还款账户
                if(!ci._select){

                }
                //
                ci._select = !ci._select;

                {$samplectrl}LC.batch.calcAction();
            };
            {$samplectrl}LC.batch.selectAll = function(){
                if(!{$samplectrl}LC.{$lowerctrl}Log.load.itemList || !{$samplectrl}LC.{$lowerctrl}Log.load.itemList.length){
                    return;
                }
                var isAllSelected = {$samplectrl}LC.batch.isAllSelected();
                {$samplectrl}LC.{$lowerctrl}Log.load.itemList.forEach(function(ci){
                    ci._select = !isAllSelected;
                });
                {$samplectrl}LC.batch.calcAction();
            };
            {$samplectrl}LC.batch.isAllSelected = function(){
                if(!{$samplectrl}LC.{$lowerctrl}Log.load.itemList || !{$samplectrl}LC.{$lowerctrl}Log.load.itemList.length){
                    return false;
                }
                return {$samplectrl}LC.{$lowerctrl}Log.load.itemList.every(function(ci){
                    return ci._select;
                });
            };
            {$samplectrl}LC.batch.hasSelected = function(){
                if(!{$samplectrl}LC.{$lowerctrl}Log.load.itemList || !{$samplectrl}LC.{$lowerctrl}Log.load.itemList.length){
                    return false;
                }
                return {$samplectrl}LC.{$lowerctrl}Log.load.itemList.some(function(ci){
                    return ci._select;
                });
            };
            {$samplectrl}LC.batch.getSelectedList = function(){
                return {$samplectrl}LC.{$lowerctrl}Log.load.itemList.filter(function(ci){
                    return ci._select;
                });
            };

            {$samplectrl}LC.add{$camelctrl} = function(){
                var addTab = new Tab({
                    name : "{$linectrl}-add",
                    label : "添加",
                    autoopen : true,
                    params : {
                        resetList:function(){

                            {$samplectrl}LC.resetList();
                        }
                    }
                });
                TabCtrl.addTab(addTab);
            };

            {$samplectrl}LC.edit{$camelctrl} = function(id){
                var editTab = new Tab({
                    name : "{$linectrl}-edit",
                    label : "编辑",
                    autoopen : true,
                    params : {
                        id : id,
                        noclose : true,
                        resetList:function(){
                            {$samplectrl}LC.resetList();
                        }
                    }
                });
                TabCtrl.addTab(editTab);
            };

            {$samplectrl}LC.delete{$camelctrl} = function(id){
                if (!confirm("确定删除？")){
                    return ;
                }
                var rawParams = {
                    id : id
                };
                var params = FormHelper.prepareParams(rawParams);
                \$http.post(\$rootScope.SERVER+"/delete{$camelctrl}", params).then(function(response){
                    switch(response.data.ret){
                        case "FAIL":
                            \$().message(response.data.data);
                            break;
                        case "SUCCESS":
                            \$().message("删除成功");
                            {$samplectrl}LC.{$lowerctrl}LogSearch.search.go();
                            break;
                        default :
                            \$().message(MSG_SERVER_ERROR);
                            break;
                    }
                });
                {$samplectrl}LC.{$lowerctrl}LogSearch.search.go();
            };
            {$samplectrl}LC.getHeight = function () {
                var winowHeight = \$window.innerHeight; //获取窗口高度
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
            {$samplectrl}LC.init = function () {


                new DelayService().init({
                    selector : "#{$linectrl}-con-"+{$samplectrl}LC.id,
                    done:function () {
                        ScrollLoad.init({
                            parent: \$("#{$linectrl}-con-"+{$samplectrl}LC.id),
                            load : function(){
                                {$samplectrl}LC.{$lowerctrl}Log.load.getPage({$samplectrl}LC.{$lowerctrl}Log.load.page + 1);
                            }
                        });
                    }
                })
            };

            \$scope.\$watch("{$samplectrl}LC.{$lowerctrl}LogSearch.search.keywords",function () {
                console.log("try do");
                {$samplectrl}LC.resetList();
            },true);
            \$scope.\$watch("{$samplectrl}LC.date.current_date",function (newVal) {
                {$samplectrl}LC.{$lowerctrl}LogSearch.search.keywords.date = {$samplectrl}LC.date.current_date ? {$samplectrl}LC.date.current_date.format("yyyy-MM-dd"):"";
            });
            {$samplectrl}LC.init();
            \$scope.{$samplectrl}LC = {$samplectrl}LC;
        }
    });

    wukongApp.component("t{$camelctrl}Add",{
        templateUrl : "mgr/{$lowerctrl}Add",
        bindings : {
            tab : "=",
        },
        controller : function(\$scope, \$http, \$rootScope, Tab, TabCtrl, Helper, \$timeout){
            var ctrl = this;
            ctrl.\$onInit = function(){
                var tab = ctrl.tab;
                var {$camelctrl}AddCenter = {},{$samplectrl}AC = {$camelctrl}AddCenter;
                {$samplectrl}AC.id = Helper.IdService.genId();
                \$scope.ARRS = \$rootScope.ARRS;
                {$samplectrl}AC.info = {};
                {$samplectrl}AC.canEdit = true;
                {$samplectrl}AC.info.photoConfig = {
                    uploadType : "image",
                    showDelete : function (obj) {
                        return {$samplectrl}AC.canEdit  && obj && (obj.path || obj.id);
                    }
                };

                {$samplectrl}AC.add{$camelctrl} = function(){
                    \$http.post(\$rootScope.SERVER+"/add{$camelctrl}", \$("#form-{$linectrl}-add").serialize()).then(function(response){
                        var result = response.data;
                        switch(result.ret){
                            case "FAIL":
                                \$().message(result.data);
                                break;
                            case "SUCCESS":
                                \$().message("添加成功");

                                break;
                            default :
                                \$().message(MSG_SERVER_ERROR);
                                break;
                        }
                    });
                }

                {$samplectrl}AC.add{$camelctrl} = function () {
                    \$("#form-{$linectrl}-add" + {$samplectrl}AC.id).submit();
                };

                {$samplectrl}AC.init = function () {
                    {$samplectrl}AC.initForm();
                }

                {$samplectrl}AC.initForm = function () {
                    var form =  \$("#form-{$linectrl}-add" + {$samplectrl}AC.id);
                    var btn = \$("#btn-{$linectrl}-add" + {$samplectrl}AC.id);
                    if(form && form.length && btn && btn.length){
                        form.submit(function () {
                            var form = \$(this);
                            form.ajaxSubmit({
                                type : "post",
                                \$http : \$http,
                                btn : btn,
                                done : function(result){
                                    switch(result.ret){
                                        case "FAIL":
                                            \$().message(result.data);
                                            break;
                                        case "SUCCESS":
                                            \$().message("添加成功");
                                            tab.params.resetList();
                                            TabCtrl.closeTab(tab);
                                            break;
                                        default:
                                            \$().message(MSG_SERVER_ERROR);
                                            break;
                                    }
                                }
                            });
                            return false;
                        });
                    }else{
                        \$timeout({$samplectrl}AC.initForm,_ID_POLL_INTERVAL);
                    }
                }

                {$samplectrl}AC.close = function(){
                    TabCtrl.closeTab(tab);
                };

                {$samplectrl}AC.init();

                \$scope.{$samplectrl}AC = {$samplectrl}AC;
            }
        }
    });

    wukongApp.component("t{$camelctrl}Edit",{
        templateUrl : "mgr/{$lowerctrl}Edit",
        bindings : {
            tab : "=",
        },
        controller : function(\$scope, \$http, \$rootScope, Tab, TabCtrl, Helper, \$timeout){
            var ctrl = this;
            ctrl.\$onInit = function(){
                \$scope.ARRS = \$rootScope.ARRS;
                var tab = ctrl.tab;
                var params = tab.params;
                var {$camelctrl}EditCenter = {},{$samplectrl}EC = {$camelctrl}EditCenter;
                {$samplectrl}EC.id = Helper.IdService.genId();

                \$http.get(\$rootScope.SERVER+"/get{$camelctrl}", {params: params}).then(function(response){
                    var result = response.data;
                    switch(result.ret){
                        case "FAIL":
                            \$().message(result.data);
                            break;
                        case "SUCCESS":
                            {$samplectrl}EC.info = result.data;
                            {$samplectrl}EC.info.photoConfig = {
                                uploadType : "image",
                                showDelete : function (obj) {
                                    return {$samplectrl}EC.canEdit  && obj &&(obj.path || obj.id);
                                }
                            };
                            break;
                        default :
                            \$().message(MSG_SERVER_ERROR);
                            break;
                    }
                });

                {$samplectrl}EC.edit{$camelctrl} = function () {
                    if (!confirm("确认修改？")){
                        return ;
                    }
                    \$("#form-{$linectrl}-edit"+{$samplectrl}EC.id).submit();
                };

                {$samplectrl}EC.init = function () {
                    {$samplectrl}EC.initForm();
                }

                {$samplectrl}EC.initForm = function () {
                    var form =  \$("#form-{$linectrl}-edit" + {$samplectrl}EC.id);
                    var btn = \$("#btn-{$linectrl}-edit" + {$samplectrl}EC.id);
                    if(form && form.length && btn && btn.length){
                        form.submit(function () {
                            var form = \$(this);
                            form.ajaxSubmit({
                                type : "post",
                                \$http : \$http,
                                btn : btn,
                                done : function(result){
                                    switch(result.ret){
                                        case "FAIL":
                                            \$().message(result.data);
                                            break;
                                        case "SUCCESS":
                                            \$().message("编辑成功");
                                            {$samplectrl}EC.canEdit = true;
                                            tab.params.resetList();
                                            TabCtrl.closeTab(tab);
                                            break;
                                        default:
                                            \$().message(MSG_SERVER_ERROR);
                                            break;
                                    }
                                }
                            });
                            return false;
                        });
                    }else{
                        \$timeout({$samplectrl}EC.initForm,_ID_POLL_INTERVAL);
                    }
                }

                {$samplectrl}EC.init();

                {$samplectrl}EC.close = function(){
                    TabCtrl.closeTab(tab);
                };

                \$scope.{$samplectrl}EC = {$samplectrl}EC;
            }
        }
    });

    wukongApp.controller("{$camelctrl}Controller",function(\$scope,Tab,TabCtrl){
        var {$camelctrl}HomeCenter = {},{$samplectrl}HC = {$camelctrl}HomeCenter;

        {$samplectrl}HC.initList = function(){
            var listTab = new Tab({
                name : "{$linectrl}-list",
                label : "列表",
                closeable : false,
            });
            TabCtrl.addTab(listTab);
        };
        {$samplectrl}HC.init = function(){
            {$samplectrl}HC.initList();
        };

        \$scope.TC = TabCtrl;
        \$scope.{$samplectrl}HC = {$samplectrl}HC;

        {$samplectrl}HC.init();
    });
});
EOF;
