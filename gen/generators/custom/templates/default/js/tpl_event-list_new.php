<?
$ctrl = $page;
$samplectrl = strtoupper($page[0]);
$upperctrl = strtoupper($page);
$lowerctrl = lcfirst($page);
$oldctrl = ($page);
$linectrl = Utilities::toUnderScore($page);
$camelctrl = ucfirst($page);
echo <<<EOF
<style type="text/css">
    .footer,div{
        overflow: visible;
        display: block;
    }
</style>
<div class="page-{$linectrl}-list page-list page-list-new">
    <div class="btn-con-float">
    </div>
    <div class="airi-con">
        <div class="weui-cell-simple action-con-top" >
            <select ng-model="{$samplectrl}LC.{$lowerctrl}LogSearch.search.keywords.period" class="weui-cell__hd period-selector form-control uib-date-picker" ng-click="{$samplectrl}LC.openPeriod()" >
                <option value="">全部</option>
                <option value="{{key}}" ng-repeat="(key,value) in ARRS.{$upperctrl}_PERIOD">{{value}}</option>
            </select>

            <input type="text" class=" weui-cell__hd form-control msg-detail laydate-icon laydate-icon-default cursor date-selector uib-form-control uib-date-picker cursor" uib-datepicker-popup="{{{$samplectrl}LC.date.format}}" ng-model="{$samplectrl}LC.date.current_date" is-open="{$samplectrl}LC.date.opened" datepicker-options="{$samplectrl}LC.date.dateOptions" ng-required="false" close-text="Close" alt-input-formats="{$samplectrl}LC.date.altInputFormats" readonly ng-click="{$samplectrl}LC.date.open()"/>

            <div class="weui-cell__bd">
                <div class="weui-cell__holder"></div>
            </div>
            <div class="weui-cell__ft">
                <button class="btn btn-dark btn-no-float" type="button" ng-click="{$samplectrl}LC.add{$camelctrl}(false);">添加</button>
            </div>

        </div>
        <div class="airi-scroll-con" ng-style="{height: {$samplectrl}LC.getHeight()}" ng-attr-id="{{'{$linectrl}-con-'+{$samplectrl}LC.id}}" ng-show="{$samplectrl}LC.{$lowerctrl}Log.load.itemList&&{$samplectrl}LC.{$lowerctrl}Log.load.itemList.length >0">
            <div class="airi-scroll-inner">
                <div class="weui-cell-simple {$linectrl}-block" ng-repeat="info in {$samplectrl}LC.{$lowerctrl}Log.load.itemList">
                    <div class="weui-cell__hd cell-star">
                        <div class="{$linectrl}-header-con">
                            <div class="{$linectrl}-occur-time">{{info.display_occur_time}}</div>
                            <div class="{$linectrl}-star">
                                <span uib-rating ng-model="info.star" max="info.star" read-only="true" titles="['一星','二星','三星']" aria-labelledby="default-rating"></span>
                            </div>
                        </div>
                    </div>
                    <div class="weui-cell__hd cell-title">
                        <div class="{$linectrl}-title text-limit-3">{{info.title}}</div>
                    </div>
                    <div class="weui-cell__bd cell-comment">
                        <div class="{$linectrl}-comment text-limit-7">{{info.comment}}</div>
                    </div>
                    <div class="weui-cell__ft cell-author">
                        <div class="{$linectrl}-author">{{info.author}}</div>
                    </div>
                    <div class="weui-cell__ft cell-actions">
                        <div class="{$linectrl}-actions"><button class="btn btn-link" type="button" ng-click="{$samplectrl}LC.edit{$camelctrl}(info.id)">编辑</button></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="airi-empty-con" ng-show="{$samplectrl}LC.{$lowerctrl}Log.load.end && (!{$samplectrl}LC.{$lowerctrl}Log.load.itemList ||{$samplectrl}LC.{$lowerctrl}Log.load.itemList.length == 0) ">
            <div class="airi-empty-tip">无相关数据</div>
        </div>
    </div>
</div>
EOF;
