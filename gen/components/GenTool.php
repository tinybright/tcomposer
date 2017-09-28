<?php

class GenTool{

	public static function str_replace_limit($search, $replace, $subject, $limit=-1) {
		// constructing mask(s)... 
		if (is_array($search)) {
			foreach ($search as $k=>$v) {
				$search[$k] = '`' . preg_quote($search[$k],'`') . '`';
			}
		}
		else {
			$search = '`' . preg_quote($search,'`') . '`';
		}
		// replacement 
		return preg_replace($search, $replace, $subject, $limit);
	}

	public static function get_config($file, $ini, $type="string"){
		if(!file_exists($file)) return false;
		$str = file_get_contents($file);
		if ($type=="int"){
			$config = preg_match("/".preg_quote($ini)."=(.*);/", $str, $res);
			return $res[1];
		}
		else{
			$config = preg_match("/".preg_quote($ini)."=\"(.*)\";/", $str, $res);
			if($res[1]==null){
				$config = preg_match("/".preg_quote($ini)."='(.*)';/", $str, $res);
			}
			return $res[1];
		}
	}

	public static function update_config($file, $ini, $value,$type="string"){
		if(!file_exists($file)) return false;
		$str = file_get_contents($file);
		$str2="";
		if($type=="int"){
			$str2 = preg_replace("/".preg_quote($ini)."=(.*);/", $ini."=".$value.";",$str);
		}
		else{
			$str2 = preg_replace("/".preg_quote($ini)."=(.*);/",$ini."=\"".$value."\";",$str);
		}
		$ret = file_put_contents($file, $str2);
		return $ret;
	}

	public static $MENU_MENU_LIST = [
		['数据',['http://image.lszhushou.com/2016/07/lszs1469182804350.png','http://image.lszhushou.com/2016/07/lszs1469182784010.png'],[
			/*['BA账号',['http://image.lszhushou.com/2016/08/lszs1471930399964.png','http://image.lszhushou.com/2016/08/lszs1471930413435.png'],'/menu/ba_list'],*/
			['功能上线',['http://image.lszhushou.com/2016/08/lszs1471930399964.png','http://image.lszhushou.com/2016/08/lszs1471930413435.png'],'/menu/menu_edit'],
			['功能点',['http://image.lszhushou.com/2016/08/lszs1471930399964.png','http://image.lszhushou.com/2016/08/lszs1471930413435.png'],'/menu/feature_list'],
			['功能添加',['http://image.lszhushou.com/2016/08/lszs1471930399964.png','http://image.lszhushou.com/2016/08/lszs1471930413435.png'],'/menu/feature_add',true],
			['功能编辑',['http://image.lszhushou.com/2016/08/lszs1471930399964.png','http://image.lszhushou.com/2016/08/lszs1471930413435.png'],'/menu/feature_edit',true],
		]],
		['用户',null,[
			['退出',['http://image.lszhushou.com/2016/06/lszs1465799914530.png','http://image.lszhushou.com/2016/06/lszs1465799924132.png','http://image.lszhushou.com/2016/06/lszs1465799933147.png'],'/sign/logout',true],
		],true],
	];
}