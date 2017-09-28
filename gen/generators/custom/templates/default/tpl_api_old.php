
    public function actionImportEfficiency(){
        $uid = Yii::app()->user->id;
        $importEfficiency = EfficiencyService::importEfficiency($uid,$_FILES);
        echo json_encode($importEfficiency);
    }

    public function actionGetEfficiencyDetail($id = ''){
        if(!$id){
            echo json_encode(['ret'=>'FAIL','data'=>'参数缺失']);
            return;
        }
        $efficiency = Efficiency::model()->findByPk($id,'t.deleted = 0');
        if(!$efficiency){
            echo json_encode(['ret'=>'FAIL','data'=>'获取时效失败']);
            return;
        }
        $efficiencyAttrs = EfficiencyUtil::efficiency2Array($efficiency,'detail','vm');
        echo json_encode(['ret'=>'SUCCESS','data'=>$efficiencyAttrs]);
    }

    public function actionGetEfficiencyList($page = 1,$pagesize =Constant::DEFAULT_PAGESIZE){
        $efficiencyRet = EfficiencyService::getEfficiencyList($_GET,$page,$pagesize);
        if($efficiencyRet['list']){
            $list = [];
            foreach ($efficiencyRet['list'] as $efficiency){
                $efficiencyAttrs = EfficiencyUtil::efficiency2Array($efficiency,'list','vm');
                $list[] = $efficiencyAttrs;
            }
            $efficiencyRet['list'] = $list;
        }
        echo json_encode(['ret'=>'SUCCESS','data'=>$efficiencyRet]);
    }

    public function actionAddEfficiency(){
        if(!CheckUtil::isValueExist($_POST,'efficiency')){
            echo json_encode(['ret'=>'FAIL','data'=>Msgs::NO_PARAMS]);
            return;
        }
        $uid = Yii::app()->user->id;
        $params = CheckUtil::getValue($_POST,'efficiency');
        $addRet = EfficiencyService::addEfficiency($uid,$params);
        if($addRet['ret'] == 'SUCCESS'){
            $efficiency = KCache::getEfficiency(CheckUtil::getValue($addRet,'data'));
            if(!$efficiency){
                echo json_encode(['ret'=>'FAIL','data'=>'获取时效信息失败']);
                return;
            }else{
                $efficiencyAttr = EfficiencyUtil::efficiency2Array($efficiency,'detail',"vm");
                $addRet['data'] = $efficiencyAttr;
            }
        }
        echo json_encode($addRet);
    }

    public function actionEditEfficiency(){
        if(!CheckUtil::isValueExist($_POST,'efficiency')){
            echo json_encode(['ret'=>'FAIL','data'=>Msgs::NO_PARAMS]);
            return;
        }
        $uid = Yii::app()->user->id;
        $params = CheckUtil::getValue($_POST,'efficiency');
        $editRet = EfficiencyService::editEfficiency($uid,CheckUtil::getValue($params,'id'),$params);
        if($editRet['ret'] == 'SUCCESS'){
            $efficiency = KCache::getEfficiency(CheckUtil::getValue($params,'id'));
            if(!$efficiency){
                echo json_encode(['ret'=>'FAIL','data'=>'获取时效信息失败']);
                return;
            }else{
                $efficiencyAttr = EfficiencyUtil::efficiency2Array($efficiency,'detail',"vm");
                $editRet['data'] = $efficiencyAttr;
            }
        }
        echo json_encode($editRet);
    }

    public function actionDealEfficiency(){
        $uid = Yii::app()->user->id;
        if(!CheckUtil::isValueExist($_POST,'verb')){
            echo json_encode(['ret'=>'FAIL','data'=>'未知的操作']);
            return;
        }
        $verb = CheckUtil::getValue($_POST,'verb');
        $dealRet = EfficiencyService::_dealBaseBatch($uid,Utilities::string2List(CheckUtil::getValue($_POST,'id'),true),$_POST,[
            '_verb'=>$verb,
        ]);
        if($dealRet['ret'] == 'SUCCESS'){
            $list = [];
            foreach ($dealRet['data'] as $efficiencyid){
                $efficiency = KCache::getEfficiency($efficiencyid);
                if(!$efficiency){
                    $list[$efficiencyid] = "";
                    continue;
                }else{
                    $efficiencyAttr = EfficiencyUtil::efficiency2Array($efficiency,'audit','vm');
                }
                $list[$efficiencyid] = $efficiencyAttr;
            }
            $dealRet['data'] = $list;
        }
        echo json_encode($dealRet);
    }

    public function actionDeleteEfficiency(){
        $uid = Yii::app()->user->id;

        $dealRet = EfficiencyService::_dealBaseBatch($uid,Utilities::string2List(CheckUtil::getValue($_POST,'id'),true),$_POST,[
            '_verb'=>'efficiency_delete',
        ]);
        if($dealRet['ret'] == 'SUCCESS'){
            $list = [];
            foreach ($dealRet['data'] as $efficiencyid){
                $list[$efficiencyid] = $efficiencyid;
            }
            $dealRet['data'] = $list;
        }
        echo json_encode($dealRet);
    }