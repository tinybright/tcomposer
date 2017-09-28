<?
$menuPath = PathUtil::getViewPath(['mgr','index.php']);
$configContent = file_get_contents($menuPath);
$templatePath=$this->templatePath;

$configContent = str_replace('mgr',$this->controller,$configContent);
$configContent = str_replace('Mgr',strtoupper($this->controller),$configContent);

echo $configContent;
?>