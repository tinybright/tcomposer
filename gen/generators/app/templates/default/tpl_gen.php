<?php
echo <<<EOF
<script type="text/javascript">
    window.ARRS = {};
    
EOF;
    foreach ($constants as $constant){
        echo <<<EOF
    $constant = <?=json_encode(Enum::$$constant)?>;
    window.ARRS.$constant = <?=json_encode(Enum::$$constant)?>;
    
EOF;
    }
echo <<<EOF
</script>
EOF;
