<?
$ctrl = $page;
$upperctrl = strtoupper($page);
$lowerctrl = strtolower($page);
$camelctrl = ucfirst($page);
echo <<<EOF
<?php

class {$camelctrl}Util extends BaseUtil{

    public static function {$ctrl}2Array(\${$ctrl},\$scope,\$from = 'vm'){
        \${$ctrl}Attrs = [];
        if(\${$ctrl}){
            switch (\$scope){
                case 'list':
                case 'audit':
                case 'detail':
                    \${$ctrl}Attrs = self::obj2ArrayIgnore(\${$ctrl},[],[]);
                    \${$ctrl}Attrs = self::injectCreator(\${$ctrl}Attrs);
                    \$sm = new {$camelctrl}SM();
                    \${$ctrl}Attrs['actions'] = \$sm->getAction(\${$ctrl});
                    break;
                default:
                    throw new CHttpException(404,Msgs::NO_SCOPE);
                    break;
            }
        }
        return \${$ctrl}Attrs;
    }

    public static function injectCreator(\${$ctrl}Attrs){
        if(\${$ctrl}Attrs && (\$uid = CheckUtil::getValue(\${$ctrl}Attrs,'creator'))){
            \$user = KCache::getUser(\$uid);
            if(\$user){
                \${$ctrl}Attrs['creator'] = BaseUtil::obj2ArrayInclude(\$user,['id','realname','mobile']);
            }
        }
        return \${$ctrl}Attrs;
    }

}
EOF;
