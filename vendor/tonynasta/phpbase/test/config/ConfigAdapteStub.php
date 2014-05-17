<?php



class ConfigAdapteStub implements \PhpBase\Config\IAdapter
{

    protected $_config;

    public function __construct(array $config)
    {
        $this->_config = $config;
    }


    public function get($key)
    {
        return isset($this->_config[$key]) ? $this->_config[$key] : null;
    }

    public function set($key, $value)
    {
        $this->_config[$key] = $value;
        return true;
    }

    public function getKeys()
    {
        return array_keys($this->_config);
    }
}
