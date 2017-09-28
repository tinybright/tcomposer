<?
$ctrlPath = PathUtil::getControllerPath(ucfirst($this->controller).'apiController.php');
$templatePath=$this->templatePath;
$apiFile = $templatePath.DIRECTORY_SEPARATOR.'tpl_api_old.php';

$configContent = file_get_contents($ctrlPath);

$item_marker = "gen_action_holder";

$start_holder = "/*gen_".$page."_start*/";
$end_holder = "/*gen_".$page."_end*/";
$startIndex = strpos($configContent,$start_holder);
if($startIndex === FALSE){
    $matchs = [];
    /*gen_action_holder*/
    preg_match("/"."\\n\s*\/\*".$item_marker."\*\/"."/i" ,$configContent,$matchs);
    if(!$matchs){
        return 'no "/*gen_action_holder*/"';
    }
    $holder = $matchs[0];

    $replaceContent = "\r\n";
    $replaceContent .= Msgs::TAB.$start_holder;
    $replaceContent .= "\r\n";
    $replaceContent .= CustomCode::renderTpl($apiFile,[
        'Efficiency'=>ucfirst($this->page),
        'efficiency'=>strtolower($this->page),
        'EFFICIENCY'=>strtoupper($this->page),
    ]);
    $replaceContent .= "\r\n";
    $replaceContent .= Msgs::TAB.$end_holder;

    $replaceContent .= $holder;
    $configContent = str_replace($holder,$replaceContent,$configContent);
}else{
    $endIndex = strpos($configContent,$end_holder);

    $oldContent = substr($configContent,$startIndex,$endIndex + strlen($end_holder) - $startIndex);

    $replaceContent = '';
    $replaceContent .= $start_holder;
    $replaceContent .= "\r\n";
    $replaceContent .= CustomCode::renderTpl($apiFile,[
        'Efficiency'=>ucfirst($this->page),
        'efficiency'=>strtolower($this->page),
        'EFFICIENCY'=>strtoupper($this->page),
    ]);
    $replaceContent .= "\r\n";
    $replaceContent .= Msgs::TAB.$end_holder;
    $configContent = str_replace($oldContent,$replaceContent, $configContent);
}

echo $configContent;