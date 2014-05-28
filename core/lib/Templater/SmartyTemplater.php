<?php

namespace Zotto\Templater;
use Zotto\Config\Files;
use Smarty;
require_once CORE_LIB.'SmartyHelpers.php';

class SmartyTemplater implements Templater {

    /**
     * @var Smarty
     */
    protected $_smartyInstance;

    function render($template, $data)
    {
        if($this->_smartyInstance===null){
            $this->prepare();
        }
        $this->_smartyInstance->assign($data);
        return $this->_smartyInstance->fetch($template);
    }

    function prepare()
    {
        if($this->_smartyInstance===null){
            $smartyConfig = (new Files(CORE_CONFIG))->get('smarty');
            $this->_smartyInstance = new Smarty();
            $this->_smartyInstance->setTemplateDir($smartyConfig['templates_dir']);
            $this->_smartyInstance->setCacheDir($smartyConfig['templates_cache']);
            $this->_smartyInstance->setCompileDir($smartyConfig['templates_compile']);
        }
    }
}