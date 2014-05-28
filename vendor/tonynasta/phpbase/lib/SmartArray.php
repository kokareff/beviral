<?php
/**
 * Класс-обертка массива с дополнительными возможностями
 */


namespace PhpBase;


/**
 * Класс-обертка массива с дополнительными возможностями
 *
 * @package PhpBase
 */
class SmartArray implements \ArrayAccess, \Iterator
{
    /**
     * @var array
     */
    protected $_array = [];


    /**
     * Конструктор класса
     *
     * @param array $inital Начальное значение
     */
    public function __construct(array $inital = [])
    {
        $this->_array = $inital;
    }


    /**
     * Возвращает текущий элемент
     *
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed
     */
    public function current()
    {
        return \current($this->_array);
    }


    /**
     * Двигает курсор на следущий элемент
     *
     * @link http://php.net/manual/en/iterator.next.php
     * @return void
     */
    public function next()
    {
        \next($this->_array);
        return;
    }


    /**
     * Возвращает ключ текущего элемента
     *
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed
     */
    public function key()
    {
        return \key($this->_array);
    }


    /**
     * Проверят валидность позиции курсора
     *
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean
     */
    public function valid()
    {
        return \current($this->_array) !== false;
    }


    /**
     * Перемещает итератор в начало
     *
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    public function rewind()
    {
        \reset($this->_array);
        return;
    }

    /**
     * Проверяет существование элемента
     *
     * @param mixed $offset Позиция
     * @return boolean
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     */
    public function offsetExists($offset)
    {
        return isset($this->_array[$offset]);
    }

    /**
     * Возвращает элемент
     *
     * @param mixed $offset Позиция
     * @return mixed
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }


    /**
     * Устанавливает значение
     *
     * @param mixed $offset Позция
     * @param mixed $value Значение
     * @return void
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
        return;
    }


    /**
     * Удаляет элемент
     *
     * @param mixed $offset Позиция
     * @return void
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     */
    public function offsetUnset($offset)
    {
        unset($this->_array[$offset]);
        return;
    }


    /**
     * Получает значение
     *
     * @param string $key Ключ
     * @param mixed $default Значение по-умолчанию
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return isset($this->_array[$key]) ? $this->_array[$key] : $default;
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
        $this->_array[$key] = $value;
    }


    /**
     * Получает значение
     *
     * @param string $key Ключ
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }


    /**
     * Устанавливает значение
     *
     * @param string $key Ключ
     * @param mixed $value Значение
     * @return void
     */
    public function __set($key, $value)
    {
        $this->set($key, $value);
    }


    /**
     * Проверяет наличие ключе
     *
     * @param string $key Ключ
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->_array[$key]);
    }


    /**
     * Возвращает массив значений
     *
     * @return array
     */
    public function getArray()
    {
        return $this->_array;
    }
}
