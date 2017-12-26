<div class="page-event-edit page-operate page-operate-new">
    <form ng-attr-id="{{'form-event-edit'+EEC.id}}" class="form-wukong form-horizontal" action="<?=Yii::app()->createUrl("/mgrapi/editevent")?>">
        <input type="hidden" name="info[id]" value="{{EEC.info.id}}">
        <input type="hidden" name="info[ver]" value="{{EEC.info.ver}}">
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
                                    <input time="show" format="YYYY-MM-DD" def-laydate class="form-control msg-detail laydate-icon laydate-icon-default cursor" type="text" name="info[occur_time]" ng-model="EEC.info.occur_time" readonly>
                                </div>
                            </div>
                        </div>
                        <form-group-input class="col-sm-12" input-title="'标题'" input-content="EEC.info.title" input-name="info[title]" input-required="true" input-mode="'model'"></form-group-input>
                        <form-group-input class="col-sm-12" input-title="'链接'" input-content="EEC.info.link" input-name="info[link]" input-required="true" input-mode="'model'"></form-group-input>
                        <form-group-input class="col-sm-12" input-type="'select'" input-items="ARRS.STAR" input-title="'等级'" input-content="EEC.info.star" input-name="info[star]" input-required="true" ></form-group-input>
                        <form-group-input class="col-sm-12" input-type="'select'" input-items="ARRS.AUTHOR" input-title="'编辑'" input-content="EEC.info.author" input-name="info[author]" input-required="true" ></form-group-input>

                        <form-group-input class="col-sm-12" input-type="'textarea'"  input-title="'点评'" input-content="EEC.info.comment" input-name="info[comment]" input-required="true" ></form-group-input>

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
            <button ng-attr-id="{{'btn-event-edit'+EEC.id}}" type="button" class="btn btn-dark" ng-click="EEC.editEvent();">确认</button>
            <button type="button" class="btn btn-default" ng-click="EEC.close();">取消</button>
        </div>
    </form>
</div>