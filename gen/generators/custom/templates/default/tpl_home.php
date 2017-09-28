<style type="text/css">
    body {
        background-color: #FAFAFA;
    }
</style>
<div class="page-<?=@$page?>-home page">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" ng-repeat="tab in TC.tabs" ng-init="TC.autoOpen(tab)" ng-mouseover="colseBtnShow=true" ng-mouseout="colseBtnShow=false">
            <a href="#{{tab.id}}" aria-controls="{{tab.id}}" role="tab" data-toggle="tab">{{tab.label}}</a>
            <button ng-show="colseBtnShow" class="closer-tab" type="button" ng-if="tab.closeable" ng-click="TC.closeTab(tab)">
                <span class="glyphicon glyphicon-remove-sign"></span>
            </button>
        </li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane" id="{{tab.id}}" ng-repeat="tab in TC.tabs" tab-content></div>
    </div>
</div>