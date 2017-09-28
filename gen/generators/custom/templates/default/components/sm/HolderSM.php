<?
$ctrl = $page;
$upperctrl = strtoupper($page);
$lowerctrl = strtolower($page);
$camelctrl = ucfirst($page);
echo <<<EOF
<?php

class {$camelctrl}SM extends BaseSM{
    public \$chList = [];
    public function getConfig(){
        //todo 需要适配
        \$config = [
            'status_list'=>MyStatus::\${$upperctrl}_STATUS,
            'status_list_ch'=>MyStatus::\${$upperctrl}_STATUS,
            'status_route_list'=>[
                '{$ctrl}_edit'=>[
                    'rejected'=>'submited',
                    'added'=>'submited'
                ],
            ],
            'mode'=>'string'
        ];
        return \$config;
    }

    public  function getActionRouteMap(){
        \$routes = [
            'passed'=>[
                '{$ctrl}_view',
            ],
            'unpassed'=>[
                '{$ctrl}_view'
            ]
        ];
        return \$routes;
    }

    public function getActionNameMap(){
        \$nameMap = [
            '{$ctrl}_add'=>'添加',
            '{$ctrl}_view'=>'查看',
            '{$ctrl}_edit'=>'编辑',
            '{$ctrl}_feedback'=>'反馈',
            '{$ctrl}_mark_correct'=>'修改整改状态',
            '{$ctrl}_mark_correct_unpassed'=>'整改未通过',
            '{$ctrl}_mark_correct_passed'=>'整改通过',
        ];
        return \$nameMap;
    }

    public function getActionStatusMap(){
        return MyStatus::\${$upperctrl}_STATUS;
    }
}
EOF;
