<?
$name = ucfirst($this->controller).'SignService';
echo <<<EOF
<?

class $name {

    const SERVICE_NAME = 'com.airi.cloud.user.service.UserService';

    public static function register(\$username,\$password){
        if(!\$username){
            return ['ret'=>'FAIL','data'=>'用户名不能为空'];
        }
        if(!\$password){
            return ['ret'=>'FAIL','data'=>'密码不能为空'];
        }
        \$userService = Dubbo::getService(self::SERVICE_NAME);
        if(!\$userService){
            return ['ret'=>'FAIL','data'=>'获取服务错误'];
        }
        \$ret = \$userService->register(\$username,\$password);
        if ('FAIL' == \$ret['ret']) {
            \$ret['data'] = \$ret['errmsg'];
        }
        return \$ret;
    }

    public static function login(\$username,\$password){
        if(!\$username){
            return ['ret'=>'FAIL','data'=>'用户名不能为空'];
        }
        if(!\$password){
            return ['ret'=>'FAIL','data'=>'密码不能为空'];
        }
        \$userService = Dubbo::getService(self::SERVICE_NAME);
        if(!\$userService){
            return ['ret'=>'FAIL','data'=>'获取服务错误'];
        }
        \$ret = \$userService->login(\$username,\$password);
        if ('FAIL' == \$ret['ret']) {
            \$ret['data'] = \$ret['errmsg'];
        }
        return \$ret;
    }
}
EOF;
?>

