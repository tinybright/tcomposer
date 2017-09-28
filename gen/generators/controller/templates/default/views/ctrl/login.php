<?
echo <<<EOF
<?
\$this->widget('ext.modal.SimpleModal',[
    'layout'=>'simple-login-page',
    'params'=>[
        'scope'=>'$controller',
        'from'=>\$from
    ]
]);
?>
EOF;
?>
