<?php

class HolderUtil extends BaseUtil{

    public static function efficiency2Array($efficiency,$scope,$from = 'vm'){
        $efficiencyAttrs = [];
        if($efficiency){
            switch ($scope){
                case 'list':
                case 'audit':
                case 'detail':
                    $efficiencyAttrs = self::obj2ArrayIgnore($efficiency,[],[]);
                    $efficiencyAttrs = self::injectCreator($efficiencyAttrs);
                    $sm = new EfficiencySM();
                    $efficiencyAttrs['actions'] = $sm->getAction($efficiency);
                    break;
                default:
                    throw new CHttpException(404,Msgs::NO_SCOPE);
                    break;
            }
        }
        return $efficiencyAttrs;
    }

    public static function injectCreator($efficiencyAttrs){
        if($efficiencyAttrs && ($uid = CheckUtil::getValue($efficiencyAttrs,'creator'))){
            $user = KCache::getUser($uid);
            if($user){
                $efficiencyAttrs['creator'] = BaseUtil::obj2ArrayInclude($user,['id','realname','mobile']);
            }
        }
        return $efficiencyAttrs;
    }

}