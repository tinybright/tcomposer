<?
$ctrl = $page;
$upperctrl = strtoupper($page);
$lowerctrl = strtolower($page);
$camelctrl = ucfirst($page);
echo <<<EOF
<?php

/**
 * This is the model class for table "b_base".
 *
 * The followings are the available columns in table 'b_base':
 * @property string \$id
 * @property string \$created
 * @property string \$creator
 * @property string \$creatememo
 * @property string \$status
 * @property integer \$deleted
 */
class {$camelctrl}Service extends BaseService{

    public static function get{$camelctrl}List(\$keywords = [],\$page = 1,\$pageSize = Constant::DEFAULT_PAGESIZE){
        \$ct = self::prepareCt('t.deleted = 0',[]);
        
        \$ct->order = 't.id DESC,t.created DESC';
        self::_completeCt(\$ct,\$keywords);
        return self::getListByCt({$camelctrl}::model(),\$ct,\$page,\$pageSize);
    }

    public static function import{$camelctrl}(\$uid,\$files){
        \$ret = {$camelctrl}Importer::import(\$uid,\$files);
        return \$ret;
    }

    public static function _completeCt(\$ct,\$keywords){
        if(\$keywords){
            \$equalList = [
                'status'
            ];
            \$likeList = [
                'creatememo'
            ];
            \$amountList = [

            ];
            \$periodList = [
                'created'
            ];

            /*base query*/
            self::_internalCompleteCt(\$ct,\$keywords,\$equalList,\$likeList,\$amountList,\$periodList);

            /*normal demo*/
            if(CheckUtil::isValueExist(\$keywords,'status')){
                \$keywordsStatus = CheckUtil::getValue(\$keywords,'status');
                \$ct->condition .= ' AND t.status = :status';
                \$ct->params['status'] = \$keywordsStatus;
            }

            /*relative demo*/
            if(CheckUtil::isValueExist(\$keywords,'transsuppliercode')){
                \$ct->condition .= ' AND EXISTS(SELECT 1 FROM t_trans_supplier ts WHERE ts.id = t.transsupplierid AND ts.code LIKE :transsuppliercode)';
                \$ct->params['transsuppliercode'] = DbUtil::prepareKeyword(\$keywords,'transsuppliercode');
            }
        }
    }

    public static function add{$camelctrl}(\$uid,\$params){
        \$_verb = '{$ctrl}_add';
        // rights
        \$rightsRet = RightUtil::hasRights(\$uid,\$_verb);
        if(\$rightsRet['ret'] != 'SUCCESS'){
            return \$rightsRet;
        }
        //todo
        \$checkRet = CheckUtil::checkParamsV2(\$params,[
            /*'creatememo'=>'创建备注不能为空',
            'created'=>'创建时间不能为空'*/
        ]);
        if(\$checkRet['ret'] == 'FAIL'){
            return \$checkRet;
        }

        \${$ctrl} = new {$camelctrl}();
        \$createMemo = CheckUtil::getValue(\$params,'creatememo');
        \$exist = {$camelctrl}::model()->exists('creatememo = :creatememo',['creatememo'=>\$createMemo]);
        if(\$exist){
            return ['ret'=>'FAIL','data'=>'已有创建备注相同的记录'];
        }

        \$simpleAttrList = [
            'creatememo',
        ];
        \$timeList = [
            'created',
        ];
        foreach (\$simpleAttrList as \$simpleAttr) {
            if(in_array(\$simpleAttr,\$timeList)) {
                \$rawVal = CheckUtil::getValue(\$params, \$simpleAttr);
                if (\$rawVal) {
                    if (is_numeric(\$rawVal)) {
                        \${$ctrl}->setAttribute(\$simpleAttr, date('Y-m-d H:i:s', \$rawVal / 1000));
                    } else {
                        \${$ctrl}->setAttribute(\$simpleAttr, date('Y-m-d H:i:s', strtotime(\$rawVal)));
                    }
                } else {
                    \${$ctrl}->setAttribute(\$simpleAttr, null);
                }
            }else{
                \${$ctrl}->setAttribute(\$simpleAttr,CheckUtil::getValue(\$params,\$simpleAttr));
            }
        }

        \$trans = DbUtil::getTrans();
        \$transError = ['ret'=>'FAIL','data'=>'添加'.TplUtil::getObjName('{$ctrl}').'失败'];
        try{
            \${$ctrl}->creator = \$uid;
            \${$ctrl}->created = date('Y-m-d H:i:s');
            \${$ctrl}->status = 'normal';
            \${$ctrl}->deleted = 0;
            if(!\${$ctrl}->save(true)){
                return ['ret'=>'FAIL','data'=>'添加'.TplUtil::getObjName('{$ctrl}').'失败','debug'=>\${$ctrl}->errors];
            }
            KCache::dirty{$camelctrl}(\${$ctrl}->id);
            /*todo addlog*/

            \$trans->commit();
        }catch (Exception \$e){
            \$transError['trans'] = \$e->getMessage();
            \$trans->rollback();
            return \$transError;
        }
        return ['ret'=>'SUCCESS','data'=>\${$ctrl}->id];
    }


    public static function edit{$camelctrl}(\$uid,\${$ctrl}Id,\$params){
        \$_verb = '{$ctrl}_edit';

        // rights
        \$rightsRet = RightUtil::hasRights(\$uid,\$_verb);
        if(\$rightsRet['ret'] != 'SUCCESS'){
            return \$rightsRet;
        }
        //todo
        \$checkRet = CheckUtil::checkParamsV2(\$params,[
            /*'creatememo'=>'创建备注不能为空',
            'created'=>'创建时间不能为空'*/
        ]);
        if(\$checkRet['ret'] == 'FAIL'){
            return \$checkRet;
        }

        \${$ctrl} = {$camelctrl}::model()->findByPk(\${$ctrl}Id,'t.deleted = 0');
        if(!CheckUtil::isExist(\${$ctrl})){
            return ['ret'=>'FAIL','data'=>TplUtil::getObjName('{$ctrl}').'不存在'];
        }
        \$createMemo = CheckUtil::getValue(\$params,'creatememo');
        \$exist = {$camelctrl}::model()->exists('creatememo = :creatememo AND id != :id',['creatememo'=>\$createMemo,'id'=>\${$ctrl}Id]);
        if(\$exist){
            return ['ret'=>'FAIL','data'=>'已有创建备注相同的记录'];
        }

        \$verRet = CheckUtil::checkVer(\${$ctrl},CheckUtil::getValue(\$params,'ver'));
        if(\$verRet['ret'] == 'FAIL'){
            return \$verRet;
        }
        \${$ctrl}Cached = \${$ctrl}->attributes;

        \$simpleAttrList = [
            'creatememo',
        ];
        \$timeList = [
            'created',
        ];
        foreach (\$simpleAttrList as \$simpleAttr) {
            if(in_array(\$simpleAttr,\$timeList)) {
                \$rawVal = CheckUtil::getValue(\$params, \$simpleAttr);
                if (\$rawVal) {
                    if (is_numeric(\$rawVal)) {
                        \${$ctrl}->setAttribute(\$simpleAttr, date('Y-m-d H:i:s', \$rawVal / 1000));
                    } else {
                        \${$ctrl}->setAttribute(\$simpleAttr, date('Y-m-d H:i:s', strtotime(\$rawVal)));
                    }
                } else {
                    \${$ctrl}->setAttribute(\$simpleAttr, null);
                }
            }else{
                \${$ctrl}->setAttribute(\$simpleAttr,CheckUtil::getValue(\$params,\$simpleAttr));
            }
        }

        \$sm = new {$camelctrl}SM();
        \$smRet = \$sm->sm(\${$ctrl}->status,\$_verb);
        if(\$smRet['ret'] == 'FAIL'){
            return \$smRet;
        }
        \$statusNew = \$smRet['data'];

        \$trans = DbUtil::getTrans();
        \$transError = ['ret'=>'FAIL','data'=>'编辑'.TplUtil::getObjName('{$ctrl}').'失败'];
        try{
            \${$ctrl}->status = \$statusNew;
            \$simpleAttrList[] = 'status';
            if(!\${$ctrl}->save(true,\$simpleAttrList)){
                return ['ret'=>'FAIL','data'=>'编辑'.TplUtil::getObjName('{$ctrl}').'失败','debug'=>\${$ctrl}->errors];
            }
            KCache::dirty{$camelctrl}(\${$ctrl}->id);
            //todo log
            \$trans->commit();
        }catch (Exception \$e){
            \$transError['trans'] = \$e->getMessage();
            \$trans->rollback();
            return \$transError;
        }
        return ['ret'=>'SUCCESS','data'=>\${$ctrl}->id];
    }

    public static function approve{$camelctrl}(\$uid,\${$ctrl}Id,\$params){
        return self::_dealBase(\$uid,\${$ctrl}Id,\$params,[
            '_verb'=>'{$ctrl}_approve',
        ]);
    }

    public static function reject{$camelctrl}(\$uid,\${$ctrl}Id,\$params){
        return self::_dealBase(\$uid,\${$ctrl}Id,\$params,[
            '_verb'=>'{$ctrl}_reject',
        ]);
    }

    public static function public{$camelctrl}(\$uid,\${$ctrl}Id,\$params){
        return self::_dealBase(\$uid,\${$ctrl}Id,\$params,[
            '_verb'=>'{$ctrl}_public',
        ]);
    }

    public static function _dealBaseBatch(\$uid,\${$ctrl}AttrsList,\$params,\$config = []){
        TplUtil::prepareSync();
        \$_verb = CheckUtil::getValue(\$config,'_verb');
        \$_action_verb = CheckUtil::getValue(\$config,'_action_verb');
        if(!\$_action_verb){
            \$_action_verb = CheckUtil::getValue(RightUtil::\$ACTION_VERB,\$_verb);
        }
        if(!\$_verb || !\$_action_verb){
            return ['ret'=>'FAIL','data'=>Msgs::UNKOWN_ACTION.'(1)'];
        }
        \$idList = [];
        \$trans = DbUtil::getTrans();
        \$transError = DbUtil::getTransError(\$_action_verb.'失败');
        try{
            foreach (\${$ctrl}AttrsList as \${$ctrl}Attrs){
                \$tempParams = [];
                \$tempParams['ver'] = CheckUtil::getValue(\${$ctrl}Attrs,'ver');
                \$tempParams['memo'] = CheckUtil::getValue(\$params,'memo');
                \$id = CheckUtil::getValue(\${$ctrl}Attrs,'id');
                \$taskRet = self::__dealBase(\$uid,\$id,\$tempParams,\$config ,\$_verb,\$transError);
                if(\$taskRet['ret'] == 'FAIL'){
                    \$transError = \$taskRet;
                    throw new Exception;
                    break;
                }
                \$idList[] = \$id;
            }
            \$trans->commit();
        }catch(Exception \$e){
            \$trans->rollback();
            \$transError['trans'] = \$e->getMessage();
            return \$transError;
        }
        //todo skip email
        /*\$resultEmailRet = EPCService::assembleAuditResultEmail(\$uid,\${$ctrl}->id,\$_verb);*/
        return ['ret'=>'SUCCESS','data'=>\$idList ,'action'=>\$_action_verb];
    }

    public static function _dealBase(\$uid,\${$ctrl}Id,\$params,\$config = []){
        //init
        \$_verb = CheckUtil::getValue(\$config,'_verb');
        \$_action_verb = CheckUtil::getValue(\$config,'_action_verb');
        if(!\$_action_verb){
            \$_action_verb = CheckUtil::getValue(RightUtil::\$ACTION_VERB,\$_verb);
        }
        if(!\$_verb || !\$_action_verb){
            return ['ret'=>'FAIL','data'=>Msgs::UNKOWN_ACTION.'(1)'];
        }
        \$trans = DbUtil::getTrans();
        \$transError = DbUtil::getTransError(\$_action_verb.'失败');
        try{
            \$taskRet = self::__dealBase(\$uid,\${$ctrl}Id,\$params,\$config = [],\$_verb,\$transError);
            if(\$taskRet['ret'] == 'FAIL'){
                \$transError = \$taskRet;
                throw new Exception;
            }
            \$trans->commit();
        }catch(Exception \$e){
            \$trans->rollback();
            \$transError['trans'] = \$e->getMessage();
            return \$transError;
        }
        //todo skip email
        /*\$resultEmailRet = EPCService::assembleAuditResultEmail(\$uid,\${$ctrl}->id,\$_verb);*/
        return ['ret'=>'SUCCESS','data'=>\${$ctrl}Id ,'action'=>\$_action_verb];
    }

    public static function __dealBase(\$uid,\${$ctrl}Id,\$params,\$config = [],\$_verb = '',\$transError = []){

        if(\$_verb!= '{$ctrl}_delete'){
            //todo check
            \$checkRet = CheckUtil::checkParamsV2(\$params,[
                'creatememo'=>[
                    CheckUtil::TEMPLATE_NOT_EMPTY=>'备注不能为空'
                ],
            ]);
            if(\$checkRet['ret'] == 'FAIL'){
                return \$checkRet;
            }
        }

        \$memo = CheckUtil::getValue(\$params,'memo');

        // rights
        \$skipVerb = [
            '{$ctrl}_add',
        ];
        if(!in_array(\$_verb,\$skipVerb)){
            \$rightsRet = RightUtil::hasRights(\$uid,\$_verb);
            if(\$rightsRet['ret'] != 'SUCCESS'){
                return \$rightsRet;
            }
        }

        \${$ctrl} = {$camelctrl}::model()->findByPk(\${$ctrl}Id,'t.deleted = 0');

        if(!CheckUtil::isExist(\${$ctrl})){
            return ['ret'=>'FAIL','data'=>TplUtil::getObjName('{$ctrl}').'不存在'];
        }
        \$verRet = CheckUtil::checkVer(\${$ctrl},CheckUtil::getValue(\$params,'ver'));
        if(\$verRet['ret'] == 'FAIL'){
            return \$verRet;
        }

        /*sm*/
        \$sm = new {$camelctrl}SM();
        \$smRet = \$sm->sm(\${$ctrl}->status,\$_verb);
        if(\$smRet['ret'] == 'FAIL'){
            return \$smRet;
        }
        \$statusNew = \$smRet['data'];

        \$extraData = ['from'=>\${$ctrl}->status];
        \${$ctrl}->status = \$statusNew;
        if(!\${$ctrl}->save(true,['status'])){
            \$transError['debug'] = \${$ctrl}->errors;
            return \$transError;
        }
        KCache::dirty{$camelctrl}(\${$ctrl}->id);
        //todo log
        return ['ret'=>'SUCCESS'];
    }

}
EOF;
