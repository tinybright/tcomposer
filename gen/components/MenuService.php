<?php

class MenuService extends BaseService{
    const ROOT_PARENT_ID = -1;
    const ROOT_ID = 1;
    const ROOT_NAME = 'root';
    const ROOT_DISPLAYNAME = '项目';

    public static function getChildList($parentId = -1,$page = 1,$pageSize = -1){
        $ct = BaseService::prepareCt('t.deleted = 0 AND parentid = :parentid',[
            'parentid'=>$parentId,
        ],'t.created ASC');
        return self::getListByCt(Menu::model(),$ct,$page,$pageSize);
    }

    public static function hasChild($parentId){
        return Menu::model()->exists('t.parentid = :parentid AND t.deleted = 0',['parentid'=>$parentId]);
    }

    public static function initRootMenu(){
        $rootMenu = Menu::model()->findByPk(self::ROOT_ID);
        $exist = 0;
        if($rootMenu){
            $exist = 1;
        }else{
            $rootMenu = new Menu();
        }

        $rootMenu->id = self::ROOT_ID;
        $rootMenu->name = self::ROOT_NAME;
        $rootMenu->displayname = self::ROOT_DISPLAYNAME;
        $rootMenu->parentid = self::ROOT_PARENT_ID;
        $rootMenu->idpath = MMenuUtil::genPath('#'.self::ROOT_PARENT_ID.'#',self::ROOT_ID);
        $rootMenu->level = 0;
        if($exist){
            $saveList = [
                'name','displayname','parentid','level','idpath'
            ];
            if($rootMenu->save(true,$saveList)){
                return ['ret'=>'FAIL'];
            }
        }else{
            if(!$rootMenu->save()){
                return ['ret'=>'FAIL','data'=>'保存失败','debug'=>$rootMenu->errors];
            }
        }

        return ['ret'=>'SUCCESS','data'=>$rootMenu->id];
    }

    /**
     * This is the model class for table "b_menu".
     *
     * The followings are the available columns in table 'b_menu':
     * @property string $id
     * @property string $name
     * @property string $diaplayname
     * @property string $parentid
     * @property string $parentname
     * @property string $url
     * @property integer $hide
     * @property string $iconnormal
     * @property string $iconactive
     * @property string $idpath
     * @property string $namepath
     * @property integer $level
     * @property string $creator
     * @property string $created
     * @property integer $deleted
     * @property string $updated
     */
    public static function createMenu($uid,$params){
        $checkRet = CheckUtil::checkParams($params,[
            'name','parentid',
            /*'url'*/
        ],[
            '菜单名称不能为空',
            '父元素ID不能为空',
            /*'url不能为空',*/
        ]);
        if($checkRet['ret'] == 'FAIL'){
            return $checkRet;
        }
        $name = CheckUtil::getValue($params,'name');
        $parentid = CheckUtil::getValue($params,'parentid');
        $oldMenu = Menu::model()->findByAttributes([
            'name'=>$name,
            'deleted'=>0,
        ]);
        if(CheckUtil::isExist($oldMenu)){
            return ['ret'=>'FAIL','data'=>'菜单名称不能重复'];
        }

        $parentMenu = Menu::model()->findByPk($parentid,'deleted = 0');
        if(!CheckUtil::isExist($parentMenu)){
            return ['ret'=>'FAIL','data'=>'父菜单不存在','debug'=>$parentid];
        }

        $menu = new Menu();

        $simpleAttrList = [
            'name',
            'displayname',
            'parentid',
            'featureid',
            'url',
            'hide',
            'iconnormal',
            'iconactive',
        ];
        foreach ($simpleAttrList as $simpleAttr){
            $menu->setAttribute($simpleAttr,CheckUtil::getValue($params,$simpleAttr));
        }
        $menu->parentname = $parentMenu->name;
        $menu->hide = CheckUtil::getValue($params,'hide')?1:0;
        $menu->namepath = MMenuUtil::genPath($parentMenu->namepath,$menu->name);
        $menu->level = $parentMenu->level+1;
        $menu->creator = $uid;
        $menu->created = date('Y-m-d H:i:s');
        $menu->deleted = 0;

        if(!$menu->save()){
            return ['ret'=>'FAIL','data'=>'保存菜单失败','debug'=>$menu->errors];
        }
        $menu->idpath = MMenuUtil::genPath($parentMenu->idpath,$menu->id);
        $saveList = ['idpath'];
        if(!$menu->save(true,$saveList)){
            return ['ret'=>'FAIL','data'=>'保存菜单失败','debug'=>$menu->errors];
        }
        MenuCache::dirtyMenu($menu->id);
        return ['ret'=>'SUCCESS','data'=>$menu->id];
    }

    public static function createProject($uid,$params){
        $parentid = self::ROOT_ID;
        $params['parentid'] = $parentid;
        $checkRet = CheckUtil::checkParams($params,[
            'name','parentid',
            /*'url'*/
        ],[
            '菜单名称不能为空',
            '父元素ID不能为空',
            /*'url不能为空',*/
        ]);
        if($checkRet['ret'] == 'FAIL'){
            return $checkRet;
        }
        $name = CheckUtil::getValue($params,'name');
        $parentid = CheckUtil::getValue($params,'parentid');
        $oldMenu = Menu::model()->findByAttributes([
            'name'=>$name,
            'deleted'=>0,
        ]);
        if(CheckUtil::isExist($oldMenu)){
            return ['ret'=>'FAIL','data'=>'菜单名称不能重复'];
        }

        $parentMenu = Menu::model()->findByPk($parentid,'deleted = 0');
        if(!CheckUtil::isExist($parentMenu)){
            return ['ret'=>'FAIL','data'=>'父菜单不存在','debug'=>$parentid];
        }

        $menu = new Menu();

        $simpleAttrList = [
            'name',
            'displayname',
            'parentid',
            'featureid',
            'url',
            'hide',
            'iconnormal',
            'iconactive',
        ];
        foreach ($simpleAttrList as $simpleAttr){
            $menu->setAttribute($simpleAttr,CheckUtil::getValue($params,$simpleAttr));
        }
        $menu->parentname = $parentMenu->name;
        $menu->hide = CheckUtil::getValue($params,'hide')?1:0;
        $menu->namepath = MMenuUtil::genPath($parentMenu->namepath,$menu->name);
        $menu->level = $parentMenu->level+1;
        $menu->creator = $uid;
        $menu->created = date('Y-m-d H:i:s');
        $menu->deleted = 0;

        if(!$menu->save()){
            return ['ret'=>'FAIL','data'=>'保存菜单失败','debug'=>$menu->errors];
        }
        $menu->idpath = MMenuUtil::genPath($parentMenu->idpath,$menu->id);
        $saveList = ['idpath'];
        if(!$menu->save(true,$saveList)){
            return ['ret'=>'FAIL','data'=>'保存菜单失败','debug'=>$menu->errors];
        }
        MenuCache::dirtyMenu($menu->id);
        return ['ret'=>'SUCCESS','data'=>$menu->id];
    }

    public static function editMenu($uid,$menuId,$params){
        $checkRet = CheckUtil::checkParams($params,[
            'name'
        ],[
            '菜单名称不能为空',
        ]);
        if($checkRet['ret'] == 'FAIL'){
            return $checkRet;
        }
        $name = CheckUtil::getValue($params,'name');
        $oldMenu = Menu::model()->findByPk($menuId);
        if(!CheckUtil::isExist($oldMenu)){
            return ['ret'=>'FAIL','data'=>'不存在'];
        }
        $oldRepeatMenu = Menu::model()->findByAttributes([
            'name'=>$name,
            'deleted'=>0,
        ],'id != :id',[
            'id'=>$menuId
        ]);
        if(CheckUtil::isExist($oldRepeatMenu)){
            return ['ret'=>'FAIL','data'=>'菜单名称不能重复'];
        }
        $parentMenu = Menu::model()->findByPk($oldMenu->parentid,'deleted = 0');
        if($menuId == self::ROOT_ID){
            $parentMenu = (object)[
                'idpath'=>'',
            ];
        }else{
            if(!CheckUtil::isExist($parentMenu)){
                return ['ret'=>'FAIL','data'=>'父菜单不存在'];
            }
        }
        $simpleAttrList = [
            'name',
            'displayname',
            'featureid',
            'url',
            'hide',
            'iconnormal',
            'iconactive',
        ];
        foreach ($simpleAttrList as $simpleAttr){
            $oldMenu->setAttribute($simpleAttr,CheckUtil::getValue($params,$simpleAttr));
        }
        $oldMenu->hide = CheckUtil::getValue($params,'hide')?1:0;
        $oldMenu->idpath = MMenuUtil::genPath($parentMenu->idpath,$oldMenu->id);
        $saveList = array_merge($simpleAttrList,[
           'idpath'
        ]);
        if(!$oldMenu->save(true,$saveList)){
            return ['ret'=>'FAIL','data'=>'保存菜单失败','debug'=>$oldMenu->errors];
        }
        MenuCache::dirtyMenu($oldMenu->id);
        return ['ret'=>'SUCCESS','data'=>$oldMenu->id];
    }

    public static function demo(){
        $levels = 2;
        $retList = [];
        for ($i = 0 ;$i<$levels;$i++){
            $ret = self::createOneLevel($i);
            $retList[] = $ret;
        }
        return $retList;
    }

    public static function createOneLevel($level = 0){
        $num = 5;
        $menuList = Menu::model()->findAllByAttributes(['level'=>$level],'deleted = 0');
        $errorList = [];
        if($menuList){
            for ($i = 0 ;$i<count($menuList) ;$i++){
                $pMenu = $menuList[$i];
                for ($j =0 ;$j<$num;$j++){
                    $ret = self::createMenu(0,[
                        'name'=>$pMenu->name.'_'.$j.''.time(),
                        'displayname'=>$pMenu->displayname.'_'.$j,
                        'parentid'=>$pMenu->id,
                        'url'=>$pMenu->url.'/'.$j,
                        'hide'=>0,
                        'iconnormal'=>'icon'.$j,
                        'iconactive'=>'icon'.$j,
                    ]);
                    if($ret['ret'] == 'FAIL'){
                        $errorList[] = $ret;
                    }else{
                    }
                }
            }
        }
        return $errorList;
    }

    public static function deleteMenu($uid,$nodeId,$force=false){
        $node = Menu::model()->findByPk($nodeId,'t.deleted = 0');
        if(!CheckUtil::isExist($node)){
            return ['ret'=>'SUCCESS'];
        }
        if($node->id == self::ROOT_ID){
            return ['ret'=>'FAIL','data'=>'该菜单无法删除'];
        }
        $hasChild = self::hasChild($nodeId);
        if($hasChild){
            if($force){
                $updateRows = Menu::model()->updateAll(['deleted'=>1], 'idpath LIKE :idpath',
                    [
                        'idpath'=>'%'.MMenuUtil::wrapKeyword($node->id).'%',
                    ]);
                return ['ret'=>'SUCCESS','data'=>$updateRows];
            }
            return ['ret'=>'FAIL','data'=>'请先处理子菜单'];
        }
        $rows = Menu::model()->updateByPk($nodeId,['deleted'=>1]);
        if(!$rows){
            return ['ret'=>'FAIL','data'=>'删除失败'];
        }
        MenuCache::dirtyMenu($nodeId);
        return ['ret'=>'SUCCESS','data'=>'删除成功'];
    }

    public static function moveMenu($uid,$nodeId,$targetId,$formId = ''){
        $node = Menu::model()->findByPk($nodeId,'t.deleted = 0');
        if(!$node || $node->deleted){
            return ['ret'=>'FAIL','data'=>'操作菜单不存在','debug'.$nodeId];
        }
        if($node->parentid == $targetId){
            return ['ret'=>'SUCCESS'];
        }
        if($targetId>0){
            $targetNode = Menu::model()->findByPk($targetId,'t.deleted = 0');
            if(!$targetNode || $targetNode->deleted){
                return ['ret'=>'FAIL','data'=>'目标菜单不存在'];
            }
        }else{
            return ['ret'=>'FAIL','data'=>'未知的目标菜单'];
        }

        $trans = DbUtil::getTrans();
        $transError = ['ret'=>'FAIL','data'=>'移动失败'];
        try{
            $oldNodeIdPath = $node->idpath;
            $node->parentid = $targetNode->id;
            $node->parentname = $targetNode->name;
            $node->idpath = MMenuUtil::genPath($targetNode->idpath,$node->id);
            $node->level = $targetNode->level+1;
            if(!$node->save(true,['parentid','parentname','idpath','level'])){
                $transError['debug'] = $node->errors;
                throw new Exception;
            }

            $relativedNodeList = Menu::model()->findAll(BaseService::prepareCt(
                'idpath LIKE :idpath AND id != :id',
                [
                    'idpath'=>'%'.MMenuUtil::wrapKeyword($node->id).'%',
                    'id'=>$node->id
                ]
            ));
            $updateRows = Menu::model()->updateAll(['idpath'=>new CDbExpression('REPLACE(idpath,:oldidpath,:newidpath)',[
                'oldidpath'=>$oldNodeIdPath,
                'newidpath'=>$node->idpath
            ])], ' idpath LIKE :idpath AND id != :id',
                [
                    'idpath'=>'%'.MMenuUtil::wrapKeyword($node->id).'%',
                    'id'=>$node->id
                ]);

            if($relativedNodeList){
                foreach ($relativedNodeList as $relativeNode){
                    MenuCache::dirtyNode($relativeNode->id);
                }
            }
            $trans->commit();
        }catch (Exception $e){
            $trans->rollback();
            $transError['trans'] = $e->getMessage();
            return $transError;
        }
        MenuCache::dirtyNode($node->id);
        return ['ret'=>'SUCCESS','data'=>'','update'=>count(@$updateRows),'relative'=>count($relativedNodeList)];
    }

    public static function exportMenu($uid,$menuId){
        $project = Menu::model()->findByAttributes([
            'deleted'=>0,
            'parentid'=>self::ROOT_ID,
            'id'=>$menuId,
        ]);
        if(!CheckUtil::isExist($project)){
            return ['ret'=>'FAIL','data'=>'项目不存在'];
        }
        $projectAttrs = MMenuUtil::menu2Array($project,'project');
        $menuList = Menu::model()->findAllByAttributes([
            'deleted'=>0,
            'parentid'=>$project->id
        ]);
        $tempMenuList = [];
        if($menuList){
            foreach ($menuList as $menu){
                $menuAttrs = [
                    $menu->displayname,[$menu->iconnormal,$menu->iconactive],[],$menu->hide?true:false
                ];

                $subMenuList = Menu::model()->findAllByAttributes([
                    'deleted'=>0,
                    'parentid'=>$menu->id
                ]);
                $tempSubMenuList = [];
                if($subMenuList){
                    foreach ($subMenuList as $subMenu){
                        $subMenuAttrs = [
                            $subMenu->displayname,[$subMenu->iconnormal,$subMenu->iconactive],$subMenu->url,$subMenu->hide?true:false
                        ];
                        $tempSubMenuList[] = $subMenuAttrs;
                    }
                }
                $menuAttrs[2] = $tempSubMenuList;
                $tempMenuList[] = $menuAttrs;
            }
        }
        $projectAttrs['menus'] = $tempMenuList;
        /*$retList[] = $projectAttrs;*/
        MenuCache::setMenuList($project->name,$tempMenuList);
        return ['ret'=>'SUCCESS','data'=>$tempMenuList,'name'=>$project->name];
    }

    public static function exportMenuAll(){
        $projectList = Menu::model()->findAllByAttributes([
            'deleted'=>0,
            'parentid'=>self::ROOT_ID,
        ]);
        $retList = [];
        if($projectList){
            foreach ($projectList as $project){
                $projectAttrs = MMenuUtil::menu2Array($project,'project');
                $menuList = Menu::model()->findAllByAttributes([
                    'deleted'=>0,
                    'parentid'=>$project->id
                ]);
                $tempMenuList = [];
                if($menuList){
                    foreach ($menuList as $menu){
                        $menuAttrs = [
                            $menu->displayname,[$menu->iconnormal,$menu->iconactive],[],$menu->hide?true:false
                        ];

                        $subMenuList = Menu::model()->findAllByAttributes([
                            'deleted'=>0,
                            'parentid'=>$menu->id
                        ]);
                        $tempSubMenuList = [];
                        if($subMenuList){
                            foreach ($subMenuList as $subMenu){
                                $subMenuAttrs = [
                                    $subMenu->displayname,[$subMenu->iconnormal,$subMenu->iconactive],$subMenu->url,$subMenu->hide?true:false
                                ];
                                $tempSubMenuList[] = $subMenuAttrs;
                            }
                        }
                        $menuAttrs[2] = $tempSubMenuList;
                        $tempMenuList[] = $menuAttrs;
                    }
                }
                $projectAttrs['menus'] = $tempMenuList;
                $retList[] = $projectAttrs;
            }
        }
        if($retList){
            foreach ($retList as $ret){
                MenuCache::setMenuList($ret['name'],$ret['menus']);
            }
        }
        return $retList;
    }


    public static function importMenu($appName = '',$nameDisplay = '',$menus = []){
        $trans = DbUtil::getTrans();
        $transError = DbUtil::getTransError('导入失败');
        try{
            $importRet = self::_importMenu($appName,$nameDisplay,$menus);
            $trans->commit();
        }catch (Exception $e){
            $trans->rollback();
            $transError['trans'] = $e->getMessage();
            return $transError;
        }
        return $importRet;
    }

    public static function _importMenu($appName = '',$nameDisplay = '',$menus = []){
        $nameDisplay = $nameDisplay?$nameDisplay:$appName;
        /*$appName = 'zijian';
        $menus = [
            ['数据',['http://image.lszhushou.com/2016/07/lszs1469182804350.png','http://image.lszhushou.com/2016/07/lszs1469182784010.png'],[
                ['用户管理',['http://image.lszhushou.com/2016/08/lszs1471930399964.png','http://image.lszhushou.com/2016/08/lszs1471930413435.png'],'/data/userlist'],
                ['数据统计',['http://image.lszhushou.com/2016/08/lszs1471930399964.png','http://image.lszhushou.com/2016/08/lszs1471930413435.png'],'/mgr/analysismain'],
                ['资源管理',['http://image.lszhushou.com/2016/08/lszs1471930399964.png','http://image.lszhushou.com/2016/08/lszs1471930413435.png'],'/mgr/resourcelist'],
                ['反馈管理',['http://image.lszhushou.com/2016/08/lszs1471930399964.png','http://image.lszhushou.com/2016/08/lszs1471930413435.png'],'/mgr/feedbacklist'],
                ['闪屏设置',['http://image.lszhushou.com/2016/08/lszs1471930399964.png','http://image.lszhushou.com/2016/08/lszs1471930413435.png'],'/mgr/splashset'],
                ['日志管理',['http://image.lszhushou.com/2016/08/lszs1471930399964.png','http://image.lszhushou.com/2016/08/lszs1471930413435.png'],'/admin/loglist'],
                ['编辑资源',['http://image.lszhushou.com/2016/08/lszs1471930399964.png','http://image.lszhushou.com/2016/08/lszs1471930413435.png'],'/mgr/editresource',true],
                ['申请列表',['http://image.lszhushou.com/2016/08/lszs1471930399964.png','http://image.lszhushou.com/2016/08/lszs1471930413435.png'],'/mgr/applylist'],
                ['用户邀请',['http://image.lszhushou.com/2016/08/lszs1471930399964.png','http://image.lszhushou.com/2016/08/lszs1471930413435.png'],'/mgr/invitelist'],
                ['申请列表1',['http://image.lszhushou.com/2016/08/lszs1471930399964.png','http://image.lszhushou.com/2016/08/lszs1471930413435.png'],'/mgr/userapplylist',true],
            ]],
            ['用户',null,[
                ['退出',['http://image.lszhushou.com/2016/06/lszs1465799914530.png','http://image.lszhushou.com/2016/06/lszs1465799924132.png','http://image.lszhushou.com/2016/06/lszs1465799933147.png'],'/sign/logout',true],
            ],true],
        ];*/

        $project = Menu::model()->findByAttributes([
            'deleted'=>0,
            'parentid'=>self::ROOT_ID,
            'name'=>$appName
        ]);

        if(!$project){
            $projectRet = self::createProject(0,[
                'name'=>$appName,
                'displayname'=>$nameDisplay,
                'parentid'=>self::ROOT_ID
            ]);
            if($projectRet['ret'] == 'FAIL'){
                return $projectRet;
            }
            $project = MenuCache::getMenu($projectRet['data']);
        }else{
            Menu::model()->updateByPk($project->id,[
                'displayname'=>$nameDisplay
            ]);
            MenuCache::dirtyMenu($project->id);
            $project = MenuCache::getMenu($project->id);
        }
        if(!$project){
            return ['ret'=>'FAIL','data'=>'项目不能为空'];
        }
        $deleteNum = Menu::model()->updateAll(
            ['deleted'=>1],
            'idpath LIKE :idpath AND id != :id',
            [
                'idpath'=>'%'.MMenuUtil::wrapKeyword($project->id).'%',
                'id'=>$project->id
            ]
        );
        foreach ($menus as $menu){
            $menuName = @$menu[0];
            $menuIconNormal = @$menu[1][0];
            $menuIconActive = @$menu[1][0];
            $menuHide = @$menu[3];
            $subMenuAttrsList = @$menu[2];
            $menuRet = MenuService::createMenu(0,[
                'name'=>$appName.'_'.$menuName,
                'displayname'=>$menuName,
                'iconnormal'=>$menuIconNormal,
                'iconactive'=>$menuIconActive,
                'hide'=>$menuHide?1:0,
                'parentid'=>$project->id
            ]);
            if($menuRet['ret'] == 'FAIL'){
                return $menuRet;
            }
            if($subMenuAttrsList){
                foreach ($subMenuAttrsList as $subMenuAttrs){
                    $subMenuName = @$subMenuAttrs[0];
                    $subMenuIconNormal = @$subMenuAttrs[1][0];
                    $subMenuIconActive = @$subMenuAttrs[1][0];
                    $subMenuHide = @$subMenuAttrs[3];
                    $subMenuUrl = @$subMenuAttrs[2];
                    $subMenuRet = MenuService::createMenu(0,[
                        'name'=>$appName.'_'.$menuName.'_'.$subMenuName,
                        'displayname'=>$subMenuName,
                        'iconnormal'=>$subMenuIconNormal,
                        'iconactive'=>$subMenuIconActive,
                        'hide'=>$subMenuHide?1:0,
                        'parentid'=>$menuRet['data'],
                        'url'=>$subMenuUrl
                    ]);
                    if($subMenuRet['ret'] == 'FAIL'){
                        return $subMenuRet;
                    }
                }
            }
        }
        return ['ret'=>'SUCCESS'];
    }
}