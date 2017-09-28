when("/data/<?=strtolower($objectName)?>",{
            templateUrl : '<?=$name?>/<?=$objectName?>Home',
            controller : '<?=ucfirst($objectName)?>Controller',
        }).
        otherwise(