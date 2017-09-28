<?
$ctrl = $page;
$upperctrl = strtoupper($page);
$lowerctrl = strtolower($page);
$camelctrl = ucfirst($page);
echo <<<EOF
<?php

class {$camelctrl}Importer extends BaseImporter {
    /**
     * This is the model class for table "b_base".
     *
     * The followings are the available columns in table 'b_base':
     * @property string \$creatememo
     * @property string \$created
     * @model [creatememo,\$created]
     */
    
    public function doImport()
    {
        for (\$row = 2;\$row <= \$this->highestRow ;\$row++){
            \$this->allCount++;
            \$createMemo = ExcelUtil::getStringValue(\$this->sheet,0,\$row);
            if(!\$createMemo){
                \$this->errorList[] = ['row'=>\$row,'error'=>'创建说明不能为空'];
                continue;
            }
            \$exist = {$camelctrl}::model()->exists('creatememo = :creatememo',['creatememo'=>\$createMemo]);
            if(\$exist){
                \$this->errorList[] = ['row'=>\$row,'error'=>'创建说明重复'];
                continue;
            }
            //科学计数法
            \$rawData = [
                'creatememo'=>ExcelUtil::getStringValue(\$this->sheet,0,\$row),
                'created'=>FormatUtil::date2ms(ExcelUtil::getStringValue(\$this->sheet,1,\$row)),
            ];
            \$addRet = {$camelctrl}Service::add{$camelctrl}(\$this->uid,\$rawData);
            if (\$addRet['ret']=='FAIL') {
                \$this->errorList[] = ['row'=>\$row,'error'=>\$addRet['data']];
            }else{
                \$this->successCount++;
            }
        }
        return ['ret'=>'SUCCESS','data'=>['success'=>\$this->successCount,'all'=>\$this->allCount,'fail'=>\$this->allCount-\$this->successCount,'errorList'=>\$this->errorList]];
    }

    public static function import(\$uid,\$files){
        \$importer = new {$camelctrl}Importer(\$uid,\$files);
        \$importer->objName = TplUtil::getObjName('{$camelctrl}');
        \$importer->files = \$files;
        \$ret = \$importer->internalImport();
        return \$ret;
    }
}
EOF;
