<?php
/**
 * Класс реестра
 */

namespace PhpBase;


/**
 * Класс реестра
 */
class Registry
{
    /**
     * @var array
     */
    protected $_values = [];

    /**
     * @var Registry
     */
    protected static $_instance = null;


    /**
     * Возвращает экземпляр реестра
     *
     * @return Registry
     */
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }


    /**
     * Магический метд получения значения
     *
     * @param string $key Ключ
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }


    /**
     * Магический метод установки значения
     *
     * @param string $key Ключ
     * @param mixed $value Значение
     * @return void
     */
    public function __set($key, $value)
    {
        $this->set($key, $value);
        return;
    }


    /**
     * Возвращает значение
     *
     * @param string $key Ключ
     * @param mixed $default По-умолчанию
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return isset($this->_values[$key]) ? $this->_values[$key] : $default;
    }


    /**
     * Устанавливает значение
     *
     * @param string $key Ключ
     * @param mixed $value Значение
     * @return void
     */
    public function set($key, $value)
    {
        $this->_values[$key] = $value;
        return;
    }
}
