<?

class MMenuUtil {

    public static function menu2Array($menu,$scope = 'all'){
        $menuAttrs = [];
        if($menu){
            switch ($scope){
                case 'project':
                    $menuAttrs = BaseUtil::obj2ArrayInclude($menu,['displayname','name']);
                    break;
                case 'menu':
                    $menuAttrs = BaseUtil::obj2ArrayIgnore($menu,['deleted']);
                    break;
                case 'detail':
                    $menuAttrs = BaseUtil::obj2ArrayIgnore($menu,['deleted']);
                    break;
                default:
                    throw new CHttpException(404,'NO SCOPE');
                    break;
            }
        }
        return $menuAttrs;
    }

    public static function genPath($parentPath,$point){
        if(!$parentPath){
            return self::wrapKeyword($point);
        }
        if(!$point){
            return is_array($parentPath)?Utilities::list2String($parentPath):$parentPath;
        }
        if(!is_array($parentPath)){
            $parentPath = Utilities::string2List($parentPath);
        }
        return Utilities::list2String(array_merge($parentPath,[self::wrapKeyword($point)]));
    }

    public static function convert2WrapList($unwrappedList){
        $wrappedIdList = [];
        if($unwrappedList){
            foreach ($unwrappedList as $unwrappedId){
                $wrappedIdList[] = self::wrapKeyword($unwrappedId);
            }
            return $wrappedIdList;
        }
        return $wrappedIdList;
    }

    public static function wrapKeyword($point){
        if(!$point){
            return $point;
        }
        return '#'.$point.'#';
    }
    public static function startsWith($haystack, $needle){
        return $needle === "" || strpos($haystack, $needle) === 0;
    }

    public static function endsWith($haystack, $needle){
        return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
    }
    public static function unwrapKeyword($wrapped){
        if(!Utilities::startsWith($wrapped,'#') || !Utilities::endsWith($wrapped,'#')){
            return $wrapped;
        }
        return substr($wrapped,1,strlen($wrapped)-2);
    }

    public static function decodePath($idPath){
        $wrappedIdList = Utilities::string2List($idPath);
        $unwrappedIdList = [];
        if($wrappedIdList){
            foreach ($wrappedIdList as $wrappedId){
                $unWrappedIdList[] = self::unwrapKeyword($wrappedId);
            }
            return $unWrappedIdList;
        }
        return '';
    }
}