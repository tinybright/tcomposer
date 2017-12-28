<?
$ctrl = $page;
$samplectrl = strtoupper($page[0]);
$upperctrl = strtoupper($page);
$lowerctrl = lcfirst($page);
$oldctrl = ($page);
$linectrl = Utilities::toUnderScore($page);
$camelctrl = ucfirst($page);
echo <<<EOF
<div class="page-{$linectrl}-edit page-operate page-operate-new">
    <form ng-attr-id="{{'form-{$linectrl}-edit'+{$samplectrl}EC.id}}" class="form-muum form-horizontal" action="<?=Yii::app()->createUrl("/mgrapi/edit{$lowerctrl}")?>">
        <input type="hidden" name="info[id]" value="{{{$samplectrl}EC.info.id}}">
        <input type="hidden" name="info[ver]" value="{{{$samplectrl}EC.info.ver}}">
        <div class="form-left1 pull-left1 col-md-6 col-md-offset-3">
            <div class="block">
                <div class="block-content">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="group-inline">
                                <label class="control-label">
                                    <require-tag></require-tag>
                                    时间
                                </label>
                                <div class="">
                                    <input time="show" format="YYYY-MM-DD" def-laydate class="form-control msg-detail laydate-icon laydate-icon-default cursor" type="text" name="info[occur_time]" ng-model="{$samplectrl}EC.info.occur_time" readonly>
                                </div>
                            </div>
                        </div>
                        <form-group-input class="col-sm-12" input-title="'标题'" input-content="{$samplectrl}EC.info.title" input-name="info[title]" input-required="true" input-mode="'model'"></form-group-input>
                        <form-group-input class="col-sm-12" input-title="'链接'" input-content="{$samplectrl}EC.info.link" input-name="info[link]" input-required="true" input-mode="'model'"></form-group-input>
                        <form-group-input class="col-sm-12" input-type="'select'" input-items="ARRS.STAR" input-title="'等级'" input-content="{$samplectrl}EC.info.star" input-name="info[star]" input-required="true" ></form-group-input>
                        <form-group-input class="col-sm-12" input-type="'select'" input-items="ARRS.AUTHOR" input-title="'编辑'" input-content="{$samplectrl}EC.info.author" input-name="info[author]" input-required="true" ></form-group-input>

                        <form-group-input class="col-sm-12" input-type="'textarea'"  input-title="'点评'" input-content="{$samplectrl}EC.info.comment" input-name="info[comment]" input-required="true" ></form-group-input>

                    </div>
                </div>
            </div>
        </div>
        <div class="form-right1 hidden">
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
            <button ng-attr-id="{{'btn-{$linectrl}-edit'+{$samplectrl}EC.id}}" type="button" class="btn btn-dark" ng-click="{$samplectrl}EC.edit{$camelctrl}();">确认</button>
            <button type="button" class="btn btn-default" ng-click="{$samplectrl}EC.close();">取消</button>
        </div>
    </form>
</div>
EOF;
