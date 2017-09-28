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
        templateUrl : "<?=$controller?>/<?=$page?>List",
        controller : function($scope,$http,Tab,TabCtrl,$rootScope,LoadData,DataSearch,TimeSelect,FormHelper,Helper,$timeout,ListHelper){

            $(".modal").one("show.bs.modal",function(){
                $(this).appendTo("body");
            });
            $scope.ARRS = $rootScope.ARRS;
            $scope._LS = $rootScope._LS;

            var <?=ucfirst($page);?>ListCenter = {},<?=$primary?>LC = <?=ucfirst($page);?>ListCenter;

            <?=$primary?>LC.<?=$page?>Log = new LoadData({
                loadUrl : "/get<?=$lowCtrl?>list",
                getParams : function () {
                    return angular.copy(<?=$primary?>LC.<?=$page?>LogSearch.search.keywords);
                }
            });
            <?=$primary?>LC.<?=$page?>LogSearch = new DataSearch({
                onSearch : function () {
                    <?=$primary?>LC.<?=$page?>Log.load.reset();
                },
                onReset : function(){
                }
            });

            <?=$primary?>LC.<?=$page?>LogSearch.search.reset = function(){
                <?=$primary?>LC.<?=$page?>LogSearch.search.keywords = {};
                $(".search-time-input").val("");
            }

            <?=$primary?>LC.single = {};
            <?=$primary?>LC.single.dealAction = function (<?=$page?>,verb) {
                if ("<?=$page?>_edit"==verb){
                    <?=$primary?>LC.edit<?=ucfirst($page);?>(<?=$page?>.id);
                    return ;
                }
                if ("<?=$page?>_delete"==verb){
                    <?=$primary?>LC.delete<?=ucfirst($page);?>(<?=$page?>.id);
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
                if ("<?=$page?>_delete"==verb){
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
                    if ("编辑|<?=$page?>_edit" == commonList[k]){
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
                if(!<?=$primary?>LC.<?=$page?>Log.load.itemList || !<?=$primary?>LC.<?=$page?>Log.load.itemList.length){
                    return;
                }
                var isAllSelected = <?=$primary?>LC.batch.isAllSelected();
                <?=$primary?>LC.<?=$page?>Log.load.itemList.forEach(function(ci){
                    ci._select = !isAllSelected;
                });
                <?=$primary?>LC.batch.calcAction();
            };
            <?=$primary?>LC.batch.isAllSelected = function(){
                if(!<?=$primary?>LC.<?=$page?>Log.load.itemList || !<?=$primary?>LC.<?=$page?>Log.load.itemList.length){
                    return false;
                }
                return <?=$primary?>LC.<?=$page?>Log.load.itemList.every(function(ci){
                    return ci._select;
                });
            };
            <?=$primary?>LC.batch.hasSelected = function(){
                if(!<?=$primary?>LC.<?=$page?>Log.load.itemList || !<?=$primary?>LC.<?=$page?>Log.load.itemList.length){
                    return false;
                }
                return <?=$primary?>LC.<?=$page?>Log.load.itemList.some(function(ci){
                    return ci._select;
                });
            };
            <?=$primary?>LC.batch.getSelectedList = function(){
                return <?=$primary?>LC.<?=$page?>Log.load.itemList.filter(function(ci){
                    return ci._select;
                });
            };

            <?=$primary?>LC.add<?=ucfirst($page);?> = function(){
                var addTab = new Tab({
                    name : "<?=$linePage?>-add",
                    label : "添加",
                    autoopen : true,
                });
                TabCtrl.addTab(addTab);
            };

            <?=$primary?>LC.edit<?=ucfirst($page);?> = function(id){
                var editTab = new Tab({
                    name : "<?=$linePage?>-edit",
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
                            /* 列表操作无刷新更新，
                            ListHelper.seekAndUpdateById(ILC.invoiceLog.load.itemList,id,function (item) {
                                // 需要更新的数据，例如，操作功能的变化
                                item.actions = response.data.data.actions;
                            });
                            */
                            /* 
                            if (id instanceof Array){  //  删除多行
                                ListHelper.removeByIds(<?=$primary?>LC.<?=$page?>Log.load.itemList, id);
                            } else {  //  删除一行
                                ListHelper.removeById(<?=$primary?>LC.<?=$page?>Log.load.itemList, id);
                            }
                            */
                            break;
                        default :
                            $().message(MSG_SERVER_ERROR);
                            break;
                    }
                });
            };

<?
    if (isset($btn_import) && "1" == $btn_import){
?>
            <?=$primary?>LC.ExcelImport = {};
            <?=$primary?>LC.ExcelImport.current = {};
            <?=$primary?>LC.ExcelImport.current.temppaybill = null;
            <?=$primary?>LC.ExcelImport.import = {};
            // <?=$primary?>LC.ExcelImport.current.temppaybill = null;
            <?=$primary?>LC.ExcelImport.import.showConfirm = false;
            <?=$primary?>LC.ExcelImport.import.itemList = [];
            <?=$primary?>LC.ExcelImport.import.code = null;
            <?=$primary?>LC.ExcelImport.import.startTime = null;
            <?=$primary?>LC.ExcelImport.import.error = [];
            <?=$primary?>LC.ExcelImport.import.ready = false;
            <?=$primary?>LC.ExcelImport.import.haserror = true;
            <?=$primary?>LC.ExcelImport.import.init = function(){
                <?=$primary?>LC.ExcelImport.import.reset();
            }
            <?=$primary?>LC.ExcelImport.import.submit = function () {

            }
            <?=$primary?>LC.ExcelImport.import.removeBillFile = function(){
                if (!confirm("确定删除？")) {
                    return false;
                }
                <?=$primary?>LC.ExcelImport.current.paybill = null;
                <?=$primary?>LC.ExcelImport.current.temppaybill = null;
            }
            <?=$primary?>LC.ExcelImport.import.confirm = function(){
                <?=$primary?>LC.ExcelImport.current.paybill = angular.copy(<?=$primary?>LC.ExcelImport.current.temppaybill);
                
                if(!<?=$primary?>LC.ExcelImport.current){
                    $().message("请选择账单");
                    return false;
                }
                <?=$primary?>LC.ExcelImport.import.doing = true;
                var rawData = {
                    data : angular.toJson(<?=$primary?>LC.ExcelImport.current.paybill),
                };
                var data = Helper.FormHelper.prepareParams(rawData);
                $http.post($rootScope.SERVER+"/receipttoDB",data).then(function(response){
                    var result = response.data;
                        switch(result.ret){
                            case "FAIL":
                                $().message(result.data);
                            break;
                            case "SUCCESS":
                                $().message("导入"+"成功");
                                $("#modal-efficiency-list").on("hidden.bs.modal",function () {
                                    <?=$primary?>LC.receiptLogSearch.search.go();
                                });
                                $("#modal-efficiency-list").modal("hide");
                                <?=$primary?>LC.ExcelImport.import.showConfirm = false;
                                break;
                            default:
                                $().message(MSG_SERVER_ERROR);
                                break;
                    }
                    <?=$primary?>LC.ExcelImport.import.doing = false;
                });
            };
            <?=$primary?>LC.ExcelImport.import.reset = function(){
                <?=$primary?>LC.ExcelImport.createItems = 0;
                <?=$primary?>LC.ExcelImport.import.itemList = [];
                <?=$primary?>LC.ExcelImport.import.error = [];
                <?=$primary?>LC.ExcelImport.import.ready = false;
                <?=$primary?>LC.ExcelImport.import.haserror = true;
            };
            <?=$primary?>LC.initForm = function () {
                var form = $("#container-upload-efficiency"+ <?=$primary?>LC.id + "[data-uploadform = '#form-upload-efficiency"+<?=$primary?>LC.id+"']");
                var container = $( "#form-upload-efficiency"+ <?=$primary?>LC.id);
                console.log(form.length ,container.length);
                if(form && form.length && container && container.length){
                    console.log("excel form ready" + <?=$primary?>LC.id);
                    <?=$primary?>LC.ExcelImport.mediaUploadBill = new ExcelImport();
                    <?=$primary?>LC.ExcelImport.mediaUploadBill.upload.init({
                        multi : true,
                        el : $("#container-upload-efficiency"+ <?=$primary?>LC.id),
                        formId : "form-upload-efficiency"+ <?=$primary?>LC.id,
                        maxSize : 20,
                        hideLoading : true,
                        onUploaded : function (btn,result) {
                            <?=$primary?>LC.ExcelImport.current.temppaybill = null;
                            switch(result.ret){
                                case "FAIL":
                                    <?=$primary?>LC.ExcelImport.import.haserror = true;
                                    <?=$primary?>LC.ExcelImport.import.error = result.data;
                                    <?=$primary?>LC.ExcelImport.import.showConfirm = false;
                                    <?=$primary?>LC.ExcelImport.current.temppaybill = null;
                                    break;
                                case "SUCCESS":
                                    <?=$primary?>LC.ExcelImport.import.haserror = false;
                                    <?=$primary?>LC.ExcelImport.import.error = null;
                                    <?=$primary?>LC.ExcelImport.import.showConfirm = true;
                                    <?=$primary?>LC.ExcelImport.current.temppaybill = result.info;
                                    $rootScope.magic();
                                    break;
                                default:
                                    break;
                            }
                            $rootScope.magic();
                            $("#modal-efficiency-list").modal("show");
                        }
                    });
                }else{
                    $timeout(<?=$primary?>LC.initForm,_ID_POLL_INTERVAL);
                }
            };
<?
    }
?>

            <?=$primary?>LC.init = function () {
<?
    if (isset($btn_import) && "1" == $btn_import){
?>
                <?=$primary?>LC.initForm();
<?
    }
?>
            };

            $scope.$watch("<?=$primary?>LC.<?=$page?>LogSearch.search.keywords",function () {
                <?=$primary?>LC.<?=$page?>LogSearch.search.go();
            },true);

            $scope.<?=$primary?>LC = <?=$primary?>LC;
            <?=$primary?>LC.init();
        }
    });

    <?=$app."App"?>.component("t<?=ucfirst($page);?>Add",{
        templateUrl : "<?=$controller?>/<?=$page?>Add",
        bindings : {
            tab : "=",
        },
        controller : function($scope, $http, $rootScope, Tab, TabCtrl, Helper, $timeout){
            var ctrl = this;
            ctrl.$onInit = function(){
                $scope.ARRS = $rootScope.ARRS;
                var tab = ctrl.tab;
                var <?=ucfirst($page);?>AddCenter = {},<?=$primary?>AC = <?=ucfirst($page);?>AddCenter;
                <?=$primary?>AC.id = Helper.IdService.genId();
<?
        foreach ($fields as $value) {
            if (isset($value["add"]) && "on" == @$value["add"]){
                if ("photo"==$value["type"]) {
?>
                <?=$primary?>AC.info = {};
                <?=$primary?>AC.canEdit = true;
                <?=$primary?>AC.info.<?=$value["en"]?>Config = {
                    uploadType : "image",
                    showDelete : function (obj) {
                        return <?=$primary?>AC.canEdit  && obj && (obj.path || obj.id);
                    }
                };
<?
                }
            }
        }
?>
                <?=$primary?>AC.add<?=ucfirst($page);?> = function () {
                    $("#form-<?=$page?>-add" + <?=$primary?>AC.id).submit();
                };

                <?=$primary?>AC.init = function () {
                    <?=$primary?>AC.initForm();
                }

                <?=$primary?>AC.initForm = function () {
                    var form =  $("#form-<?=$page?>-add" + <?=$primary?>AC.id);
                    var btn = $("#btn-<?=$page?>-add" + <?=$primary?>AC.id);
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

                $scope.<?=$primary?>AC = <?=$primary?>AC;
                <?=$primary?>AC.init();
            }
        }
    });

    <?=$app."App"?>.component("t<?=ucfirst($page);?>Edit",{
        templateUrl : "<?=$controller?>/<?=$page?>Edit",
        bindings : {
            tab : "=",
        },
        controller : function($scope, $http, $rootScope, Tab, TabCtrl, Helper, $timeout, $filter){
            var ctrl = this;
            ctrl.$onInit = function(){
                $scope.ARRS = $rootScope.ARRS;
                var tab = ctrl.tab;
                var params = tab.params;
                var <?=ucfirst($page);?>EditCenter = {},<?=$primary?>EC = <?=ucfirst($page);?>EditCenter;
                <?=$primary?>EC.id = Helper.IdService.genId();

                $http.get($rootScope.SERVER+"/get<?=$lowCtrl?>detail", {params: params}).then(function(response){
                    var result = response.data;
                    switch(result.ret){
                        case "FAIL":
                            $().message(result.data);
                            break;
                        case "SUCCESS":
                            <?=$primary?>EC.info = result.data;
<?
    foreach ($fields as $value) {
        if ('time' == $value['type']){
?>
                            <?=$primary?>EC.info.<?=$value['en']?> = $filter("date")(<?=$primary?>EC.info.<?=$value['en']?>, "yyyy-MM-dd");
<?
        }
    }
?>
<?
    foreach ($fields as $value) {
        if (isset($value["add"]) && "on" == @$value["add"]){
            if ("photo"==$value["type"]) {
?>
                            <?=$primary?>EC.info.<?=$value["en"]?>Config = {
                                uploadType : "image",
                                showDelete : function (obj) {
                                    return <?=$primary?>EC.canEdit  && obj &&(obj.path || obj.id);
                                }
                            };
                            <?=$primary?>EC.canEdit = true;
<?
            }
        }
    }
?>
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
                    $("#form-<?=$page?>-edit"+<?=$primary?>EC.id).submit();
<?
    foreach ($fields as $value) {
        if (isset($value["add"]) && "on" == @$value["add"]){
            if ("photo"==$value["type"]) {
?>
                            <?=$primary?>EC.canEdit = false;
<?
            }
        }
    }
?>
                };

                <?=$primary?>EC.init = function () {
                    <?=$primary?>EC.initForm();
                }

                <?=$primary?>EC.initForm = function () {
                    var form =  $("#form-<?=$page?>-edit" + <?=$primary?>EC.id);
                    var btn = $("#btn-<?=$page?>-edit" + <?=$primary?>EC.id);
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
<?
    foreach ($fields as $value) {
        if (isset($value["add"]) && "on" == @$value["add"]){
            if ("photo"==$value["type"]) {
?>
                                            <?=$primary?>EC.canEdit = true;
<?
            }
        }
    }
?>
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

                <?=$primary?>EC.close = function(){
                    TabCtrl.closeTab(tab);
                };

                $scope.<?=$primary?>EC = <?=$primary?>EC;
                <?=$primary?>EC.init();
            }
        }
    });

    <?=$app."App"?>.controller("<?=ucfirst($page);?>Controller",function($scope,Tab,TabCtrl){
        var <?=ucfirst($page);?>HomeCenter = {},<?=$primary?>HC = <?=ucfirst($page);?>HomeCenter;

        <?=$primary?>HC.initList = function(){
            var listTab = new Tab({
                name : "<?=$linePage?>-list",
                label : "列表",
                closeable : false,
            });
            TabCtrl.addTab(listTab);
        };
        <?=$primary?>HC.init = function(){
            <?=$primary?>HC.initList();
        };

        $scope.TC = TabCtrl;
        $scope.<?=$primary?>HC = <?=$primary?>HC;

        <?=$primary?>HC.init();
    });
});