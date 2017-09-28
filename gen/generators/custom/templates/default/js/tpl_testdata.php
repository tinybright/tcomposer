<?
$ctrl = $page;
$samplectrl = strtoupper($page[0]);
$upperctrl = strtoupper($page);
$lowerctrl = strtolower($page);
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
        controller : function(\$scope,\$http,Tab,TabCtrl,\$rootScope,LoadData,DataSearch,TimeSelect,FormHelper,Helper,\$timeout){

            \$(".modal").one("show.bs.modal",function(){
                \$(this).appendTo("body");
            });

            \$scope._LS = \$rootScope._LS;

            var {$camelctrl}ListCenter = {},{$samplectrl}LC = {$camelctrl}ListCenter;

            {$samplectrl}LC.{$lowerctrl}Log = new LoadData({
                loadUrl : "/get{$lowerctrl}list",
                getParams : function () {
                    return angular.copy({$samplectrl}LC.{$lowerctrl}LogSearch.search.keywords);
                }
            });
            {$samplectrl}LC.{$lowerctrl}LogSearch = new DataSearch({
                onSearch : function () {
                    {$samplectrl}LC.{$lowerctrl}Log.load.reset();
                },
                onReset : function(){
                }
            });

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
                    name : "{$lowerctrl}-add",
                    label : "添加",
                    autoopen : true,
                });
                TabCtrl.addTab(addTab);
            };

            {$samplectrl}LC.edit{$camelctrl} = function(id){
                var editTab = new Tab({
                    name : "{$lowerctrl}-edit",
                    label : "编辑",
                    autoopen : true,
                    params : {
                        id : id,
                        noclose : true,
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
                \$http.post(\$rootScope.SERVER+"/delete{$lowerctrl}", params).then(function(response){
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

            {$samplectrl}LC.init = function () {
            };

            \$scope.\$watch("{$samplectrl}LC.{$lowerctrl}LogSearch.search.keywords",function () {
                {$samplectrl}LC.{$lowerctrl}LogSearch.search.go();
            },true);

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

                {$samplectrl}AC.info = {};
                {$samplectrl}AC.canEdit = true;
                {$samplectrl}AC.info.photoConfig = {
                    uploadType : "image",
                    showDelete : function (obj) {
                        return {$samplectrl}AC.canEdit  && obj && (obj.path || obj.id);
                    }
                };

                {$samplectrl}AC.add{$camelctrl} = function(){
                    \$http.post(\$rootScope.SERVER+"/add{$lowerctrl}", \$("#form-{$lowerctrl}-add").serialize()).then(function(response){
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
                    \$("#form-{$lowerctrl}-add" + {$samplectrl}AC.id).submit();
                };

                {$samplectrl}AC.init = function () {
                    {$samplectrl}AC.initForm();
                }

                {$samplectrl}AC.initForm = function () {
                    var form =  \$("#form-{$lowerctrl}-add" + {$samplectrl}AC.id);
                    var btn = \$("#btn-{$lowerctrl}-add" + {$samplectrl}AC.id);
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
                var tab = ctrl.tab;
                var params = tab.params;
                var {$camelctrl}EditCenter = {},{$samplectrl}EC = {$camelctrl}EditCenter;
                {$samplectrl}EC.id = Helper.IdService.genId();

                \$http.get(\$rootScope.SERVER+"/get{$lowerctrl}detail", {params: params}).then(function(response){
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
                    \$("#form-{$lowerctrl}-edit"+{$samplectrl}EC.id).submit();
                };

                {$samplectrl}EC.init = function () {
                    {$samplectrl}EC.initForm();
                }

                {$samplectrl}EC.initForm = function () {
                    var form =  \$("#form-{$lowerctrl}-edit" + {$samplectrl}EC.id);
                    var btn = \$("#btn-{$lowerctrl}-edit" + {$samplectrl}EC.id);
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
        var {$camelctrl}HomeCenter = {},THC = {$camelctrl}HomeCenter;

        THC.initList = function(){
            var listTab = new Tab({
                name : "{$lowerctrl}-list",
                label : "列表",
                closeable : false,
            });
            TabCtrl.addTab(listTab);
        };
        THC.init = function(){
            THC.initList();
        };

        \$scope.TC = TabCtrl;
        \$scope.THC = THC;

        THC.init();
    });
});
EOF;
