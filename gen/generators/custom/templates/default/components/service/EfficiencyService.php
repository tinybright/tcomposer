<?php

/**
 * This is the model class for table "b_base".
 *
 * The followings are the available columns in table 'b_base':
 * @property string $id
 * @property string $created
 * @property string $creator
 * @property string $creatememo
 * @property string $status
 * @property integer $deleted
 */
class HolderService extends BaseService{

    public static function getEfficiencyList($keywords = [],$page = 1,$pageSize = Constant::DEFAULT_PAGESIZE){
        $ct = self::prepareCt('t.deleted = 0',[]);
        
        $ct->order = 't.id DESC,t.created DESC';
        self::_completeCt($ct,$keywords);
        return self::getListByCt(Efficiency::model(),$ct,$page,$pageSize);
    }

    public static function importEfficiency($uid,$files){
        $ret = EfficiencyImporter::import($uid,$files);
        return $ret;
    }

    public static function _completeCt($ct,$keywords){
        if($keywords){
            $equalList = [
                'status'
            ];
            $likeList = [
                'creatememo'
            ];
            $amountList = [

            ];
            $periodList = [
                'created'
            ];

            /*base query*/
            self::_internalCompleteCt($ct,$keywords,$equalList,$likeList,$amountList,$periodList);

            /*normal demo*/
            if(CheckUtil::isValueExist($keywords,'status')){
                $keywordsStatus = CheckUtil::getValue($keywords,'status');
                $ct->condition .= ' AND t.status = :status';
                $ct->params['status'] = $keywordsStatus;
            }

            /*relative demo*/
            if(CheckUtil::isValueExist($keywords,'transsuppliercode')){
                $ct->condition .= ' AND EXISTS(SELECT 1 FROM t_trans_supplier ts WHERE ts.id = t.transsupplierid AND ts.code LIKE :transsuppliercode)';
                $ct->params['transsuppliercode'] = DbUtil::prepareKeyword($keywords,'transsuppliercode');
            }
        }
    }

    public static function addEfficiency($uid,$params){
        $_verb = 'efficiency_add';
        // rights
        $rightsRet = RightUtil::hasRights($uid,$_verb);
        if($rightsRet['ret'] != 'SUCCESS'){
            return $rightsRet;
        }
        //todo
        $checkRet = CheckUtil::checkParamsV2($params,[
            /*'creatememo'=>'创建备注不能为空',
            'created'=>'创建时间不能为空'*/
        ]);
        if($checkRet['ret'] == 'FAIL'){
            return $checkRet;
        }

        $efficiency = new Efficiency();
        $createMemo = CheckUtil::getValue($params,'creatememo');
        $exist = Efficiency::model()->exists('creatememo = :creatememo',['creatememo'=>$createMemo]);
        if($exist){
            return ['ret'=>'FAIL','data'=>'已有创建备注相同的记录'];
        }

        $simpleAttrList = [
            'creatememo',
        ];
        $timeList = [
            'created',
        ];
        foreach ($simpleAttrList as $simpleAttr) {
            if(in_array($simpleAttr,$timeList)) {
                $rawVal = CheckUtil::getValue($params, $simpleAttr);
                if ($rawVal) {
                    if (is_numeric($rawVal)) {
                        $efficiency->setAttribute($simpleAttr, date('Y-m-d H:i:s', $rawVal / 1000));
                    } else {
                        $efficiency->setAttribute($simpleAttr, date('Y-m-d H:i:s', strtotime($rawVal)));
                    }
                } else {
                    $efficiency->setAttribute($simpleAttr, null);
                }
            }else{
                $efficiency->setAttribute($simpleAttr,CheckUtil::getValue($params,$simpleAttr));
            }
        }

        $trans = DbUtil::getTrans();
        $transError = ['ret'=>'FAIL','data'=>'添加'.TplUtil::getObjName('efficiency').'失败'];
        try{
            $efficiency->creator = $uid;
            $efficiency->created = date('Y-m-d H:i:s');
            $efficiency->status = 'normal';
            $efficiency->deleted = 0;
            if(!$efficiency->save(true)){
                return ['ret'=>'FAIL','data'=>'添加'.TplUtil::getObjName('efficiency').'失败','debug'=>$efficiency->errors];
            }
            KCache::dirtyEfficiency($efficiency->id);
            /*todo addlog*/

            $trans->commit();
        }catch (Exception $e){
            $transError['trans'] = $e->getMessage();
            $trans->rollback();
            return $transError;
        }
        return ['ret'=>'SUCCESS','data'=>$efficiency->id];
    }


    public static function editEfficiency($uid,$efficiencyId,$params){
        $_verb = 'efficiency_edit';

        // rights
        $rightsRet = RightUtil::hasRights($uid,$_verb);
        if($rightsRet['ret'] != 'SUCCESS'){
            return $rightsRet;
        }
        //todo
        $checkRet = CheckUtil::checkParamsV2($params,[
            /*'creatememo'=>'创建备注不能为空',
            'created'=>'创建时间不能为空'*/
        ]);
        if($checkRet['ret'] == 'FAIL'){
            return $checkRet;
        }

        $efficiency = Efficiency::model()->findByPk($efficiencyId,'t.deleted = 0');
        if(!CheckUtil::isExist($efficiency)){
            return ['ret'=>'FAIL','data'=>TplUtil::getObjName('efficiency').'不存在'];
        }
        $createMemo = CheckUtil::getValue($params,'creatememo');
        $exist = Efficiency::model()->exists('creatememo = :creatememo AND id != :id',['creatememo'=>$createMemo,'id'=>$efficiencyId]);
        if($exist){
            return ['ret'=>'FAIL','data'=>'已有创建备注相同的记录'];
        }

        $verRet = CheckUtil::checkVer($efficiency,CheckUtil::getValue($params,'ver'));
        if($verRet['ret'] == 'FAIL'){
            return $verRet;
        }
        $efficiencyCached = $efficiency->attributes;

        $simpleAttrList = [
            'creatememo',
        ];
        $timeList = [
            'created',
        ];
        foreach ($simpleAttrList as $simpleAttr) {
            if(in_array($simpleAttr,$timeList)) {
                $rawVal = CheckUtil::getValue($params, $simpleAttr);
                if ($rawVal) {
                    if (is_numeric($rawVal)) {
                        $efficiency->setAttribute($simpleAttr, date('Y-m-d H:i:s', $rawVal / 1000));
                    } else {
                        $efficiency->setAttribute($simpleAttr, date('Y-m-d H:i:s', strtotime($rawVal)));
                    }
                } else {
                    $efficiency->setAttribute($simpleAttr, null);
                }
            }else{
                $efficiency->setAttribute($simpleAttr,CheckUtil::getValue($params,$simpleAttr));
            }
        }

        $sm = new EfficiencySM();
        $smRet = $sm->sm($efficiency->status,$_verb);
        if($smRet['ret'] == 'FAIL'){
            return $smRet;
        }
        $statusNew = $smRet['data'];

        $trans = DbUtil::getTrans();
        $transError = ['ret'=>'FAIL','data'=>'编辑'.TplUtil::getObjName('efficiency').'失败'];
        try{
            $efficiency->status = $statusNew;
            $simpleAttrList[] = 'status';
            if(!$efficiency->save(true,$simpleAttrList)){
                return ['ret'=>'FAIL','data'=>'编辑'.TplUtil::getObjName('efficiency').'失败','debug'=>$efficiency->errors];
            }
            KCache::dirtyEfficiency($efficiency->id);
            //todo log
            $trans->commit();
        }catch (Exception $e){
            $transError['trans'] = $e->getMessage();
            $trans->rollback();
            return $transError;
        }
        return ['ret'=>'SUCCESS','data'=>$efficiency->id];
    }

    public static function approveEfficiency($uid,$efficiencyId,$params){
        return self::_dealBase($uid,$efficiencyId,$params,[
            '_verb'=>'efficiency_approve',
        ]);
    }

    public static function rejectEfficiency($uid,$efficiencyId,$params){
        return self::_dealBase($uid,$efficiencyId,$params,[
            '_verb'=>'efficiency_reject',
        ]);
    }

    public static function publicEfficiency($uid,$efficiencyId,$params){
        return self::_dealBase($uid,$efficiencyId,$params,[
            '_verb'=>'efficiency_public',
        ]);
    }

    public static function _dealBaseBatch($uid,$efficiencyAttrsList,$params,$config = []){
        TplUtil::prepareSync();
        $_verb = CheckUtil::getValue($config,'_verb');
        $_action_verb = CheckUtil::getValue($config,'_action_verb');
        if(!$_action_verb){
            $_action_verb = CheckUtil::getValue(RightUtil::$ACTION_VERB,$_verb);
        }
        if(!$_verb || !$_action_verb){
            return ['ret'=>'FAIL','data'=>Msgs::UNKOWN_ACTION.'(1)'];
        }
        $idList = [];
        $trans = DbUtil::getTrans();
        $transError = DbUtil::getTransError($_action_verb.'失败');
        try{
            foreach ($efficiencyAttrsList as $efficiencyAttrs){
                $tempParams = [];
                $tempParams['ver'] = CheckUtil::getValue($efficiencyAttrs,'ver');
                $tempParams['memo'] = CheckUtil::getValue($params,'memo');
                $id = CheckUtil::getValue($efficiencyAttrs,'id');
                $taskRet = self::__dealBase($uid,$id,$tempParams,$config ,$_verb,$transError);
                if($taskRet['ret'] == 'FAIL'){
                    $transError = $taskRet;
                    throw new Exception;
                    break;
                }
                $idList[] = $id;
            }
            $trans->commit();
        }catch(Exception $e){
            $trans->rollback();
            $transError['trans'] = $e->getMessage();
            return $transError;
        }
        //todo skip email
        /*$resultEmailRet = EPCService::assembleAuditResultEmail($uid,$efficiency->id,$_verb);*/
        return ['ret'=>'SUCCESS','data'=>$idList ,'action'=>$_action_verb];
    }

    public static function _dealBase($uid,$efficiencyId,$params,$config = []){
        //init
        $_verb = CheckUtil::getValue($config,'_verb');
        $_action_verb = CheckUtil::getValue($config,'_action_verb');
        if(!$_action_verb){
            $_action_verb = CheckUtil::getValue(RightUtil::$ACTION_VERB,$_verb);
        }
        if(!$_verb || !$_action_verb){
            return ['ret'=>'FAIL','data'=>Msgs::UNKOWN_ACTION.'(1)'];
        }
        $trans = DbUtil::getTrans();
        $transError = DbUtil::getTransError($_action_verb.'失败');
        try{
            $taskRet = self::__dealBase($uid,$efficiencyId,$params,$config = [],$_verb,$transError);
            if($taskRet['ret'] == 'FAIL'){
                $transError = $taskRet;
                throw new Exception;
            }
            $trans->commit();
        }catch(Exception $e){
            $trans->rollback();
            $transError['trans'] = $e->getMessage();
            return $transError;
        }
        //todo skip email
        /*$resultEmailRet = EPCService::assembleAuditResultEmail($uid,$efficiency->id,$_verb);*/
        return ['ret'=>'SUCCESS','data'=>$efficiencyId ,'action'=>$_action_verb];
    }

    public static function __dealBase($uid,$efficiencyId,$params,$config = [],$_verb = '',$transError = []){

        if($_verb!= 'efficiency_delete'){
            //todo check
            $checkRet = CheckUtil::checkParamsV2($params,[
                'creatememo'=>[
                    CheckUtil::TEMPLATE_NOT_EMPTY=>'备注不能为空'
                ],
            ]);
            if($checkRet['ret'] == 'FAIL'){
                return $checkRet;
            }
        }

        $memo = CheckUtil::getValue($params,'memo');

        // rights
        $skipVerb = [
            'efficiency_add',
        ];
        if(!in_array($_verb,$skipVerb)){
            $rightsRet = RightUtil::hasRights($uid,$_verb);
            if($rightsRet['ret'] != 'SUCCESS'){
                return $rightsRet;
            }
        }

        $efficiency = Efficiency::model()->findByPk($efficiencyId,'t.deleted = 0');

        if(!CheckUtil::isExist($efficiency)){
            return ['ret'=>'FAIL','data'=>TplUtil::getObjName('efficiency').'不存在'];
        }
        $verRet = CheckUtil::checkVer($efficiency,CheckUtil::getValue($params,'ver'));
        if($verRet['ret'] == 'FAIL'){
            return $verRet;
        }

        /*sm*/
        $sm = new EfficiencySM();
        $smRet = $sm->sm($efficiency->status,$_verb);
        if($smRet['ret'] == 'FAIL'){
            return $smRet;
        }
        $statusNew = $smRet['data'];

        $extraData = ['from'=>$efficiency->status];
        $efficiency->status = $statusNew;
        if(!$efficiency->save(true,['status'])){
            $transError['debug'] = $efficiency->errors;
            return $transError;
        }
        KCache::dirtyEfficiency($efficiency->id);
        //todo log
        return ['ret'=>'SUCCESS'];
    }

}