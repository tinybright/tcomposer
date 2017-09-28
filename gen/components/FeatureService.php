<?php

class FeatureService extends BaseService{
    /**
     * This is the model class for table "b_feature".
     *
     * The followings are the available columns in table 'b_feature':
     * @property string $id
     * @property string $owner
     * @property string $description
     * @property string $url
     * @property integer $version
     * @property string $screenlist
     * @property string $creator
     * @property string $created
     * @property integer $status
     * @property integer $deleted
     * @property string $updated
     */

    public static function getFeatureList($uid,$keywords,$page = 1,$pageSize = Constant::DEFAULT_PAGESIZE){
        $ct = self::prepareCt('t.deleted = 0',[]);
        $ct->order = 't.created DESC';
        self::_completeCt($ct,$keywords);
        return self::getListByCt(Feature::model(),$ct,$page,$pageSize);
    }

/*<td>
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
</td>*/
    public static function _completeCt($ct,$keywords){
        if($keywords){
            $equalList = [];
            foreach ($equalList as $equal){
                if(CheckUtil::isValueExist($keywords,$equal)){
                    $ct->condition .= ' AND t.'.$equal.' = :'.$equal;
                    $ct->params[$equal] = CheckUtil::getValue($keywords,$equal);
                }
            }
            $likeList = ['description','url','owner','version'];
            $amountList = [];
            foreach ($amountList as $amount){
                if(CheckUtil::isValueExist($keywords,$amount)){
                    $ct->condition .= ' AND t.'.$amount.' LIKE :'.$amount;
                    $ct->params[$amount] = DbUtil::prepareSearchKeyword(rtrim(CheckUtil::getValue($keywords,$amount,0) * 100, '0'));
                }
            }

            foreach ($likeList as $like){
                if(CheckUtil::isValueExist($keywords,$like)){
                    $ct->condition .= ' AND t.'.$like.' LIKE :'.$like;
                    $ct->params[$like] = DbUtil::prepareSearchKeyword(CheckUtil::getValue($keywords,$like));
                }
            }
        }
    }

    public static function addFeature($uid, $params){
        $checkRet = CheckUtil::checkParams($params,[
            'owner','description','url',
            /*'version','screenlist',*/
        ],[
            '所有者不能为空',
            '描述不能为空',
            'url不能为空',
        ]);
        if($checkRet['ret'] == 'FAIL'){
            return $checkRet;
        }
        $owner = CheckUtil::getValue($params,'owner');
        $description = CheckUtil::getValue($params,'description');
        $url = CheckUtil::getValue($params,'url');

        //不校验;
        $feature = new Feature();
        $simpleAttrList = [
            'owner',
            'description',
            'url',
            'version',
        ];
        foreach ($simpleAttrList as $simpleAttr){
            $feature->setAttribute($simpleAttr,CheckUtil::getValue($params,$simpleAttr));
        }
        $feature->screenlist = json_encode(CheckUtil::getValue($params,'screen'));
        $feature->creator = $uid;
        $feature->created = date('Y-m-d H:i:s');
        $feature->deleted = 0;
        if(!$feature->save()){
            return ['ret'=>'FAIL','data'=>'保存功能点失败','debug'=>$feature->errors];
        }
        MenuCache::dirtyFeature($feature->id);
        return ['ret'=>'SUCCESS','data'=>$feature->id];
    }

    public static function editFeature($uid, $params){
        $checkRet = CheckUtil::checkParams($params,[
            'id',
            'owner','description','url',
            /*'version','screenlist',*/
        ],[
            '功能点ID不能为空',
            '所有者不能为空',
            '描述不能为空',
            'url不能为空',
        ]);
        if($checkRet['ret'] == 'FAIL'){
            return $checkRet;
        }
        $owner = CheckUtil::getValue($params,'owner');
        $description = CheckUtil::getValue($params,'description');
        $url = CheckUtil::getValue($params,'url');

        //不校验;
        $feature = Feature::model()->findByPk(CheckUtil::getValue($params,'id'));
        if(!CheckUtil::isExist($feature)){
            return ['ret'=>'FAIL','data'=>'功能点不存在'];
        }
        $simpleAttrList = [
            'owner',
            'description',
            'url',
            'version',
        ];
        foreach ($simpleAttrList as $simpleAttr){
            $feature->setAttribute($simpleAttr,CheckUtil::getValue($params,$simpleAttr));
        }
        $feature->screenlist = json_encode(CheckUtil::getValue($params,'screen'));
        $saveList = array_merge($simpleAttrList,['screenlist']);
        if(!$feature->save(true,$saveList)){
            return ['ret'=>'FAIL','data'=>'保存功能点失败','debug'=>$feature->errors];
        }
        MenuCache::dirtyFeature($feature->id);
        return ['ret'=>'SUCCESS','data'=>$feature->id];
    }

    public static function deleteFeature($uid,$featureId){
        $usedNum = Menu::model()->findByAttributes([
            'featureid'=>$featureId,
            'deleted'=>0
        ]);
        if($usedNum){
            return ['ret'=>'FAIL','data'=>'功能点被'.$usedNum.'个菜单引用'];
        }
        $exist = Feature::model()->findByPk($featureId,'deleted = 0');
        if(!$exist){
            return ['ret'=>'FAIL','data'=>'功能点不存在'];
        }
        $updateRow = Feature::model()->updateByPk($featureId,['deleted'=>1]);
        if(!$updateRow){
            return ['ret'=>'FAIL','data'=>'删除功能点失败'];
        }
        MenuCache::dirtyFeature($featureId);
        return ['ret'=>'SUCCESS','data'=>$featureId];
    }

    public static function demo(){
        $max = 10;
        $addRetList = [];
        for ($i = 0 ;$i < $max ;$i++){
            $addRet = FeatureService::addFeature($i,[
                'owner'=>'owner'.$i,
                'description'=>'des'.$i,
                'url'=>'url'.$i,
                'version'=>$i,
                'screenlist'=>'screenlist'.$i,
            ]);
            $addRetList[] = $addRet;
        }
        $getListRet = self::getFeatureList(0,[],1,-1);

        return [$addRetList,$getListRet];
    }
}