<?
$ctrl = $page;
$samplectrl = strtoupper($page[0]);
$upperctrl = strtoupper($page);
$lowerctrl = lcfirst($page);
$oldctrl = ($page);
$linectrl = Utilities::toUnderScore($page);
$camelctrl = ucfirst($page);
echo <<<EOF
<div class="page-{$linectrl}-add page-operate page-operate-new">
    <form ng-attr-id="{{'form-{$linectrl}-add'+{$samplectrl}AC.id}}" class="form-muum form-horizontal" action="<?=Yii::app()->createUrl("/mgrapi/add{$lowerctrl}")?>">
        <div class="form-left1 col-md-6 col-md-offset-3">
            <div class="block">
<!--                <div class="block-head">基本信息</div>-->
                <div class="block-content">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="group-inline">
                                <label class="control-label">
                                    <require-tag></require-tag>
                                    时间
                                </label>
                                <div class="">
                                    <input time="show" format="YYYY-MM-DD" def-laydate class="form-control msg-detail laydate-icon laydate-icon-default cursor" type="text" name="info[occur_time]" ng-model="{$samplectrl}AC.info.occur_time" readonly>
                                </div>
                            </div>
                        </div>
                        <form-group-input class="col-sm-12" input-title="'标题'" input-content="{$samplectrl}AC.info.title" input-name="info[title]" input-required="true" input-mode="'model'"></form-group-input>
                        <form-group-input class="col-sm-12" input-title="'链接'" input-content="{$samplectrl}AC.info.link" input-name="info[link]" input-required="true" input-mode="'model'"></form-group-input>
                        <form-group-input class="col-sm-12" input-type="'select'" input-items="ARRS.STAR" input-title="'等级'" input-content="{$samplectrl}AC.info.star" input-name="info[star]" input-required="true" ></form-group-input>
                        <form-group-input class="col-sm-12" input-type="'select'" input-items="ARRS.AUTHOR" input-title="'编辑'" input-content="{$samplectrl}AC.info.author" input-name="info[author]" input-required="true" ></form-group-input>

                        <form-group-input class="col-sm-12" input-type="'textarea'"  input-title="'点评'" input-content="{$samplectrl}AC.info.comment" input-name="info[comment]" input-required="true" ></form-group-input>

                    </div>
                </div>
            </div>
        </div>
        <div class="form-right1 hidden">
            <div class="block">
                <div class="block-head">备注信息</div>
                <div class="block-content">
                    <div class="form-group">
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="footer">
            <button ng-attr-id="{{'btn-{$linectrl}-add'+{$samplectrl}AC.id}}" type="button" class="btn btn-dark" ng-click="{$samplectrl}AC.add{$camelctrl}();">确认</button>
            <button type="button" class="btn btn-default" ng-click="{$samplectrl}AC.close();">取消</button>
        </div>
    </form>
</div>
EOF;
