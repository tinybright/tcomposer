<div class="page page-payfeature-item-list-finance">
    <div class="block">
        <div class="block-head bg-white">
            <button type="button" class="btn btn-dark pull-right" ng-click="FLC.route.toAdd()">添加功能点</button>
        </div>
        <div class="block-content block-border">
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
                        <input type="text" class="form-control" ng-model="FLC.featureSearch.search.keywords.description">
                    </td>
                    <td>
                        <input type="text" class="form-control" ng-model="FLC.featureSearch.search.keywords.url">
                    </td>
                    <td>
                        <input type="text" class="form-control" ng-model="FLC.featureSearch.search.keywords.owner">
                    </td>
                    <td>
                        <input type="text" class="form-control" ng-model="FLC.featureSearch.search.keywords.version">
                    </td>
                    <td>

                    </td>
                    <td>
                        <button class="btn btn-link" ng-click="FLC.featureSearch.search.reset()">清空搜索</button>
                    </td>
                </tr>
                <tr ng-repeat="feature in FLC.feature.load.itemList">
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
                        <button class="btn btn-link btn-check" ng-click="FLC.route.toEdit(feature)" >编辑</button>
                    </td>
                </tr>
                </tbody>
            </table>
            <?$this->widget('ext.angular.PageCtrl',['target'=>'FLC.feature.load'])?>
        </div>
    </div>
</div>

