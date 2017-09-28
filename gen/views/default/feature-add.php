<?
$addLink = Yii::app()->createUrl('/gen/defaultapi/addfeature');
$editLink = Yii::app()->createUrl('/gen/defaultapi/editfeature');
?>
<div class="page page-feature-add">
    <div class="block">
       <!-- <div class="block-head">

        </div>-->
        <div class="block-content">
            <div class="col-md-10 col-md-offset-1">
                <form id="form-add-feature" class="form-horizontal" action="{{FAC.mode == 'edit'?'<?=$editLink?>':'<?=$addLink?>'}}">
                    <input id="node-code1" class="form-control col-md-8 hidden" type="text"  name="feature[id]"  value="{{FAC.current.id}}">

                    <div class="form-group-sm">
                        <label class="control-label col-md-4" for="node-code">
                            描述
                        </label>
                        <div class="">
                            <input id="node-code" class="form-control col-md-8" type="text"  name="feature[description]" placeholder="描述"  values="{{FAC.current.description}}" >
                        </div>
                    </div>
                    <div class="form-group-sm">
                        <label class="control-label col-md-4" for="node-code">
                            url
                        </label>
                        <div class="">
                            <input id="node-code" class="form-control col-md-8" type="text"  name="feature[url]" placeholder="url"  values="{{FAC.current.url}}" >
                        </div>
                    </div>
                    <div class="form-group-sm">
                        <label class="control-label col-md-4" for="node-code">
                            所有者
                        </label>
                        <div class="">
                            <input id="node-code" class="form-control col-md-8" type="text"  name="feature[owner]" placeholder="owner"  values="{{FAC.current.owner}}" >
                        </div>
                    </div>
                    <div class="form-group-sm">
                        <label class="control-label col-md-4" for="node-code">
                            版本（数字）
                        </label>
                        <div class="">
                            <input id="node-code" class="form-control col-md-8" type="text"  name="feature[version]" placeholder="version"  values="{{FAC.current.version}}" >
                        </div>
                    </div>
                    <div class="form-group-sm">
                        <div class="col-md-offset-4">
                            <button class="btn btn-dark" id="btn-add-feature" type="button" ng-click="FAC.feature.submitForm()">保存</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?
$this->widget('ext.modal.SimpleGroup',[
    'name'=>'ext.modal.UploadFormV1',
    'configs'=>[
        [
            'params'=>[
                'formId'=>'form-upload-screen',
                'type'=>'file'
            ]
        ],
    ]
]);
?>