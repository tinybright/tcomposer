<?
    $cols = 1;
    foreach ($fields as $value) {
        if (isset($value["list"]) && "on" == $value["list"]){
            $cols ++;
        }
    }
    $primary = strtoupper($page[0]);
?>
<div class="page-<?=@$page?>-list page-list">
<?
    if (isset($btn_import) && "1" == $btn_import){
?>
    <div ng-attr-id="{{'container-upload-efficiency'+ <?=$primary?>LC.id}}" class="absolute-container page-ctrl-action img-container-file-efficiency btn-import" ng-attr-data-uploadform="{{ '#'+'form-upload-efficiency'+ <?=$primary?>LC.id}}">
        <button class="btn pull-right btn-upload-media btn-upload-excel btn-dark" type="button">
            导入
        </button>
    </div>
<?
    }
?>
    <button class="btn btn-dark" type="button" ng-click="<?=$primary?>LC.add<?=ucfirst(@$page)?>();">添加</button>
    <div class="list-space">
        <table class="list">
            <colgroup>
                <col class="col-checkbox">
<?
    foreach ($fields as $value) {
        if (isset($value["list"]) && "on" == @$value["list"]){
?>
                <col class="<?=@$value["width"]?>">
<?
        }
    }
?>
                <col class="col-operate">
                <col class="col-history">
            </colgroup>
            <thead>
                <tr class="list-header">
                    <td ng-click="<?=$primary?>LC.batch.selectAll()" class="cursor">
                        <span>
                            <span class="cb-select glyphicon" ng-class="{'active glyphicon-check' : <?=$primary?>LC.batch.isAllSelected() , 'glyphicon-unchecked' : !<?=$primary?>LC.batch.isAllSelected()}"></span>
                        </span>
                    </td>
<?
    foreach ($fields as $value) {
        if (isset($value["list"]) && "on" == @$value["list"]){
?>
                    <td><?=@$value["zh"]?></td>
<?
        }
    }
?>
                    <td>操作</td>
                    <td></td>
                </tr>
            </thead>
            <tbody class="list-main">
                <tr class="list-group search">
                    <td></td>
<?
    foreach ($fields as $value) {
        if (isset($value["list"]) && "on" == @$value["list"]){
            switch ($value["type"]){
                case "input":
?>
                    <td><input class="search-input" type="text" ng-model="<?=$primary?>LC.<?=$page?>LogSearch.search.keywords.<?=@$value["en"]?>"></td>
<?
                    break;
                case "select":
?>
                    <td>
                        <select class="search-select" ng-model="<?=$primary?>LC.<?=$page?>LogSearch.search.keywords.<?=@$value["en"]?>">
<?
                            $options = CheckUtil::getValue($value,"statusName");
                            if ("" == $options){
                                $options = @$page."_".$value["en"]."";
                            }
                            $options = strtoupper($options);
                            echo <<<EOT
                            <option value=""><?=Msgs::SELECT_HOLDER ?></option>
                            <?
                            foreach (MyStatus::\$$options as \$key => \$value) {
                                ?>
                                <option value="<?=\$key?>"><?=\$value?></option>
                                <?
                            }
                            ?>

EOT;
?>
                        </select>
                    </td>
<?
                    break;
                case "time":
?>
                    <td><date-ranger timer="<?=$primary?>LC.<?=$page?>LogSearch.search.keywords.<?=$value["en"]?>"></td>
<?
                    break;
                default :
?>
                    <td></td>
<?
                    break;
            }
        }
    }
?>
                    <td><button type="button" class="btn btn-link" ng-click="<?=$primary?>LC.<?=$page?>LogSearch.search.reset();">清空搜索</button></td>
                    <td></td>
                </tr>
                <tr class="list-group" ng-repeat="info in <?=$primary?>LC.<?=$page?>Log.load.itemList">
                    <td ng-click="<?=$primary?>LC.batch.select(info)" class="cursor">
                        <span class="cb-select glyphicon" ng-class="{'active glyphicon-check' : info._select , 'glyphicon-unchecked' : !info._select}"></span>
                    </td>
<?
    foreach ($fields as $value) {
        if (isset($value["list"]) && "on" == @$value["list"]){
            if ("time" == $value["type"]){
?>
                    <td>{{info.<?=@$value["en"]?> | date:'yyyy-MM-dd'}}</td>
<?
            } else {
?>
                    <td>{{info.<?=@$value["en"]?>}}</td>
<?
            }
        }
    }
?>
                    <td class="text-left">
                        <button type="button" class="btn btn-link" ng-click="<?=$primary?>LC.single.dealAction(info,<?=$primary?>LC.batch.getActionVerb(action));" ng-repeat="action in info.actions">{{<?=$primary?>LC.batch.getActionName(action)}}</button>
                    </td>
                    <td class="td-icon">
                        <td-log objtype="'<?=$page?>'" objid = "info.id" on-click="_LS.showLog({objtype:'<?=$page?>',objid:info.id})"></td-log>
                    </td>
                </tr>
                <tr class="list-group no-border">
                    <td colspan="<?=$cols?>"></td>
                    <td class="text-left">
                        <button type="button" class="btn btn-link" ng-click="<?=$primary?>LC.batch.dealAction(<?=$primary?>LC.batch.getActionVerb(action));" class="text-gray disabled" ng-repeat="action in <?=$primary?>LC.batch.actions">{{<?=$primary?>LC.batch.getActionName(action)}}</button>
                        <button ng-if="!<?=$primary?>LC.batch.actions || <?=$primary?>LC.batch.actions.length == 0" type="button" class="btn btn-link" disabled ng-click="<?=$primary?>LC.batch.dealAction(<?=$primary?>LC.batch.getActionVerb(action));" class="text-gray disabled" ng-repeat="action in <?=$primary?>LC.batch.holderActions">{{<?=$primary?>LC.batch.getActionName(action)}}</button>
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <page-ctrl target="<?=$primary?>LC.<?=$page?>Log.load"></page-ctrl>
    </div>
<?
    if (isset($btn_import) && "1" == $btn_import){
?>
    <div id="modal-efficiency-list" class="modal-index-0 modal fade modal-bestfi modal-contract-item modal-paybill-detail" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h5 class="modal-title">时效导入</h5>
                </div>
                <div class="table-import modal-body page">
                    <table class="table table-lszs text-center table-plain" ng-hide="<?=$primary?>LC.ExcelImport.import.haserror">
                        <colgroup>
<?
        foreach ($fields as $value) {
            if (isset($value["list"]) && "on" == @$value["list"]){
?>
                            <col class="<?=@$value["width"]?>">
<?
            }
        }
?>
                        </colgroup>
                        <thead>
                            <tr>
<?
        foreach ($fields as $value) {
            if (isset($value["list"]) && "on" == @$value["list"]){
?>
                                <th><?=$value['zh']?></th>
<?
            }
        }
?>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="info in <?=$primary?>LC.ExcelImport.current.temppaybill">
<?
        foreach ($fields as $value) {
            if (isset($value["list"]) && "on" == @$value["list"]){
                if ("time" == $value["type"]){
?>
                    <td>{{info.<?=@$value["en"]?> | date:'yyyy-MM-dd'}}</td>
<?
                } else {
?>
                    <td>{{info.<?=@$value["en"]?>}}</td>
<?
                }
            }
        }
?>
                            </tr>
                        </tbody>
                    </table>
                    <div class="error-section" ng-show="<?=$primary?>LC.ExcelImport.import.haserror">
                        <div>上传出错了！请检查文件</div>
                        <div>
                            {{<?=$primary?>LC.ExcelImport.import.error}}
                        </div>
                        <!--<div class="" ng-repeat="(key,error) in <?=$primary?>LC.ExcelImport.import.error">
                            {{key=="base"?error:"第"+key +"行："+ error}}
                        </div>-->
                    </div>
                </div>
                <div class="modal-footer" ng-show="<?=$primary?>LC.ExcelImport.import.showConfirm">
                    <!-- <button type="button" class="btn btn-default" ng-click="<?=$primary?>LC.ExcelImport.import.reselect()">重新上传</button> -->
                    <button type="button" class="btn btn-dark" ng-click="<?=$primary?>LC.ExcelImport.import.confirm()" ng-show="!<?=$primary?>LC.ExcelImport.import.haserror">确定</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <div ng-attr-id="{{'container-upload-efficiency'+ <?=$primary?>LC.id}}" class="absolute-container page-ctrl-action img-container-file-efficiency btn-import" ng-attr-data-uploadform="{{ '#'+'form-upload-efficiency'+ <?=$primary?>LC.id}}">
        <button class="btn pull-right btn-upload-media btn-upload-excel btn-dark" type="button">
            导入
        </button>
    </div>
<?
    }
?>
</div>