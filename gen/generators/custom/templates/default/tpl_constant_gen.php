<?
$menuPath = PathUtil::getCompoPath(['custom','MyStatus.php']);
$configContent = file_get_contents($menuPath);

$templatePath=$this->templatePath;
$statusHolder = "/*gen_status_holder*/";
$index = strpos($configContent,$statusHolder);
if($index === FALSE){
    echo 'Cant find '.$statusHolder.' in Constant.php';
    return;
}
$replaceContent = "\r\n";

/*$config = preg_match("/".preg_quote($ini)."=\"(.*)\";/", $str, $res);*/
/*if(!$res || $res[1]==null){
    $config = preg_match("/".preg_quote($ini)."='(.*)';/", $str, $res);
}*/
preg_match("/"."\\r\\n\s{1,100}\/\*gen_status_holder\*\/"."/i" ,$configContent,$math);
$statusHolderMatch = @$math[0];

if($statusHolderMatch){
    if($statusInfos){
        foreach ($statusInfos as $index=>$statusInfo){
            $renderContent = $this->render($templatePath.'/tpl_constant_status.php',[
                'statusName'=>@$statusInfo['statusName'],
                'statusList'=>@$statusInfo['statusList']
            ],true);
            $replaceContent .= Msgs::TAB;
            $replaceContent .= $renderContent;
            if($index!= count($statusInfos)-1){
                $replaceContent .= "\r\n";
            }
        }
    }

    $replaceContent .= $statusHolderMatch;

    $configContent = str_replace($statusHolderMatch,$replaceContent,$configContent);
}

echo $configContent;
?>