<?php
/**
 * Класс для работы с конфигурацией
 */

namespace PhpBase;


/**
 * Класс для работы с конфигурацией
 */
class Config
{
    /**
     * Локальный кеш
     *
     * @var array
     */
    protected $_conf = [];

    /**
     * @var Config\IAdapter
     */
    protected $_adapter;

    /**
     * Конструктор
     *
     * @param Config\IAdapter $adapter Адаптер доступа к конфигам
     */
    public function __construct(Config\IAdapter $adapter)
    {
        $this->_adapter = $adapter;
    }


    /**
     * Устанавливает значение
     *
     * @param string $keyspace Пространство ключей
     * @param string $key Ключ
     * @param mixed $value Значение
     * @return bool
     */
    public function set($keyspace, $key, $value)
    {
        $vals = isset($this->_conf[$keyspace]) ? $this->_conf[$keyspace] : [];
        $vals[$key] = $value;

        if ($this->_adapter->set($keyspace, $vals)) {
            $this->_conf[$keyspace] = $vals;
            return true;
        }

        return false;
    }


    /**
     * Возвращает значение конфига
     *
     * @param string $keyspace Пространство ключей
     * @param string $key Ключ
     * @param mixed $default По-умолчанию
     * @return mixed
     */
    public function get($keyspace, $key, $default = null)
    {
        $this->_loadKeySpace($keyspace);

        return isset($this->_conf[$keyspace][$key]) ?
                $this->_conf[$keyspace][$key] : $default;
    }


    /**
     * Возвращает все известные пространства ключей
     *
     * @return array
     */
    public function getDefinedKeyspaces()
    {
        return $this->_adapter->getKeys();
    }


    /**
     * Возвращает все значения пространства ключей в виде массива
     *
     * @param string $keyspace Пространство ключей
     * @return array
     */
    public function getValues($keyspace)
    {
        $this->_loadKeySpace($keyspace);

        return isset($this->_conf[$keyspace]) ? $this->_conf[$keyspace] : [];
    }


    /**
     * Загружает пространство ключей, если при необходимости
     *
     * @param string $keyspace Пространство ключей
     * @return void
     */
    protected function _loadKeySpace($keyspace)
    {
        if (!isset($this->_conf[$keyspace])) {
            $vals = $this->_adapter->get($keyspace);

            if ($vals !== null && is_array($vals)) {
                $this->_conf[$keyspace] = $vals;
            }
        }
    }
}
