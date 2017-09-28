<?
$menuPath = $this->getComponentsPath('MenuUtil.php');
$configContent = file_get_contents($menuPath);
$templatePath=$this->templatePath;

$start_tag = '/*holder_start*/';
$end_tag = '/*holder_end*/';
$holderMarker = '/*holder_marker*/';

$startIndex = strpos($configContent,$start_tag);
$endIndex = strpos($configContent,$end_tag);
$holderContent = substr($configContent,$startIndex, strlen($end_tag)+ $endIndex-$startIndex);
$holderReplaceContent = str_replace('holder',$this->controller,$holderContent);
$holderReplaceContent = str_replace('HOLDER',strtoupper($this->controller),$holderReplaceContent);
$holderReplaceContent .= "\r\n".Msgs::TAB.$holderMarker;

$configContent = str_replace($holderMarker,$holderReplaceContent,$configContent);

/*$replaceContent = $this->render($templatePath.'/tpl_menu_item.php',[
    'name'=>$controller,
    'objectName'=>$page,
],true);

$configContent = Utilities::str_replace_limit(']],',$replaceContent,$configContent,1);*/
echo $configContent;
?>