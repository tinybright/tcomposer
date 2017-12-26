<div class="page-config-add page-operate">
    <form ng-attr-id="{{'form-config-add'+CAC.id}}" class="form-wukong form-horizontal" action="<?=Yii::app()->createUrl("/mgrapi/addconfig")?>">
        <div class="form-left pull-left">
            <div class="block">
                <div class="block-head">基本信息</div>
                <div class="block-content">
                    <input name="info[uid]" value="{{CAC.info.uid}}" type="hidden"/>

                    <div class="form-group">
<!--                        <div class="col-sm-12">-->
<!--                            <div class="group-inline">-->
<!--                                <label class="control-label">-->
<!--                                    用户-->
<!--                                </label>-->
<!--                                <div class="relative-container ">-->
<!--                                    <input type="hidden" value="{{CAC.info.uid}}" name="info[uid]">-->
<!--                                    <input class="form-control msg-detail input-icon input-icon-more-noleft cursor" value="{{CAC.info.uid?CAC.info.user__nickname:'全局'}}" type="text" ng-click="CAC.pickUser()" readonly="">-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->

                        <form-group-input class="col-sm-12" input-type="'select'" input-items="ARRS.CONFIG_NAME" input-title="'类型'" input-content="CAC.info.key" input-name="info[key]" input-required="true" ></form-group-input>

                        <form-group-input ng-if="CAC.info.key != 'post_enable'" class="col-sm-12" input-title="'值'" input-content="CAC.info.value" input-name="info[value]" input-required="true" input-mode="'model'" ></form-group-input>

                        <form-group-input  ng-if="CAC.info.key == 'post_enable'" class="col-sm-12" input-type="'select'" input-items="ARRS.ENABLE_STATUS" input-title="'值'" input-content="CAC.info.value" input-name="info[value]" input-required="true" ></form-group-input>

<!--                        <div class="col-sm-6">-->
<!--                            <button type="button" class="btn btn-link" ng-click="CAC.showUserPicker()">-->
<!--                                添加检查员-->
<!--                            </button>-->
<!--                        </div>-->
                    </div>
                </div>
            </div>
        </div>
        <div class="form-right">

        </div>
        <div class="footer">
            <button ng-attr-id="{{'btn-config-add'+CAC.id}}" type="button" class="btn btn-dark" ng-click="CAC.addConfig();">确认</button>
            <button type="button" class="btn btn-default" ng-click="CAC.close();">取消</button>
        </div>
    </form>
</div>