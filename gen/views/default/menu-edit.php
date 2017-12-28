<script type="text/ng-template" id="nodes_renderer.html">
    <div ui-tree-handle class="tree-node tree-node-content"  ng-class="{'node-selected':OC.action.current.id == node.id,'node-tip':!(!node.loaded||(node.itemList && node.itemList.length > 0))}" data-nodrag="{{node.id<0 || true}}" ng-click="OC.toggle(node,this,$event)" ng-init="OC.menu.clickFirstNode(node,this)">
        <span class="node-title">
            {{node.displayname?node.displayname:node.name}}
        </span>
        <a class="btn btn-success btn-xs  hidden" ng-if="!node.loaded||(node.itemList && node.itemList.length > 0)"  data-nodrag><span
                class="glyphicon"
                ng-class="{
          'glyphicon-chevron-up': node.opened,
          'glyphicon-chevron-down': !node.opened
        }" ></span></a>
        <button type="button" class="btn btn-success btn-xs btn-show-add pull-right" ng-if="!node.loaded||(node.itemList && node.itemList.length > 0)"  data-nodrag><span
                class="glyphicon glyphicon-plus-sign"></span></button>
        <button type="button" class="btn btn-success btn-xs btn-sync-menu pull-right" ng-if="node.parentid == 1"  data-nodrag ><span
                class="glyphicon glyphicon-refresh" ng-class="{'ani-wheeling':OC.menu.isSyncing(node)}"></span></button>
    </div>
    <ol ui-tree-nodes="" ng-model="node.itemList" ng-class="{'hidden': !node.opened}">
        <li ng-repeat="node in node.itemList" ui-tree-node ng-include="'nodes_renderer.html'" data-expand-on-hover="true">
        </li>
    </ol>
</script>
<script type="text/ng-template" id="myModalContent.html">
    <div class="modal-header">
        <h3 class="modal-title" id="modal-title">I'm a modal!</h3>
    </div>
    <div class="modal-body" id="modal-body">
        <ul>
            <li ng-repeat="item in $ctrl.items">
                <a href="#" ng-click="$event.preventDefault(); $ctrl.selected.item = item">{{ item }}</a>
            </li>
        </ul>
        Selected: <b>{{ $ctrl.selected.item }}</b>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" type="button" ng-click="$ctrl.ok()">OK</button>
        <button class="btn btn-warning" type="button" ng-click="$ctrl.cancel()">Cancel</button>
    </div>
</script>
<div class="page page-org">
    <div class="block">
        <div class="block-head">
            <!--<button class="btn btn-success btn-dark pull-right" type="button" ng-click="OC.menu.openExportModal()">导出</button>-->

            <button class="btn btn-success btn-dark pull-right" type="button" ng-click="OC.menu.openImportModal()">导入</button>
        </div>
        <div class="block-content">
            <div class="row">
                <div class="col-sm-8 main-part">
                    <div class="block-content with-padding">
                        <div class="block-empty text-center block-load" ng-show="OC.org.root.empty">
                            <img src="<?=''?>">
                            <!--<div class="loadingtip text-center text-muted" ng-show="SSC.lesson.loading">加载中…</div>-->
                            <div class="pad-top emptytip text-center text-muted" ng-show="OC.org.root.empty">暂无数据,请导入或添加根菜单</div>
                        </div>
                        <div ng-show="!OC.org.root.empty&&OC.org.root.itemList&& (OC.org.root.itemList.length)">
                            <div ui-tree="treeOptions" data-drag-enabled ="false" data-empty-placeholder-enabled="true"  id="tree-root" class="with-padding" >
                                <ol ui-tree-nodes ng-model="OC.org.root.itemList">
                                    <li ng-repeat="node in OC.org.root.itemList" ui-tree-node ng-include="'nodes_renderer.html'" data-expand-on-hover="true" data-nodeid="{{node.id}}"></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 extra-part">
                    <div class="block" ng-show="OC.action.current">
                        <div class="block-title">
                            <span class="pull-left block-head">
                                菜单信息
                            </span>
                        </div>
                        <div class="block-content with-padding" ng-show="!OC.action.current">
                            暂未选择菜单
                        </div>
                        <div class="block-content with-padding" ng-show="OC.action.current">
                            <div class="node-info">
                                <form id="form-edit-node" class="form-horizontal" action="<?=Yii::app()->createUrl('/gen/defaultapi/editmenu')?>">
                                    <input id="node-add-code" class="form-control col-md-8 hidden" type="text"  name="node[menuid]"   ng-model="OC.action.current.id" >

                                    <div class="form-group-sm">
                                        <label class="control-label col-md-4" for="node-add-name">
                                            唯一名称
                                        </label>
                                        <div class="">
                                            <input id="node-add-name" class="form-control col-md-8" type="text"  name="node[name]" placeholder="唯一名称" ng-model="OC.action.current.name" ng-rea="OC.action.current && OC.action.current.id == 1">
                                        </div>
                                    </div>
                                    <div class="form-group-sm">
                                        <label class="control-label col-md-4" for="node-add-shortname">
                                            显示名称
                                        </label>
                                        <div class="">
                                            <input id="node-add-shortname" class="form-control col-md-8" type="text"  name="node[displayname]" placeholder="显示名称" ng-model="OC.action.current.displayname">
                                        </div>
                                    </div>
                                    <div class="form-group-sm">
                                        <label class="control-label col-md-4" for="node-name">
                                            url
                                        </label>
                                        <div class="">
                                            <input type="hidden" name="node[featureid]" value="{{OC.action.current.featureid}}">
                                            <input ss class="form-control col-md-8 input-icon input-icon-more-noleft cursor" type="text"  name="node[url]" placeholder="" ng-model="OC.action.current.url" ng-click="OC.feature.showModal('edit')" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group-sm">
                                        <label class="control-label col-md-4" for="node-name">
                                            菜单显示
                                        </label>
                                        <div class="">
                                            <input ss class="" value="0" type="radio"  name="node[hide]" placeholder="hide" ng-model="OC.action.current.hide">显示
                                            <input ss class="" value="1" type="radio"  name="node[hide]" placeholder="hide" ng-model="OC.action.current.hide">隐藏
                                        </div>
                                    </div>
                                    <div class="form-group-sm">
                                        <label class="control-label col-md-4" for="node-name">
                                            图标
                                        </label>
                                        <div class="">
                                            <input ss class="form-control col-md-8" type="text"  name="node[iconnormal]" placeholder="iconnormal" ng-model="OC.action.current.iconnormal">
                                        </div>
                                    </div>
                                    <div class="form-group-sm">
                                        <label class="control-label col-md-4" for="node-name">
                                            激活图标
                                        </label>
                                        <div class="">
                                            <input ss class="form-control col-md-8" type="text"  name="node[iconactive]" placeholder="iconactive" ng-model="OC.action.current.iconactive">
                                        </div>
                                    </div>
                                    <div class="form-group-sm">
                                        <div class="col-md-offset-4">
                                            <button class="btn btn-success btn-dark" type="submit">保存</button>

                                            <button class="btn btn-danger btn-dark-red btn-delete-node" type="button" id="btn-delete-node" ng-hide="OC.action.current && OC.action.current.id == 1">删除</button>
                                            <button class="btn btn-danger btn-dark-red btn-delete-node" type="button" id="btn-delete-node-force" ng-hide="OC.action.current && OC.action.current.id == 1">强制删除</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="block block-switch" ng-show="OC.add.showAdd">
                        <div class="block-title">
                            <span class="pull-left block-head">
                                添加菜单
                            </span>
                        </div>
                        <div class="block-content with-padding" ng-show="false">
                            暂未选择父亲菜单
                        </div>
                        <div class="block-content with-padding" ng-show="true">
                            <div class="">
                                <form id="form-add-node" class="form-horizontal" action="<?=Yii::app()->createUrl('/gen/defaultapi/addmenu')?>">
                                    <input id="node-code1" class="form-control col-md-8 hidden" type="text"  name="node[parentid]"   ng-model="OC.action.current.id" >
                                    <input id="node-code1" class="form-control col-md-8 hidden" type="text"  name="node[rootnode]"   ng-model="OC.nodeType.cursor" >
                                    <ul class="list-uploadtype list-inline list-block" ng-hide="true">
                                        <li class="li-paytype" ng-repeat="(key,type) in OC.nodeType.types" ng-click="OC.nodeType.select(key)">
                                            <span class="paytype-select" ng-class="{'active' : key == OC.nodeType.cursor}"></span>{{type}}
                                        </li>
                                    </ul>
                                    <div class="form-group-sm" ng-show="OC.nodeType.cursor == 2">
                                        <label class="control-label col-md-4" for="node-parent">
                                            父菜单名称
                                        </label>
                                        <div class="">
                                            <input id="node-parent" class="form-control col-md-8" type="text"  ng-model="OC.action.current.displayname" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group-sm">
                                        <label class="control-label col-md-4" for="node-code">
                                            唯一名称
                                        </label>
                                        <div class="">
                                            <input id="node-code" class="form-control col-md-8" type="text"  name="node[name]" placeholder="唯一名称"  ng-model="OC.action.addInfo.name" >
                                        </div>
                                    </div>
                                    <div class="form-group-sm">
                                        <label class="control-label col-md-4" for="node-name">
                                            显示名称
                                        </label>
                                        <div class="">
                                            <input ss class="form-control col-md-8" type="text"  name="node[displayname]" placeholder="显示名称" ng-model="OC.action.addInfo.displayname">
                                        </div>
                                    </div>
                                    <div class="form-group-sm">
                                        <label class="control-label col-md-4" for="node-name">
                                            url
                                        </label>
                                        <div class="">
                                            <input type="hidden" name="node[featureid]" value="{{OC.action.addInfo.featureid}}">
                                            <input ss class="form-control col-md-8 input-icon input-icon-more-noleft cursor" type="text"  name="node[url]" placeholder="" ng-model="OC.action.addInfo.url" ng-click="OC.feature.showModal('add')" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group-sm">
                                        <label class="control-label col-md-4" for="node-name">
                                            菜单显示
                                        </label>
                                        <div class="">
                                            <input ss value="0" type="radio"  name="node[hide]" placeholder="hide" ng-model="OC.action.addInfo.hide">显示
                                            <input ss value="1" type="radio"  name="node[hide]" placeholder="hide" ng-model="OC.action.addInfo.hide">隐藏
                                        </div>
                                    </div>
                                    <div class="form-group-sm">
                                        <label class="control-label col-md-4" for="node-name">
                                            图标
                                        </label>
                                        <div class="">
                                            <input ss class="form-control col-md-8" type="text"  name="node[iconnormal]" placeholder="iconnormal" ng-model="OC.action.addInfo.iconnormal">
                                        </div>
                                    </div>
                                    <div class="form-group-sm">
                                        <label class="control-label col-md-4" for="node-name">
                                            激活图标
                                        </label>
                                        <div class="">
                                            <input ss class="form-control col-md-8" type="text"  name="node[iconactive]" placeholder="iconactive" ng-model="OC.action.addInfo.iconactive">
                                        </div>
                                    </div>
                                    <div class="form-group-sm">
                                        <div class="col-md-offset-4">
                                            <button class="btn btn-dark" id="btn-add-node" type="submit"">添加</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--<div class="page page-sale-cata" >-->
                    <div class="block block-switch hidden">
                        <div class="block-title">
                                <span class="block-head pull-left">
                                    导入CSV
                                </span>
                        </div>
                        <div class="block-content with-padding step-upload">
                            <div class="upload-intro">
                                上级菜单尽量排在下属菜单之前
                            </div>
                            <div class="form-group-sm">
                                <a class="btn btn-default" href="<?=Yii::app()->createUrl('/upload/sample/sample.csv')?>">下载模板</a>
                            </div>
                            <form id="form-excel" action="<?=Yii::app()->createUrl('/gen/defaultapi/uploadcsv')?>" class="form-horizontal">
                                <div class="form-group-sm">
                                    <label class="control-label col-md-4" for="node-count">
                                        导入次数
                                        <!--关系层级数目（大概）-->
                                    </label>
                                    <div class="">
                                        <input class="form-control col-md-8" id="node-count" type="text"  name="levelnum" value="3" placeholder="导入多少次">
                                    </div>
                                </div>
                                <div class="form-group-sm">
                                    <div>
                                        <div class="excel-upload">
                                            <input id="excel-file" name="file" type="file" class="hidden">
                                            <div class="pull-left file-label hidden">Excel学生表：</div>
                                            <button class="btn-upload-excel btn btn-default pull-left btn-success" type="button">
                                                <span class="glyphicon glyphicon-upload"></span> 上传文件
                                            </button>
                                            <div class="pull-left file-name hidden" id="excel-filename" >
                                                {{OC.import.excelTitle?OC.import.excelTitle:"未选择"}}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                    <!--<button class="btn btn-success" ng-click="OC.resetBtn()" type="button">
                            出错请按重置按钮
                        </button>
                        <div class="block-error">
                            <div class="">结果</div>
                            <div class="">
                                {{OC.import.result}}
                            </div>
                        </div>
                        <div>
                            <div class="">当前数目</div>
                            <div class="">
                                {{OC.import.result.sum}}
                            </div>
                        </div>
                        <div>
                            <div class="">初始数目</div>
                            <div class="">
                                <?/*=@Secinfo::model()->count()*/?>
                            </div>
                        </div>-->
                    <!--</div>-->
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-correct modal-muum fade" id="modal-import-menu" data-backdrop="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h5 class="modal-title text-left">导入菜单</h5>
            </div>
            <div class="modal-body">
                <form id="form-import-menu" class="form-horizontal" action="<?=$this->createUrl('/gen/defaultapi/importmenu')?>">
                    <div class="form-group-sm">
                        <label class="control-label col-md-2" for="node-name">
                            唯一名称
                        </label>
                        <div class="">
                            <input class="form-control" placeholder="name" name="name">
                        </div>
                    </div>
                    <div class="form-group-sm">
                        <label class="control-label col-md-2" for="node-name">
                            显示名称
                        </label>
                        <div class="">
                            <input class="form-control" placeholder="displayname" name="displayname">
                        </div>
                    </div>
                    <div class="form-group-sm">
                        <label class="control-label col-md-2" for="node-name">
                            菜单文本
                        </label>
                        <div class="">
                            <textarea class="form-control" placeholder="例子数据：
    [
        ['数据',['http://image.lszhushou.com/2016/07/lszs1469182804350.png','http://image.lszhushou.com/2016/07/lszs1469182784010.png'],[
            ['用户管理',['http://image.lszhushou.com/2016/08/lszs1471930399964.png','http://image.lszhushou.com/2016/08/lszs1471930413435.png'],'/data/userlist'],
        ]],
        ['用户',null,[
            ['退出',['http://image.lszhushou.com/2016/06/lszs1465799914530.png','http://image.lszhushou.com/2016/06/lszs1465799924132.png','http://image.lszhushou.com/2016/06/lszs1465799933147.png'],'/sign/logout',true],
        ],true],
    ]"  name="menus" rows="20"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="text-center modal-footer">
                <button class="btn btn-dark btn-bottom-submit" type="button" ng-click="OC.menu.importMenu()" id="btn-import-menu">导入</button>
            </div>
        </div>
    </div>
</div>
