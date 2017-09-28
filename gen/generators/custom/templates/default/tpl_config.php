<?
$configJsPath = CustomCode::getJsPath(@$controller,'app','config.js');
$configContent = file_get_contents($configJsPath);
$templatePath=$this->templatePath;

$replaceContent = $this->render($templatePath.'/tpl_route.php',[
    'name'=>@$controller,
    'objectName'=>@$page,
],true);

$configContent = str_replace('otherwise(',$replaceContent,$configContent);
echo $configContent;
?>