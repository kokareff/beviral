<?php
/**
 * Адаптер конфигов в редисе
 */

namespace PhpBase\Config;

/**
 * Адаптер конфигов в редисе
 */
class Redis implements IAdapter
{
    /**
     * @var \Redis
     */
    protected $_redis;

    /**
     * @var string
     */
    protected $_keyPrefix;



    /**
     * Конструктор
     *
     * @param \Redis $redis Инстанс редиса
     * @param string $keyPrefix Префикс ключей
     */
    public function __construct(\Redis $redis, $keyPrefix = 'config:')
    {
        $this->_redis = $redis;
        $this->_keyPrefix = $keyPrefix;
    }



    /**
     * Возвращает значение по ключу
     *
     * @param string $key Ключ
     * @return mixed
     */
    public function get($key)
    {
        $value = $this->_redis->get($this->_keyPrefix . $key);

        if ($value !== false) {
            $value = \unserialize($value);
            if ($value !== false) {
                return $value;
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
        return $this->_redis->set($this->_keyPrefix . $key, \serialize($value));
    }


    /**
     * Возвращает все известные ключи параметров
     *
     * @return array
     */
    public function getKeys()
    {
        $keys = $this->_redis->keys($this->_keyPrefix . '*');
        $prefixLen = strlen($this->_keyPrefix);

        foreach ($keys as $index => $value) {
            $keys[$index] = \substr($value, $prefixLen);
        }

        return $keys;
    }
}
