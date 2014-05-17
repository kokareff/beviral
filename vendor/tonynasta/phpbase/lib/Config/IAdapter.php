<?php
/**
 * Интерфейс адаптера конфигов
 */


namespace PhpBase\Config;


/**
 * Интерфейс адаптера конфигов
 */
interface IAdapter
{
    /**
     * Возвращает значение по ключу
     *
     * @param string $key Ключ
     * @return mixed NULL если не найдено
     */
    public function get($key);

    /**
     * Устанавливает знаечение по ключу
     *
     * @param string $key Ключ
     * @param mixed $value Значение
     * @return bool
     */
    public function set($key, $value);


    /**
     * Возвращает все известные ключи параметров
     *
     * @return array
     */
    public function getKeys();
}
