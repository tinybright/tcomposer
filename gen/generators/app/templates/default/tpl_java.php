<?php
echo <<<EOF
public class Constants {

EOF;
foreach ($constants as $constant){
    echo <<<EOF
    public enum {$constant} implements DisplayEnum{
EOF;
        echo "\r\n";
        $c = MyStatus::$$constant;
        end($c);
        $key_last = key($c);
        foreach (MyStatus::$$constant as $key=>$value){
            $d = ",";
            if($key_last == $key){
                $d = ";";
            }
            echo <<<EOF
        $key("{$value}")$d   
EOF;
            echo "\r\n";
        }
    echo <<<EOF
        {$constant}(String name) {
            myname = name;
        }
        public String myname;
        @Override
        public String getDisplayName() {
            return myname;
        }
    }
EOF;
    echo "\r\n";
}
echo <<<EOF
}
EOF;
