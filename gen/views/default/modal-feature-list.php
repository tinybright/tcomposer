<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h5 class="modal-title">选择功能点</h5>
</div>
<div class="modal-body page">
    <div class="table-container relative-container">
        <table class="table table-lszs text-center">
            <?
            $cols = [
                ['描述','col-8w'],
                ['url','col-10w'],
                ['所有者','col-4w'],
                ['版本','col-2w'],
                ['截图','col-10w'],
                ['操作','col-8w'],
            ];
            ?>
            <? $this->widget('ext.table.TableTitle', [
                'cols' => $cols,
                /*'mode' => 'log'*/
            ]); ?>
            <tbody>

            <tr class="tr-search">
                <td>
                    <input type="text" class="form-control" ng-model="FMS.featureSearch.search.keywords.description">
                </td>
                <td>
                    <input type="text" class="form-control" ng-model="FMS.featureSearch.search.keywords.url">
                </td>
                <td>
                    <input type="text" class="form-control" ng-model="FMS.featureSearch.search.keywords.owner">
                </td>
                <td>
                    <input type="text" class="form-control" ng-model="FMS.featureSearch.search.keywords.version">
                </td>
                <td>

                </td>
                <td>
                    <button class="btn btn-link" ng-click="FMS.featureSearch.search.reset()">清空搜索</button>
                </td>
            </tr>
            <tr class="cursor" ng-repeat="feature in FMS.feature.load.itemList" ng-click="FMS.onItemSelected(feature)">
                <td class="col-holder">
                    {{feature.description }}
                </td>
                <td>
                    {{feature.url }}
                </td>
                <td>
                    {{feature.owner }}
                </td>
                <td>
                    {{feature.version }}
                </td>
                <td>
                    {{}}
                </td>
                <td class="text-center">
                    <!--<button class="btn btn-link btn-check" ng-click="FMS.route.toEdit(feature)" >编辑</button>-->
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <?$this->widget('ext.angular.PageCtrl',['target'=>'FMS.feature.load'])?>
</div>