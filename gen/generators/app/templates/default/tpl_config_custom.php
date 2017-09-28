<?
$configJsPath = $this->getProtectedPath('config','custom.php');

$configContent = file_get_contents($configJsPath);
$templatePath=$this->templatePath;
/*$appName = GenTool::get_config($configJsPath,'appName');*/
/*print_r([$appName,$dbName,$salt]);*/
$configs = [
    'appName'=>$appname,
    'dbName'=>$dbname,
    'salt'=>$salt,
];

foreach ($configs as $key=>$config){
    $ini = $key;
    $value = $config;
    $configContent = preg_replace("/".preg_quote($ini)."=(.*);/",$ini."='".$value."';",$configContent);
}

echo $configContent;
?>