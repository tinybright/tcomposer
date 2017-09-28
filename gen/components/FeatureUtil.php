<?php

class FeatureUtil{

    public static function feature2Array($feature,$scope = 'all'){
        $featureAttrs = [];
        if($feature){
            switch ($scope){
                case 'detail':
                    $featureAttrs = BaseUtil::obj2ArrayIgnore($feature,['deleted']);
                    $featureAttrs['screenList'] = json_decode(@$featureAttrs['screenlist'],true);
                    break;
                case 'list':
                    $featureAttrs = BaseUtil::obj2ArrayIgnore($feature,['deleted']);
                    $featureAttrs['screenList'] = json_decode(@$featureAttrs['screenlist'],true);
                    break;
                default:
                    throw new CHttpException(404,'NO SCOPE');
                    break;
            }
        }
        return $featureAttrs;
    }
}