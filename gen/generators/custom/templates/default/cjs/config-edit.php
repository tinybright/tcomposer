<div class="page-config-edit page-operate">
    <form ng-attr-id="{{'form-config-edit'+CEC.id}}" class="form-wukong form-horizontal" action="<?=Yii::app()->createUrl("/mgrapi/editconfig")?>">
        <input type="hidden" name="config[id]" value="{{CEC.info.id}}">
        <input type="hidden" name="config[ver]" value="{{CEC.info.ver}}">
        <div class="form-left pull-left">
            <div class="block">
                <div class="block-head">基本信息</div>
                <div class="block-content">
                    <div class="form-group">
                        <input type="hidden" value="{{CEC.info.id}}" name="info[id]">
                        <form-group-input class="col-sm-12" input-type="'input'" input-items="ARRS.CONFIG_NAME" input-title="'类型'" input-content="CEC.info.display_key" input-name="info[display_key]" input-required="true" input-readonly="true"></form-group-input>
<!--                        <form-group-input class="col-sm-12" input-title="'值'" input-content="CEC.info.value" input-name="info[value]" input-required="true" input-mode="'model'"></form-group-input>-->



                        <form-group-input ng-if="CEC.info.key != 'post_enable'" class="col-sm-12" input-title="'值'" input-content="CEC.info.value" input-name="info[value]" input-required="true" input-mode="'model'" ></form-group-input>

                        <form-group-input  ng-if="CEC.info.key == 'post_enable'" class="col-sm-12" input-type="'select'" input-items="ARRS.ENABLE_STATUS" input-title="'值'" input-content="CEC.info.value" input-name="info[value]" input-required="true" ></form-group-input>


                    </div>
                </div>
            </div>
        </div>
        <div class="form-right">
            <div class="block">

            </div>
        </div>
        <div class="footer">
            <button ng-attr-id="{{'btn-config-edit'+CEC.id}}" type="button" class="btn btn-dark" ng-click="CEC.editConfig();">确认</button>
            <button type="button" class="btn btn-default" ng-click="CEC.close();">取消</button>
        </div>
    </form>
</div>