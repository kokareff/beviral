<?php
/**
 * Адаптер конфигов в файлах
 */

namespace PhpBase\Config;

use PhpBase\Config\IAdapter;

/**
 * Адаптер конфигов в файлах
 */
class Files implements IAdapter
{
    /**
     * @var string
     */
    protected $_configsDir;


    /**
     * @var array
     */
    protected $_keys = [];

    /**
     * @var array
     */
    protected static $_vars = [];


    /**
     * Конструктор
     *
     * @param string $directory Путь к файлам конфигурации
     */
    public function __construct($directory)
    {
        $this->_configsDir = rtrim($directory, '/');
        foreach (scandir($directory) as $file) {
            if (substr($file, - 4) === '.php') {
                $this->_keys[] = basename($file, '.php');
            }
        }
    }



    /**
     * Возвращает значение по ключу
     *
     * @param string $key Ключ
     * @return mixed
     */
    public function get($key)
    {
        if(isset(self::$_vars[$key])){
           return self::$_vars[$key];
        } else {
            $key = basename($key, '.php');
            if (in_array($key, $this->_keys)) {
                $value = require $this->_configsDir . '/' . $key . '.php';

                if ($value !== false) {
                    self::$_vars[$key]=$value;
                    return $value;
                }
            }
        }
        return null;
    }


    /**
     * Устанавливает знаечение по ключу
     *
     * @param string $key Ключ
     * @param mixed $value Значение
     * @return bool
     */
    public function set($key, $value)
    {
        // TODO: Доделать
        return false;
    }


    /**
     * Возвращает все известные ключи параметров
     *
     * @return array
     */
    public function getKeys()
    {
        return $this->_keys;
    }
}