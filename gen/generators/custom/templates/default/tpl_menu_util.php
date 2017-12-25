<?
$menuPath = PathUtil::getPath(['proteced','components','custom','MenuUtil.php']);
$configContent = file_get_contents($menuPath);
$templatePath=$this->templatePath;

/*platform_item_marker*/
$item_marker = $controller.'_item_marker';

$matchs = [];
preg_match("/"."\\n\s*\/\*".$item_marker."\*\/"."/i" ,$configContent,$matchs);
if(!$matchs){
    return 'no "/*platform_item_marker*/"';
}
$holder = $matchs[0];
$replaceContent = "\r\n";
$replaceContent .= $this->render($templatePath.'/tpl_menu_item.php',[
    'name'=>$controller,
    'objectName'=>$page,
],true);
$replaceContent .= $holder;
$configContent = str_replace($holder,$replaceContent,$configContent);
echo $configContent;
?>