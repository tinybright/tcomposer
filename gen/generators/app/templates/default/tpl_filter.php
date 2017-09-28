<?php
echo <<<EOF
$(function () {
    var kickoffApp = angular.module(_MOUDLE_NAME);
    if(!kickoffApp){
        console.e("no app");
        return;
    }
EOF;
    foreach ($constants as $constant) {
        echo <<<EOF
    kickoffApp.filter("$constant",function (SimpleFilter) {
        return function(type,def){
            return SimpleFilter.filter($constant,type,def);
        };
    });
    
EOF;
    }
echo <<<EOF
});
EOF;
