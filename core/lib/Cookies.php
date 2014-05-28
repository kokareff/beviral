<?php

namespace Zotto;

class Cookies {
    public static function set($name, $val){
        setcookie($name, $val, time()+31536000,'/');
        $_COOKIE[$name]=$val;
    }

    public static function get($name){
        return $_COOKIE[$name];
    }

    public static function isSetted($name){
        return isset($_COOKIE[$name]);
    }

    public static function del($name){
        setcookie($name, "", time()-3600,'/');
        unset($_COOKIE[$name]);
    }
} 