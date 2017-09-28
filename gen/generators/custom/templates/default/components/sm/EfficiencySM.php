<?php

class HolderSM extends BaseSM{
    public $chList = [];
    public function getConfig(){
        //todo 需要适配
        $config = [
            'status_list'=>MyStatus::$EFFICIENCY_STATUS,
            'status_list_ch'=>MyStatus::$EFFICIENCY_STATUS,
            'status_route_list'=>[
                'efficiency_edit'=>[
                    'rejected'=>'submited',
                    'added'=>'submited'
                ],
            ],
            'mode'=>'string'
        ];
        return $config;
    }

    public  function getActionRouteMap(){
        $routes = [
            'passed'=>[
                'efficiency_view',
            ],
            'unpassed'=>[
                'efficiency_view'
            ]
        ];
        return $routes;
    }

    public function getActionNameMap(){
        $nameMap = [
            'efficiency_add'=>'添加',
            'efficiency_view'=>'查看',
            'efficiency_edit'=>'编辑',
            'efficiency_feedback'=>'反馈',
            'efficiency_mark_correct'=>'修改整改状态',
            'efficiency_mark_correct_unpassed'=>'整改未通过',
            'efficiency_mark_correct_passed'=>'整改通过',
        ];
        return $nameMap;
    }

    public function getActionStatusMap(){
        return MyStatus::$EFFICIENCY_STATUS;
    }
}