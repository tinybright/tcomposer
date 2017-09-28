<?
    $primary = strtoupper($page[0]);
    $lowCtrl = strtolower($page);
    $page = $lowCtrl;
    $link = "<?=Yii::app()->createUrl(\"/".$controller."api/edit".$lowCtrl."\")?>";
?>
<div class="page-<?=@$page?>-edit page-operate">
    <form ng-attr-id="{{'form-<?=@$page?>-edit'+<?=$primary?>EC.id}}" class="form-wukong form-horizontal" action="<?=$link?>">
        <input type="hidden" name="<?=@$page?>[id]" value="{{<?=$primary?>EC.info.id}}">
        <input type="hidden" name="<?=@$page?>[ver]" value="{{<?=$primary?>EC.info.ver}}">
        <div class="form-left pull-left">
            <div class="block">
                <div class="block-head">基本信息</div>
                <div class="block-content">
                    <div class="form-group">
<?
    foreach ($fields as $value) {
        if (isset($value["edit"]) && "on" == @$value["edit"]){
            switch ($value["type"]) {
                case "input":
?>
                        <div class="col-sm-12">
                            <div class="group-inline">
                                <label class="control-label">
                                    <span class="tag-required glyphicon glyphicon-asterisk"></span>
                                    <?=@$value["zh"]?>

                                </label>
                                <div class="">
                                    <input class="form-control msg-detail" type="text" name="<?=@$page?>[<?=@$value["en"]?>]" ng-model="<?=$primary?>EC.info.<?=@$value["en"]?>">
                                </div>
                            </div>
                        </div>
<?
                    break;
                case "select":
?>
                        <div class="col-sm-12">
                            <div class="group-inline">
                                <label class="control-label">
                                    <span class="tag-required glyphicon glyphicon-asterisk"></span>
                                    <?=@$value["zh"]?>

                                </label>
                                <div class="">
                                    <select id="<?=@$value["en"]?>" class="form-control msg-detail" name="<?=@$page?>[<?=@$value["en"]?>]" ng-model="<?=$primary?>EC.info.<?=@$value["en"]?>">
<?
                                        $options = CheckUtil::getValue($value,"statusName");
                                        if ("" == $options){
                                            $options = @$page."_".$value["en"]."";
                                        }
                                        $options = strtoupper($options);
                                        echo <<<EOT
                                        <option value=""><?=Msgs::SELECT_HOLDER ?></option>
                                        <?
                                        foreach (Constant::\$$options as \$key => \$value) {
                                            ?>
                                            <option value="<?=\$key?>"><?=\$value?></option>
                                            <?
                                        }
                                        ?>

EOT;
?>
                                    </select>
                                </div>
                            </div>
                        </div>
<?
                    break;
                case "time":
?>
                        <div class="col-sm-12">
                            <div class="group-inline">
                                <label class="control-label">
                                    <span class="tag-required glyphicon glyphicon-asterisk"></span>
                                    <?=@$value["zh"]?>

                                </label>
                                <div class="">
                                    <input time="hide" format="YYYY-MM-DD" def-laydate class="form-control msg-detail laydate-icon laydate-icon-default cursor" type="text" name="<?=@$page?>[<?=@$value["en"]?>]" ng-model="<?=$primary?>EC.info.<?=@$value["en"]?>" readonly>
                                </div>
                            </div>
                        </div>
<?
                    break;
                case "text":
?>
                        <div class="col-sm-12">
                            <div class="group-inline">
                                <label class="control-label">
                                    <span class="tag-required glyphicon glyphicon-asterisk"></span>
                                    <?=@$value["zh"]?>

                                </label>
                                <div class="">
                                    <textarea name="<?=@$page?>[<?=@$value["en"]?>]" rows="4" class="form-control" ng-model="<?=$primary?>EC.info.<?=@$value["en"]?>"></textarea>
                                </div>
                            </div>
                        </div>
<?
                    break;
                case "editor":
?>
                        <div class="col-sm-12">
                            <div class="group-inline">
                                <label class="control-label">
                                    <span class="tag-required glyphicon glyphicon-asterisk"></span>
                                    <?=@$value["zh"]?>

                                </label>
                                <div class="">
                                    <input type="hidden" name="<?=@$page?>[<?=@$value["en"]?>]" value="{{<?=$primary?>EC.info.<?=@$value["en"]?>}}">
                                    <ueditor ng-model="<?=$primary?>EC.info.<?=@$value["en"]?>" class="ueditor"></ueditor>
                                </div>
                            </div>
                        </div>
<?
                    break;
                case "photo":
?>
                        <div class="col-sm-12">
                            <div class="group-inline">
                                <label class="control-label">
                                    <span class="tag-required glyphicon glyphicon-asterisk"></span>
                                    <?=@$value["zh"]?>

                                </label>
                                <div class="">
                                    <input type="hidden" name="<?=@$page?>[<?=@$value["en"]?>]" value="{{<?=$primary?>EC.info.<?=@$value["en"]?>.path}}">
                                    <image-upload file-obj="<?=$primary?>EC.info.<?=@$value["en"]?>" file-config="<?=$primary?>EC.info.photoConfig" ></image-upload>
                                </div>
                            </div>
                        </div>
<?
                    break;
                default :
                    break;
            }
        }
    }
?>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-right">
            <div class="block">
                <div class="block-head">
                    备注信息
                </div>
                <div class="block-content">
                    <div class="form-group">

                    </div>
                </div>
            </div>
        </div>
        <div class="footer">
            <button ng-attr-id="{{'btn-<?=$page?>-edit'+<?=$primary?>EC.id}}" type="button" class="btn btn-dark" ng-click="<?=$primary?>EC.edit<?=ucfirst(@$page);?>();">确认</button>
            <button type="button" class="btn btn-default" ng-click="<?=$primary?>EC.close();">取消</button>
        </div>
    </form>
</div>