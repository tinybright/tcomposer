<style type="text/css">
    .footer,div{
        overflow: visible;
        display: block;
    }
</style>
<page class="page-event-list  page-list page-list-new">
    <div class="btn-con-float">
        <button class="btn btn-dark btn-no-float" type="button" ng-click="ELC.addEvent();">添加</button>
    </div>
    <div class="">
        <div  ui-grid="ELC.grid.options" ui-grid-edit ui-grid-pagination
              ui-grid-selection ui-grid-exporter ui-grid-resize-columns ui-grid-auto-resize  ui-grid-pinning >
        </div>
    </div>
</page>

