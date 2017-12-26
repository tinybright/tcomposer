<style type="text/css">
    .footer,div{
        overflow: visible;
        display: block;
    }
</style>
<div class="page-config-list page-list">
    <button class="btn btn-dark" type="button" ng-click="CLC.addConfig();">添加配置</button>
    <div class="">
        <div  ui-grid="CLC.grid.options" style="width: 100%; height: 500px; text-align: center;" ui-grid-edit ui-grid-pagination
              ui-grid-selection ui-grid-exporter ui-grid-resize-columns ui-grid-auto-resize  ui-grid-pinning >
        </div>
    </div>
</div>
