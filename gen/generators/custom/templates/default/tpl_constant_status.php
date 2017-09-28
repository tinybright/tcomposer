public static <?='$'.strtoupper($statusName)?> = [
<?
        foreach ($statusList as $key=>$status){
            echo <<<EOF
        '$key' => '$status',\n
EOF;

        }
?>
    ];