<?php

namespace Zotto\Request;

use PhpBase\Mvc\Request;
use PhpBase\SmartArray;

class Qupa extends Request {
    public $params;
    public $rawParams;

    public function mapParams($mapper){
        $mappedParams = array();
        foreach ($mapper as $index => $mapName) {
            if(isset($this->rawParams[$index])){
                $mappedParams[$mapName] = $this->rawParams[$index];
            }
        }
        $this->params = new SmartArray($mappedParams);
    }

    public function setParams($newParams){
        $this->rawParams = $newParams;
    }
} 