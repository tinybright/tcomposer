<?
$layoutPath =  $this->getViewsPath('layouts',$controller.'.php');
$layoutContent = file_get_contents($layoutPath);


$start_tag = '<!-- build:js js/'.$controller.'.controller.js -->';
$end_tag = '<!-- endbuild -->';
$startIndex = strpos($layoutContent,$start_tag);
$endIndex = strpos($layoutContent,$end_tag,$startIndex);
$replaceStr = substr($layoutContent,$startIndex,$endIndex-$startIndex);
if($path){
    $newReplaceContent = $replaceStr.'<script src="'.strtolower($path).'" type="text/javascript"></script>'."\n"."\t";
}else{
    /*$bathPath =dirname($protectedPath);
    $jsPath = $bathPath.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.$name;
    $jsDirList = scandir($jsPath);
    $list = [];
    $dirs = [];
    if($jsDirList){
        foreach ($jsDirList as $key=> $jsDir){
            if(strlen($jsDir) < 3){
                continue;
            }
            $list[$jsDir] = [];
            $jsDirReal = $jsPath.DIRECTORY_SEPARATOR.$jsDir;
            if(is_dir($jsDirReal)){
                $jsPathList = scandir( $jsDirReal);
                if($jsPathList){
                    foreach ($jsPathList as $jsPathA){
                        if(strlen($jsPathA) < 3){
                            continue;
                        }
                        $list[$jsDir][] = $jsPathA;
                        $dirs[] = 'js/'.$name.'/'.$jsDir.'/'.$jsPathA.'';
                    }
                }
            }
        }
    }
    $newReplaceContent = '';
    $newReplaceContent .= $start_tag;
    $newReplaceContent .= "\n"."\t";
    foreach ($dirs as $dir){
        $newReplaceContent .= '<script src="'.$dir.'" type="text/javascript"></script>'."\n"."\t";
    }*/
}

$result = str_replace($replaceStr,$newReplaceContent,$layoutContent);
echo $result;
?>