<?php
echo <<<EOF
$(function () {
    var customApp = angular.module(_MOUDLE_NAME);
    if(!customApp){
        console.e("no app");
        return;
    }
EOF;
    foreach ($constants as $constant) {
        echo <<<EOF
    customApp.filter("$constant",function (SimpleFilter) {
        return function(type,def){
            return SimpleFilter.filter($constant,type,def);
        };
    });
    
EOF;
    }
echo <<<EOF
});
EOF;
