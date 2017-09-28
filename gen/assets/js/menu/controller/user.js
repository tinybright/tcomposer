$(function () {
    var wukongApp = angular.module(_MOUDLE_NAME);
    if(!wukongApp){
        console.e("no app");
        return;
    }
    function FakeForm(option) {
        $.extend(this,option);
        var temp = this;
        this.submit = function(){
            this.btn.attr("disabled",true);
            var data = this.getParams();
            temp.$http.post(this.action,data).then(function(response){
                var result = response.data;
                temp.btn.attr("disabled",false);
                switch (result.ret){
                    case "SUCCESS":
                        temp.success(result);
                        break;
                    case "FAIL":
                        $().message(result.data);
                        temp.fail(result);
                        break;
                    default:
                        $().message("未知AJAX反馈",'error');
                        console.log(result.data);
                        break;
                }
            });
        };
        this.init = function(){
            if(this.btn && this.btn.length){
                this.btn.click(function(){
                    if(temp.beforeSubmit($(this))){
                        temp.submit();
                    }
                });
            }
        };
    };
    
    wukongApp.factory("FeatureModalService",function (LoadData,DataSearch,$rootScope) {
        var FMS = function () {
            var FMST = this;
            FMST.config = {};

            FMST.feature = new LoadData({
                loadMode : "page",
                loadUrl : "/getsupplierlist",
                pagesize : 10,
                getParams : function () {
                    var params = angular.copy(FMST.featureSearch.search.keywords);
                    params.iskeyword = 0;
                    params.mode = 'approved';
                    return params;
                },
                getReqUrl : function () {
                    return FMST.config.name == "承运商"?"/getfeaturelist":'/getfeaturelist';
                }
            });
            FMST.featureSearch = new DataSearch({
                onSearch : function () {
                    FMST.feature.load.reset();
                },
                onReset : function(){
                }
            });
            FMST.feature.modalSupplier = $("#modal-feature-pagemode");
            FMST.feature.showModal = function (supplierid) {
                FMST.feature.modalSupplier.modal("show");
            };
            FMST.supplierModal = $("#modal-feature-pagemode");
            FMST.showModal = function (config) {
                FMST.config = config;
                FMST.featureSearch.search.reset();
                if(FMST.unwatch != null && typeof (FMST.unwatch) == "function"){
                    FMST.unwatch();
                }
                FMST.unwatch = $rootScope.$watch("FMS.featureSearch.search.keywords",function () {
                    FMST.featureSearch.search.go();
                },true);
                $("#modal-feature-pagemode").modal("show");
            };
            FMST.onItemSelected = function (supplier) {
                FMST.supplierModal.modal("hide");
                if(typeof(FMST.config.onSelected) == "function"){
                    FMST.config.onSelected(supplier)
                }
            }
        };
        return FMS;
    });
    wukongApp.controller('ModalInstanceCtrl', function ($uibModalInstance, items,$scope,LoadData,DataSearch) {
        /*var $ctrl = this;
        $ctrl.items = items;
        $ctrl.selected = {
            item: $ctrl.items[0]
        };*/

        var FMST = this;
        FMST.config = {};

        FMST.feature = new LoadData({
            loadMode : "page",
            loadUrl : "/getsupplierlist",
            pagesize : 10,
            getParams : function () {
                var params = angular.copy(FMST.featureSearch.search.keywords);
                params.iskeyword = 0;
                params.mode = 'approved';
                return params;
            },
            getReqUrl : function () {
                return FMST.config.name == "承运商"?"/getfeaturelist":'/getfeaturelist';
            }
        });
        FMST.featureSearch = new DataSearch({
            onSearch : function () {
                FMST.feature.load.reset();
            },
            onReset : function(){
            }
        });

        FMST.onItemSelected = function (supplier) {
            FMST.select(supplier)
        }

        FMST.select = function (supplier) {
            $uibModalInstance.close(supplier);
        };

        FMST.ok = function () {
            $uibModalInstance.close(FMST.selected.item);
        };

        FMST.cancel = function () {
            $uibModalInstance.dismiss('cancel');
        };

        $scope.$watch("FMST.featureSearch.search.keywords",function () {
            FMST.featureSearch.search.go();
        },true);
    });

    wukongApp.controller("MenuEditController",function($scope, $rootScope,$http,$location,$route,$timeout,Helper,$uibModal,$log) {
        var OC = {
            nodeType : {
                cursor : 0,
                types : {
                    "2" : "非根节点",
                    /*"1" : "根节点",*/
                },
                select : function (type) {
                    this.cursor = type > 0 ? type : 2;
                }
            },
            fakeForm:{
                deleteForm : new FakeForm({
                    $http : $http,
                    btn : $(".btn-delete-node"),
                    action : $rootScope.SERVER+"/deletemenu" ,
                    success : function (result) {
                        $().message("删除成功","success");
                        window.tm = OC.action.currentScope;
                        var need =  OC.action.currentScope.$nodeScope.$parentNodeScope.childNodesCount() == 1;
                        var node = OC.action.currentScope.$nodeScope.$parentNodeScope.$modelValue;
                        OC.action.currentScope.remove();
                        if(need){
                            var loadingNode = angular.copy(OC.org.sample);
                            loadingNode.displayname = '加载中';
                            if(!node.itemList || !node.itemList.length){
                                node.itemList = [
                                    loadingNode
                                ];
                            }
                            OC.org.load.getChild(node);
                        }
                        OC.action.current = angular.copy(null);
                        OC.action.temp = null;
                    },
                    fail : function (result) {
                        $().message(result.data);
                    },
                    getParams : function(){
                        var data = Helper.FormHelper.prepareParams({
                            nodeid : OC.action.current.id,
                            force : OC.action.force ? 1 : 0
                        });
                        return data;
                    },
                    beforeSubmit : function (btn) {
                        OC.action.force = btn && btn.is("#btn-delete-node-force");
                        if(!OC.action.current || !OC.action.current.id){
                            return false;
                        }
                        if(OC.action.force){
                            if(!confirm("强制删除会递归删除子节点，确认删除该节点？")){
                                return false;
                            }
                        }else{
                            if(!confirm("确认删除该节点？")){
                                return false;
                            }
                        }
                        return true;
                    }
                }),
            },
            init : function () {
                OC.menu.init();
                OC.org.load.reset();
                OC.import.init();
                OC.fakeForm.deleteForm.init();
                $("#form-add-node").submit(function () {
                    if(OC.nodeType.cursor == 2){
                        if(!OC.action.current.id){
                            $().message("请先选择父节点");
                            return false;
                        }
                    }else{

                    }

                    if(OC.action.addInfo.name == ""){
                        $().message("请输入节点名称");
                        return false;
                    }
                    if(OC.action.addInfo.code == ""){
                        $().message("请输入节点编号");
                        return false;
                    }
                    $("#form-add-node").ajaxSubmit({
                        $http : $http,
                        btn : $("#btn-add-node"),
                        successEnable : true,
                        done : function (result) {
                            
                            switch (result.ret){
                                case "FAIL":
                                    $().message(result.data);
                                    break;
                                case "SUCCESS":
                                    var newNode = angular.copy(result.data);
                                    newNode.title = newNode.displayname?newNode.displayname:newNode.name;
                                    newNode.hide = newNode.hide>0?"1":"0";
                                    newNode.itemList = null;
                                    newNode.loaded = false;
                                    newNode.opened = false;
                                    if(OC.nodeType.cursor == 2){
                                        var nodeData = OC.action.currentScope.$modelValue;
                                        if(nodeData.itemList.size = 1){
                                            if(nodeData.itemList[0].id < 0){
                                                nodeData.itemList = [];
                                            }
                                        }
                                        nodeData.itemList.push(newNode);
                                    }else{
                                        if(nodeData.itemList.size = 1){
                                            if(nodeData.itemList[0].id < 0){
                                                nodeData.itemList = [];
                                            }
                                        }
                                        OC.org.root.itemList.push(newNode);
                                    }
                                    OC.action.addInfo = {};
                                    OC.action.addInfo.hide = "0";
                                    $rootScope.magic();
                                    $().message("添加成功","success");
                                    break;
                                default:
                                    $().message("未知AJAX反馈","error");
                                    break;
                            }
                        }
                    });
                    return false;
                });
                $("#form-edit-node").submit(function () {
                    if(!OC.action.current.id){
                        $().message("请先选择节点");
                        return false;
                    }
                    if(OC.action.current.name == ""){
                        $().message("请输入节点名称");
                        return false;
                    }
                    if(OC.action.current.code == ""){
                        $().message("请输入节点编号");
                        return false;
                    }
                    $("#form-edit-node").ajaxSubmit({
                        $http : $http,
                        btn : $("#btn-edit-node"),
                        successEnable : true,
                        done : function (result) {
                            switch (result.ret){
                                case "FAIL":
                                    $().message(result.data);
                                    break;
                                case "SUCCESS":
                                    OC.action.temp.code = OC.action.current.code;
                                    OC.action.temp.name = OC.action.current.name;
                                    OC.action.temp.displayname = OC.action.current.displayname;
                                    OC.action.temp.title = OC.action.current.displayname?OC.action.current.displayname:OC.action.current.name
                                    $rootScope.magic();
                                    $().message("保存成功","success");
                                    break;
                                default:
                                    $().message("未知AJAX反馈","error");
                                    break;
                            }
                        }
                    });
                    return false;
                });
                OC.nodeType.select();
            },
            action : {

                current : null,
                currentScope : null,
                addInfo : {hide:"0"},
                temp : null,
            },
            mode : "import",
            resetBtn : function () {
                $("[disabled = 'disabled']").attr("disabled",false)
            }
        };
        OC.action.force = false;
        OC.add = {};
        OC.add.showAdd = false;
        OC.add.toggleAddBlock = function () {
            OC.add.showAdd = true;
        };
        var options = {
            loadUrl : "/getchildlist",
            getParams : function () {
                return {};
            },
            pagesize :-1
        };
        OC.org = {};
        OC.org.sample = {
            id : '-100',
            displayname : '加载中',
            name : "加载中",
            itemList : [],
            loaded : true,
            opened : false,
        };
        OC.org.root = {
            id : -1,
            ts : null,
            itemList : [],
            end : false,
            empty : false,
            loading : false,
        };
        OC.org.load = {
            ts : null,
            itemList : [],
            end : false,
            empty : false,
            loading : false,
            getChild : function(node){
                if(!!!node || !!!node.id){
                    return;
                }
                if(node.loading){
                    return;
                }
                node.loading = true;
                var params = ((options.getParams) && typeof (options.getParams) == 'function') ? options.getParams(): {};
                params.page = 1;
                params.parentid = node.id;
                params.pagesize = node.pagesize;
                var ts = new Date().getTime();
                node.ts = ts;
                var temp = node;
                $http.get($rootScope.SERVER+ options.loadUrl,{params : params}).then(function(response){
                    if(temp.ts != ts){
                        return;
                    }
                    var result = response.data;
                    var list = result.data.list;

                    if(list && list.length){
                        angular.forEach(list,function (oneNode, value) {
                            oneNode.opened = false;
                            oneNode.loaded = false;
                            var loadingNode = angular.copy(OC.org.sample);
                            loadingNode.displayname = '加载中';
                            oneNode.itemList = [
                                loadingNode
                            ];
                        });
                        temp.itemList = list;
                    }else{
                        if(true){
                            temp.empty = true;
                        }
                        temp.end = true;
                        var loadingNode = angular.copy(OC.org.sample);
                        loadingNode.displayname = '暂无';
                        temp.itemList = [
                            loadingNode
                        ]
                    }
                    temp.loading = false;
                    temp.loaded = true;
                });
            },
            reset : function(node){
                OC.org.root.itemList = [];
                OC.org.root.end = false;
                OC.org.root.empty = false;
                OC.org.root.loading = false;
                OC.org.root.loaded = false;
                this.getChild(OC.org.root);
            }
        };

        OC.toggle = function (node,scope,event) {
            if(!node || node.id < 0){
                return false;
            }
            if($(event.target).hasClass("btn-sync-menu") || $(event.target).parent().hasClass("btn-sync-menu")){
                OC.menu.syncMenu(node);
                return false;
            }
            if($(event.target).hasClass("btn-show-add") || $(event.target).parent().hasClass("btn-show-add")){
                console.log("has - class");
                var loadingNode = angular.copy(OC.org.sample);
                loadingNode.displayname = '加载中';
                if(!node.itemList || !node.itemList.length){
                    node.itemList = [
                        loadingNode
                    ];
                }
                /*if(!node.opened){*/
                node.opened = true;
                if(!node.loaded){
                    OC.org.load.getChild(node);
                }
                OC.action.current = angular.copy(node);
                OC.action.temp = node;
                OC.action.currentScope = scope;
                /*}*/
                OC.add.toggleAddBlock();
            }else{
                var loadingNode = angular.copy(OC.org.sample);
                loadingNode.displayname = '加载中';
                if(!node.itemList || !node.itemList.length){
                    node.itemList = [
                        loadingNode
                    ];
                }
                /*scope.toggle();*/
                node.opened = !node.opened;
                if(!node.loaded){
                    OC.org.load.getChild(node);
                }
                OC.action.current = angular.copy(node);
                OC.action.temp = node;
                OC.action.currentScope = scope;
                OC.add.showAdd = false;
            }
            return false;
        };

        OC.moveLastToTheBeginning = function () {
            var a = OC.data.pop();
            OC.data.splice(0, 0, a);
        };

        OC.collapseAll = function () {
            $scope.$broadcast('angular-ui-tree:collapse-all');
        };

        OC.expandAll = function () {
            $scope.$broadcast('angular-ui-tree:expand-all');
        };
        OC.import = {};
        OC.import.excelTitle = "未选择";
        OC.import.studentList = [];
        OC.import.result = {};
        //ESC.import.result.success = 0;
        //ESC.import.result.fail = 0;

        $("#excel-file").val("");
        OC.mode = 'import';
        OC.import.excelTitle = "未选择";
        OC.import.result = {};
        OC.import.step = 'upload';

        OC.import.init = function(){
            var modal = $(".step-upload")
            // upload
            modal.find("#excel-file").change(function(){
                var file = $(this);
                var path = $.trim(file.val());
                var label = $("#excel-filename");
                if(path === ""){
                    OC.import.excelTitle = "未选择";
                }else{
                    OC.import.result = {
                        status : 'doing'
                    };
                    var split = path.split("\\");
                    var filename = split[split.length-1];

                    modal.find("#form-excel").submit();
                    OC.import.excelTitle = filename;
                }
            });
            modal.find(".btn-upload-excel").click(function(){
                var btn = $(this);
                modal.find("#excel-file").click();
            });
            // form
            modal.find("#form-excel").submit(function(){
                var form = $(this);
                // validate
                var path = $.trim(form.find("#excel-file").val());
                if(!path){
                    modal.message("请先选择文件");
                    return false;
                }
                // submit
                delete $http.defaults.headers.post["Content-Type"];
                $(".btn-upload-excel").html('<span class="ani-wheeling glyphicon glyphicon-refresh"></span>上传中…').attr("disabled",true);
                form.ajaxSubmit({
                    $http : $http,
                    file : true,
                    done : function(result){
                        $(".btn-upload-excel").html('<span class="glyphicon glyphicon-upload"></span> 上传文件').attr("disabled",false);
                        switch(result.ret){
                            case "FAIL":
                                modal.message(result.data);
                                break;
                            case "SUCCESS":
                                // form.find(":submit").attr("disabled",true);
                                OC.import.result = result;
                                $rootScope.magic();
                                modal.message("上传成功","success");
                                OC.org.load.reset();
                                break;
                            default:
                                modal.message("未知AJAX反馈","error");
                                break;
                        }
                    }
                });
                $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
                return false;
            });
        }
        $scope.treeOptions = {
            accept: function(sourceNodeScope, destNodesScope, destIndex) {
                return true;
            },
            beforeDrop : function (source,dest,elements,pos) {
                return true;
                if(!confirm("确认移动节点")){
                    return false;
                }
                return true;
            },
            dropped : function (sourcea,dest,elements,pos) {
                console.log([sourcea,dest,elements,pos]);
                var source = sourcea.source;
                var dest = sourcea.dest;
                var elements = sourcea.elements;
                var pos = sourcea.pos;
                window.abc = source;
                /*return;*/
                var id = source.nodeScope.$modelValue.id;
                var targetId = dest.nodesScope.$nodeScope.$modelValue.id;
                var data = "id=" + id;
                data += "&targetid="+ targetId;
                console.log(id,targetId);
                $http.post($rootScope.SERVER+"/moveMenu",data).then(function(response){
                    var result = response.data;
                    switch(result.ret){
                        case "FAIL":
                            $().message(result.data);
                            break;
                        case "SUCCESS":
                            $().message("移动成功","success");
                            break;
                        default:
                            $().message("未知反馈","error");
                            break;
                    }
                });
            },
            toggle :function (sourcea) {
                console.log(sourcea);
            }
        };
        OC.feature = {};
        OC.feature.showModal = function(mode){
            /*$rootScope.FMS.showModal({
                title : "选择功能点",
                name : "功能点",
                onSelected : function (transsupplier) {
                    console.log(transsupplier);
                    if(mode == "add"){
                        OC.action.addInfo.featureid = transsupplier.id;
                        OC.action.addInfo.url = transsupplier.url;
                    }else if(mode == "edit"){
                        OC.action.current.featureid = transsupplier.id;
                        OC.action.current.url = transsupplier.url;
                    }
                }
            });*/
            var size = "lg";
            /*var parentElem = parentSelector ?
                angular.element($document[0].querySelector('.modal-demo ' + parentSelector)) : undefined;*/
            var parentElem = undefined;
            var modalInstance = $uibModal.open({
                animation: false,
                templateUrl: 'modalAddMemo',
                controller: 'ModalAddMemoCtrl',
                controllerAs: 'MAMC',
                resolve: {
                    items: function () {
                    }
                }
            });

            modalInstance.result.then(function (selectedItem) {
                /*$ctrl.selected = selectedItem;*/
                $log.info("success");
                $log.info(selectedItem);
                if(mode == "add"){
                    OC.action.addInfo.featureid = selectedItem.id;
                    OC.action.addInfo.url = selectedItem.url;
                }else if(mode == "edit"){
                    OC.action.current.featureid = selectedItem.id;
                    OC.action.current.url = selectedItem.url;
                }
            }, function () {
                $log.info('Modal dismissed at: ' + new Date());
            });
        };
        OC.items = [1,2,3];

        
        OC.menu = {};
        OC.menu.clickFirstNode = function (node,scope,event) {
            if(!node || node.id < 0){
                return false;
            }
            if(node.id == 1){
                var loadingNode = angular.copy(OC.org.sample);
                loadingNode.displayname = '加载中';
                if(!node.itemList || !node.itemList.length){
                    node.itemList = [
                        loadingNode
                    ];
                }
                /*scope.toggle();*/
                node.opened = !node.opened;
                if(!node.loaded){
                    OC.org.load.getChild(node);
                }
                OC.action.current = angular.copy(node);
                OC.action.temp = node;
                OC.action.currentScope = scope;
                OC.add.showAdd = false;
            }
        };
        OC.menu.modalImportMenu = $("#modal-import-menu");
        OC.menu.openImportModal = function () {
            OC.menu.modalImportMenu.modal("show");
        };
        OC.menu.importMenu = function () {
            $("#form-import-menu").submit();
        };
        OC.menu.init = function () {
            $("#form-import-menu").submit(function () {
                var btn = $("#btn-import-menu");
                var form = $(this);
                form.ajaxSubmit({
                    $http:$http,
                    btn:btn,
                    done:function(result){
                        switch(result.ret){
                            case 'FAIL':
                                $().message(result.data);
                                break;
                            case 'SUCCESS':
                                $().message("导入成功");
                                OC.menu.modalImportMenu.modal("hide");
                                $timeout(function () {
                                    $route.reload();
                                },500);
                                break;
                            default:
                                $().message(MSG_SERVER_ERROR,"error");
                                break;
                        }
                    }
                });
                return false;
            });
        };
        OC.menu.syncing = [];
        OC.menu.isSyncing = function (menu) {
            return $.inArray(menu.id,OC.menu.syncing) != -1;
        };
        OC.menu.syncMenu = function (menu) {
            if(!menu){
                $().message("请选择菜单");
                return false;
            }
            /*if(!confirm("确认同步菜单")){
             return false;
             }*/
            OC.menu.syncing.push(menu.id);
            var rawData = {
                menuid : menu.id,
            };
            var data = Helper.FormHelper.prepareParams(rawData);
            $http.post($rootScope.SERVER+"/syncmenu",data).then(function(response){
                var result = response.data;
                switch(result.ret){
                    case "FAIL":
                        $().message(result.data);
                        break;
                    case "SUCCESS":
                        $().message("同步"+"成功");
                        break;
                    default:
                        $().message(MSG_SERVER_ERROR);
                        break;
                }
                Helper.ListHelper.removeByObj(OC.menu.syncing,menu.id);
            });
            return false;
        };

        $scope.OC = OC;

        OC.init();
    });

    wukongApp.controller("FeatureListController",function($scope,$http,$rootScope,MenuCenter,LoadData,DataSearch,$location,Helper,MediaUploadV1){
        var FeatureListCenter = {},FLC = FeatureListCenter;
        FLC.pageCache = {};
        FLC.pageCache.put = function () {
            Helper.PageCache.put({
                keywords: angular.copy(FLC.featureSearch.search.keywords),
                page: FLC.feature.load.page
            });
        };
        FLC.feature = new LoadData({
            loadUrl : '/getfeaturelist',
            loadMode : 'page',
            getParams : function(){
                var params =  angular.copy(FLC.featureSearch.search.keywords);
                return params;
            }
        });

        FLC.featureSearch = new DataSearch({
            onSearch : function (page) {
                FLC.feature.load.reset(page);
            },
            onReset : function(){
            }
        });

        FLC.route = {};
        FLC.route.toEdit = function (feature) {
            FLC.pageCache.put();
            MenuCenter.toNextPath("/menu/feature_edit","/"+feature.id,true,"数据","功能编辑");
        };
        FLC.route.toAdd = function (feature) {
            MenuCenter.toNextPath("/menu/feature_add","",true,"数据","功能添加");
        };

        FLC.init = function(){
            var cache = Helper.PageCache.get();
            if (cache) {
                FLC.featureSearch.search.keywords = angular.copy(cache.keywords);
            }
        };

        /*FLC.feedback.init = function () {
         FLC.feedback.formFeedback.submit(function(){
         var form = $(this);
         form.ajaxSubmit({
         $http : $http,
         btn : $("#btn-feedback-payfeature"),
         done : function(result){
         switch(result.ret){
         case "FAIL":
         $().message(result.data);
         break;
         case "SUCCESS":
         $().message("反馈成功");
         Helper.ListHelper.seekAndUpdateById(FLC.feature.load.itemList,FLC.feedback.current.id,function (item,list,index) {
         list[index] = result.data;
         });
         FLC.feedback.modalFeedback.modal("hide");
         break;
         default:
         $().message("未知反馈","error");
         break;
         }
         }
         });
         return false;
         });
         };
         */

        /*FLC.feedback.mark = function () {
         if(!FLC.feedback.current){
         $().message("请选择账单");
         return false;
         }
         if(!confirm("确认标记为反馈已处理")){
         return false;
         }
         /!*if(!$.trim(FLC.feature.auditmemo)){
         $().message("请输入备注");
         return false;
         }*!/
         FLC.feature.doing = true;
         var rawData = {
         payfeatureitemid : FLC.feedback.current.id,
         ver : FLC.feedback.current.ver,
         /!*memo : FLC.feature.auditmemo*!/
         };
         var data = Helper.FormHelper.prepareParams(rawData);
         $http.post($rootScope.SERVER+"/replypayfeatureitem",data).then(function(response){
         var result = response.data;
         switch(result.ret){
         case "FAIL":
         $().message(result.data);
         break;
         case "SUCCESS":
         $().message("标记"+"成功");
         Helper.ListHelper.seekAndUpdateById(FLC.feature.load.itemList,FLC.feedback.current.id,function (item,list,key) {
         list[key] = result.data;
         });
         FLC.feature.modalPublish.modal("hide");
         FLC.feedback.modalFeedback.modal("hide");
         break;
         default:
         $().message(MSG_SERVER_ERROR);
         break;
         }
         });
         };*/
        $scope.FLC = FLC;
        $scope.$watch("FLC.featureSearch.search.keywords",function () {
            var page = Helper.PageCache.getPageAndDelete();
            FLC.featureSearch.search.go(page);
        },true);
        FLC.init();
    });

    wukongApp.controller("FeatureAddController",function ($scope,$http,$location,$rootScope,MenuCenter,$routeParams,Helper,MediaUploadV1) {
        var FeatureAddCenter = {},FAC = FeatureAddCenter;

        FAC.getModeByPath = function () {
            var path = $location.path();
            if(path.indexOf("feature_add")>=0){
                return "add";
            }else if(path.indexOf("feature_edit")>=0){
                return "edit";
            }
            return "";
        };
        FAC.mode = FAC.getModeByPath();
        FAC.canEdit = true || FAC.mode == "add";
        FAC.current = {};



        FAC.feature = {};
        FAC.feature.init = function () {
            $("#form-add-feature").submit(function () {
                var btn = $("#btn-add-feature");
                var form = $(this);
                form.ajaxSubmit({
                    $http:$http,
                    btn:btn,
                    done:function(result){
                        switch(result.ret){
                            case 'FAIL':
                                $().message(result.data);
                                break;
                            case 'SUCCESS':
                                FAC.mode = 'add';
                                FAC.current = {};
                                form.get(0).reset();
                                if($routeParams.id && $routeParams.id != 0){
                                    $().message('修改成功');
                                }else{
                                    $().message('提交成功');
                                }
                                FAC.canEdit = true;
                                $rootScope.magic();
                                if($routeParams.id && $routeParams.id != 0){
                                    MenuCenter.toNextMenu("/feature_list");
                                }
                                break;
                            default:
                                $().message("未知反馈","error");
                                break;
                        }
                    }
                });
                return false;
            });
            if(FAC.mode != "add"){
                FAC.feature.getDetail();
            }
        };
        FAC.feature.submitForm = function () {
            $("#form-add-feature").submit();
        };
        FAC.feature.getDetail = function () {
            var params = {};
            params.id = $routeParams.id;
            $http.get($rootScope.SERVER + "/getfeature",{params : params}).then(function(response){
                var result = response.data;
                switch(result.ret){
                    case "FAIL":
                        $().message(result.data);
                        break;
                    case "SUCCESS":
                        FAC.current = result.data;
                        break;
                    default:
                        $().message(MSG_SERVER_ERROR);
                        break;
                }
            });
        };

        FAC.init = function () {
            FAC.feature.init();
            FAC.screen.init();
        };


        /*BAC.replyfileUpload = new MediaUploadV1();
         BAC.replyfileUpload.upload.init({
         multi:true,
         el : $(".file-container-replyfile"),
         maxSize : 20,
         onUploaded : function (btn,url,fileInfo) {
         window.test = btn;
         btn.scope().$parent.BAC.current.replyfilelist[btn.scope().$index].path = url;
         btn.scope().$parent.BAC.current.replyfilelist[btn.scope().$index].name = fileInfo.name;
         $rootScope.magic();
         }
         });*/
        FAC.screen = {};
        FAC.screen.demo = {
            "name":"",
            "path":"",
            "fileid":"",
            "id":"",
            "originalname":"",
        };
        FAC.screen.addFile = function (path,name) {
            var newFile = angular.copy(FAC.screen.demo);
            newFile.path = path;
            newFile.name = name;
            newFile.originalname = name;
            if(!FAC.current.screenList){
                FAC.current.screenList = [];
            }
            FAC.current.screenList.push(newFile);
        };
        FAC.screen.addIfNotExist = function () {
            if(!FAC.current.screenList ||FAC.current.screenList.length == 0){
                FAC.current.screenList = [];
                FAC.screen.addFile();
            }
        };
        FAC.screen.removeFile = function (index) {
            if(!confirm("确定删除?")){
                return false;
            }
            console.log([FAC.current.screenList,index]);
            ListHelper.removeByIndex(FAC.current.screenList,index);
            /*FAC.screen.addIfNotExist();*/
        };
        FAC.screen.init = function () {
            FAC.screenUpload = new MediaUploadV1();
            FAC.screenUpload.upload.init({
                multi:true,
                el : $(".file-container-screen"),
                maxSize : 20,
                batch : true,
                onUploaded : function (btn,url,fileInfo) {
                    FAC.screen.addFile(url,fileInfo.name);
                    $rootScope.magic();
                }
            });
        };
        $scope.FAC = FAC;
        FAC.init();
    });
});