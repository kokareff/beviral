<?php
/**
 * Базовая модель БД
 */

namespace PhpBase\Db;


/**
 * Базовая модель БД
 */
abstract class Model
{
    /**
     * @var string
     */
    protected static $_table = '';

    /**
     * @var array
     */
    protected static $_defaults = [];

    /**
     * @var Adapter
     */
    protected $_db;

    /**
     * @var array
     */
    protected $_data;

    /**
     * Конструктор модели
     *
     * @param Adapter $db Адаптер БД
     * @param array $data Начальные данные
     */
    public function __construct(Adapter $db, array $data = [])
    {
        $this->_db = $db;

        foreach ($data as $key => $value) {
            $this->set($key, $value);
        }
    }


    /**
     * Магический метод получения свойства
     *
     * @param string $key Ключ
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }


    /**
     * Магический метод установки свойства
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
     * Магический метод проверки свойства
     *
     * @param string $key Ключ
     * @return void
     */
    public function __isset($key)
    {
        return $this->exists($key);
    }



    /**
     * Возвращает свойство модели
     *
     * @param string $key Ключ
     * @param mixed $default По-умолчанию
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (isset($this->_data[$key])) {
            return $this->_data[$key];
        } else if (isset(static::$_defaults[$key])) {
            return static::$_defaults[$key];
        } else {
            return $default;
        }
    }


    /**
     * Устанавливает свойство модели
     *
     * @param string $key Ключ
     * @param mixed $value Значение
     * @return void
     */
    public function set($key, $value)
    {
        if (isset(static::$_defaults[$key])) {
            $this->_data[$key] = $value;
        }

        return;
    }


    /**
     * Проверяет свойство модели
     *
     * @param string $key Ключ
     * @return void
     */
    public function exists($key)
    {
        return isset(static::$_defaults[$key]);
    }


    /**
     * Создает или обновляет запись модели
     *
     * @return \PDOStatement
     */
    public function save()
    {
        if ($this->id > 0) {
            $statement = $this->_db->update(
                static::$_table, ['id' => $this->id], $this->_data
            );
        } else {
            $this->id = null;
            $statement = $this->_db->insert(static::$_table, $this->_data);
            $this->id = $this->_db->getInsertId();
        }
        return $statement;
    }


    /**
     * Удаляет запись, сбрасыват идентификатор
     *
     * @return void
     */
    public function delete()
    {
        if ($this->id) {
            $this->_db->delete(static::$_table, ['id' => $this->id]);
            $this->id = static::$_defaults['id'];
        }
    }


    /**
     * Возвращает адаптер
     *
     * @return Adapter
     */
    public function getAdapter()
    {
        return $this->_db;
    }


    /**
     * Выполняет поиск записей, возвращает массив моделей
     *
     * @param Adapter $db Адаптер БД
     * @param array $where Условия
     * @param array $order Сортировка
     * @param int $limit Ограничение
     * @param int $offset Отступ
     * @return array
     */
    public static function find(Adapter $db, array $where = [],
                                array $order = [], $limit = 0, $offset = 0)
    {
        $rows = $db->select(static::$_table, $where, $order, $limit, $offset)
            ->fetchAll(\PDO::FETCH_ASSOC);

        $models = [];

        foreach ($rows as $row) {
            $models[] = new static($db, $row);
        }

        return $models;
    }


    /**
     * Выполняет поиск одной записи, возвращает модель
     *
     * @param Adapter $db Адаптер
     * @param array $where Условия
     * @param array $order Сортировка
     * @param int $offset Сдвиг
     * @return static
     */
    public static function findOne(
        Adapter $db, array $where = [], array $order = [], $offset = 0)
    {
        $result = static::find($db, $where, $order, 1, $offset);
        return isset($result[0]) ? $result[0] : null;
    }


    /**
     * Выполняет поиск записи по идентификаторо, возвращает модель
     *
     * @param Adapter $db Адаптер БД
     * @param int $id Идентификатор
     * @return static
     */
    public static function findById(Adapter $db, $id)
    {
        return static::findOne($db, ['id' => $id]);
    }


    /**
     * Возвращает количество записей по условию
     *
     * @param Adapter $db Адаптер БД
     * @param array $where Массив условий
     * @return int
     */
    public static function count(Adapter $db, array $where = [])
    {
        $params = [];
        $sql = sprintf(
            'SELECT COUNT(*) as total FROM %s %s',
            $db->makeTable(static::$_table),
            $db->makeWhere($where, $params)
        );

        return (int) $db->query($sql, $params)
            ->fetch(\PDO::FETCH_ASSOC)['total'];
    }
}
