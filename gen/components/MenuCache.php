<?

class MenuCache {

	public static $PREFIX = '';

    public static function __callStatic($name,$arguments){
        if(preg_match('/^get([\w\d]+)/',$name,$matches)){
            // get obj
            $className = $matches[1];
            $class = new ReflectionClass($className);
            $method = $class->getMethod('model');
            $id = $arguments[0];
            $key = self::$PREFIX.$className.'_'.$id;
            $obj = Yii::app()->rediscache->get($key);
            if($obj === false) {
                $obj = $method->invoke($class)->findByPk($id);
                if($obj){
                    Yii::app()->rediscache->set($key,$obj);
                }
            }
            return $obj;
        }else if(preg_match('/^dirty([\w\d]+)Sn/',$name,$matches)){
            // dirty obj
            $className = $matches[1];
            $id = $arguments[0];
            $key = self::$PREFIX.$className.'Id_Sn_'.$id;
            Yii::app()->rediscache->delete($key);
        }else if(preg_match('/^dirty([\w\d]+)/',$name,$matches)){
            // dirty obj
            $className = $matches[1];
            $id = $arguments[0];
            $key = self::$PREFIX.$className.'_'.$id;
            Yii::app()->rediscache->delete($key);
        }else{
            throw new Exception('并没有这个卵子函数 => "'.$name.'"');
        }
    }

    public static function getMenu($id){
        $key = self::$PREFIX.'Menu_'.$id;
        $obj = Yii::app()->rediscache->get($key);
        if($obj === false) {
            $obj = Menu::model()->findByPk($id);
            if($obj){
                $pidList = MenuUtil::decodePath($obj->idpath);
                $pnameList = [];
                if($pidList){
                    foreach ($pidList as $pid){
                        if($pid!=$obj->id){
                            $pMenu = self::getMenu($pid);
                            if($pMenu){
                                $pnameList[] = $pMenu->name;
                            }else{
                                $pnameList[] = MenuService::ROOT_NAME;
                            }
                        }
                    }
                }
                $obj->namepath = MenuUtil::genPath(MenuUtil::convert2WrapList($pnameList),'');
                Yii::app()->rediscache->set($key,$obj);
            }
        }
        return $obj;
    }

    public static function dirtyMenu($id){
        $key = self::$PREFIX.'Menu_'.$id;
        Yii::app()->rediscache->delete($key);
    }


    public static function setMenuList($appid,$token){
        $key = self::$PREFIX.'AppMenu_'.$appid;
        Yii::app()->orediscache->set($key,$token);
        return $token;
    }

    public static function getMenuList($appid){
        $key = self::$PREFIX.'AppMenu_'.$appid;
        $obj = Yii::app()->orediscache->get($key);
        return $obj;
    }
}
