<?php

class SmartyHelpers {
    public static function setActive($patt){
        if(strstr($_SERVER['REQUEST_URI'], $patt)!==false){
            return 'class="active"';
        };
        return '';
    }
} 