<style type="text/css">
    .footer,div{
        overflow: visible;
        display: block;
    }
</style>
<div class="page-event-list page-list page-list-new">
    <div class="btn-con-float">
    </div>
    <div class="airi-con">
        <div class="weui-cell-simple action-con-top" >
            <select ng-model="ELC.eventLogSearch.search.keywords.period" class="weui-cell__hd period-selector form-control uib-date-picker" ng-click="ELC.openPeriod()" >
                <option value="">全部</option>
                <option value="{{key}}" ng-repeat="(key,value) in ARRS.EVENT_PERIOD">{{value}}</option>
            </select>

            <input type="text" class=" weui-cell__hd form-control msg-detail laydate-icon laydate-icon-default cursor date-selector uib-form-control uib-date-picker cursor" uib-datepicker-popup="{{ELC.date.format}}" ng-model="ELC.date.current_date" is-open="ELC.date.opened" datepicker-options="ELC.date.dateOptions" ng-required="false" close-text="Close" alt-input-formats="ELC.date.altInputFormats" readonly ng-click="ELC.date.open()"/>

            <div class="weui-cell__bd">
                <div class="weui-cell__holder"></div>
            </div>
            <div class="weui-cell__ft">
                <button class="btn btn-dark btn-no-float" type="button" ng-click="ELC.addEvent(false);">添加</button>
            </div>

        </div>
        <div class="airi-scroll-con" ng-style="{height: ELC.getHeight()}" ng-attr-id="{{'event-con-'+ELC.id}}" ng-show="ELC.eventLog.load.itemList&&ELC.eventLog.load.itemList.length >0">
            <div class="airi-scroll-inner">
                <div class="weui-cell-simple event-block" ng-repeat="info in ELC.eventLog.load.itemList">
                    <div class="weui-cell__hd cell-star">
                        <div class="event-header-con">
                            <div class="event-occur-time">{{info.display_occur_time}}</div>
                            <div class="event-star">
                                <span uib-rating ng-model="info.star" max="info.star" read-only="true" titles="['一星','二星','三星']" aria-labelledby="default-rating"></span>
                            </div>
                        </div>
                    </div>
                    <div class="weui-cell__hd cell-title">
                        <div class="event-title text-limit-3">{{info.title}}</div>
                    </div>
                    <div class="weui-cell__bd cell-comment">
                        <div class="event-comment text-limit-7">{{info.comment}}</div>
                    </div>
                    <div class="weui-cell__ft cell-author">
                        <div class="event-author">{{info.author}}</div>
                    </div>
                    <div class="weui-cell__ft cell-actions">
                        <div class="event-actions"><button class="btn btn-link" type="button" ng-click="ELC.editEvent(info.id)">编辑</button></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="airi-empty-con" ng-show="ELC.eventLog.load.end && (!ELC.eventLog.load.itemList ||ELC.eventLog.load.itemList.length == 0) ">
            <div class="airi-empty-tip">无相关数据</div>
        </div>
    </div>
</div>