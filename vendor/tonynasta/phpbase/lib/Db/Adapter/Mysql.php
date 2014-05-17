<?php
/**
 * Адаптер для MySQL
 */

namespace PhpBase\Db\Adapter;


/**
 * Адаптер для MySQL
 */
class Mysql extends \PhpBase\Db\Adapter
{
    /**
     * Выбирает записи
     *
     * @param string $table Таблица
     * @param array $where Массив условий
     * @param array $order Массив сортировки
     * @param int $limit Ограничение
     * @param int $offset Сдвиг
     * @return \PDOStatement
     */
    public function select($table, array $where = [],
                           $order = [], $limit = 0, $offset = 0)
    {
        $params = [];
        $sql = sprintf(
            'SELECT * FROM %s %s %s %s',
            $this->makeTable($table),
            $this->makeWhere($where, $params),
            $this->makeOrder($order),
            $this->makeLimit($limit, $offset)
        );

        return $this->query($sql, $params);
    }


    /**
     * Добавляет запись
     *
     * @param string $table Таблица
     * @param array $values Массив значений
     * @return \PDOStatement
     */
    public function insert($table, array $values)
    {
        $fields = [];
        $placeholders = [];

        foreach ($values as $key => $value) {
            $fields[] = '`' . $key . '`';
            $placeholders[] = ':' . $key;
        }

        $sql = sprintf(
            'INSERT INTO %s(%s) VALUES(%s)',
            $this->makeTable($table),
            implode(', ', $fields),
            implode(', ', $placeholders)
        );

        return $this->query($sql, $values);
    }


    /**
     * Обновляет записи
     *
     * @param string $table Таблица
     * @param array $where Массив условий
     * @param array $set Массив новых значений
     * @param array $order Массив сортировки
     * @param int $limit Ограничение
     * @return \PDOStatement
     */
    public function update(
        $table, array $where, array $set, array $order = [], $limit = 0)
    {
        $params = [];
        $sql = sprintf(
            'UPDATE %s %s %s %s %s',
            $this->makeTable($table),
            $this->makeSet($set, $params),
            $this->makeWhere($where, $params),
            $this->makeOrder($order),
            $this->makeLimit($limit, 0)
        );

        return $this->query($sql, $params);
    }


    /**
     * Удаляет записи
     *
     * @param string $table Таблица
     * @param array $where Массив условий
     * @param array $order Массив сортировки
     * @param int $limit Ограничение
     * @return \PDOStatement
     */
    public function delete($table, array $where, array $order = [], $limit = 0)
    {
        $params = [];
        $sql = sprintf(
            'DELETE FROM %s %s %s %s',
            $this->makeTable($table),
            $this->makeWhere($where, $params),
            $this->makeOrder($order),
            $this->makeLimit($limit, 0)
        );

        return $this->query($sql, $params);
    }


    /**
     * Возвращает экранированное название БД
     *
     * @param string $table Таблица
     * @return string
     */
    public function makeTable($table)
    {
        $parts = explode('.', $table, 2);
        return '`' . implode('`.`', $parts) . '`';
    }


    /**
     * Собирает часть WHERE
     *
     * @param array $where Массив условий
     * @param array &$params Массив для записи параметров
     * @return string
     */
    public function makeWhere(array $where, array &$params)
    {
        $sql = '';

        if ($where) {
            $parts = [];
            foreach ($where as $key => $value) {
                $ph = 'where_' . $key;
                $parts[] = sprintf('`%s` = :%s', $key, $ph);
                $params[$ph] = $value;
            }
            $sql = 'WHERE ' . implode(' AND ', $parts);
        }

        return $sql;
    }


    /**
     * Собирает часть ORDER
     *
     * @param array $order Массив полей
     * @return string
     */
    public function makeOrder(array $order)
    {
        $sql = '';

        if ($order) {
            $parts = [];
            foreach ($order as $col => $o) {
                $parts[] =
                    sprintf('`%s` %s', $col, $o === 'DESC' ? 'DESC' : 'ASC');
            }
            $sql = 'ORDER BY ' . implode(', ', $parts);
        }

        return $sql;
    }

    /**
     *  Собирает часть SET
     *
     * @param array $set Массив для записи значений
     * @param array &$params Массив условий
     * @return string
     */
    public function makeSet(array $set, array &$params)
    {
        $sql = '';

        if ($set) {
            $parts = [];
            foreach ($set as $key => $value) {
                $ph = 'set_' . $key;
                $parts[] = sprintf('`%s` = :%s', $key, $ph);
                $params[$ph] = $value;
            }
            $sql = 'SET ' . implode(', ', $parts);
        }

        return $sql;
    }


    /**
     * Собирает часть LIMIT
     *
     * @param int $limit Значение лимита
     * @param int $offset Значение сдвига
     * @return string
     */
    public function makeLimit($limit, $offset)
    {
        $sql = '';

        if ($limit > 0) {
            $sql = 'LIMIT ' . $limit;

            if ($offset > 0) {
                $sql .= ' OFFSET ' . $offset;
            }
        }

        return $sql;
    }
}
