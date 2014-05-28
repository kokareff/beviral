<?php

class SmartyHelpers {
    public static function setActive($patt, $strict = false, $strictPos=1){
        if(!$strict){
            if(strstr($_SERVER['REQUEST_URI'], $patt)!==false){
                return 'class="active"';
            }
        } else {
            $path = explode('/',parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),$strictPos+2);
            if($path[$strictPos]==$patt && (!isset($path[$strictPos+1]) || empty($path[$strictPos+1]))){
               return 'class="active"';
            }
        };
        return '';
    }
} 