<?php


// CREATE TABLE test_table (
//    id int(11) NOT NULL AUTO_INCREMENT,
//    foo varchar(50) NOT NULL,
//    bar int(11) NOT NULL,
//    PRIMARY KEY (`id`)
// )


require_once __DIR__ . '/../autoload.php';


class MysqlAdapterTest extends PHPUnit_Framework_TestCase
{

    public function testSqlBuilders()
    {
        $db = new \PhpBase\Db\Adapter\Mysql('mysql:host=127.0.0.1', 'root', '');
        $this->assertEquals('`tablename`', $db->makeTable('tablename'));
        $this->assertEquals('`test`.`test_table`', $db->makeTable('test.test_table'));

        $params = [];
        $this->assertEquals('WHERE `id` = :where_id AND `name` = :where_name',
            $db->makeWhere(['id' => 666, 'name' => 'name'], $params));
        $this->assertEquals(666, $params['where_id']);
        $this->assertEquals('name', $params['where_name']);
        $this->assertEquals('', $db->makeWhere([], $params));

        $this->assertEquals('SET `id` = :set_id, `name` = :set_name',
            $db->makeSet(['id' => 666, 'name' => 'name'], $params));
        $this->assertEquals(666, $params['set_id']);
        $this->assertEquals('name', $params['set_name']);

        $this->assertEquals('', $db->makeSet([], $params));


        $this->assertEquals('ORDER BY `id` ASC, `name` DESC, `fail` ASC',
            $db->makeOrder(['id'=> 'ASC', 'name'=> 'DESC', 'fail' => 123]));

        $this->assertEquals('LIMIT 123', $db->makeLimit(123, 0));
        $this->assertEquals('LIMIT 123 OFFSET 50', $db->makeLimit(123, 50));
    }


    public function testOperations()
    {
        $db = new \PhpBase\Db\Adapter\Mysql('mysql:host=127.0.0.1', 'root', '');
        $table = 'test.test_table';

        // Truncate table
        $this->assertInstanceOf('PDOStatement', $db->delete($table, []));

        // Check for no entries
        $this->assertEquals(0, $db->select($table)->rowCount());

        // Add 1 entry
        $this->assertInstanceOf('PDOStatement',
            $db->insert($table, ['foo' => '123', 'bar' => 456]));

        // Selecting it
        $result = $db->select($table);

        // Checking its the only one
        $this->assertEquals(1, $result->rowCount());

        // Getting row values
        $row = $result->fetchAll(PDO::FETCH_ASSOC)[0];

        $this->assertEquals('123', $row['foo']);
        $this->assertEquals(456, $row['bar']);

        // test update
        $id = $db->getInsertId();
        $db->update($table, ['id' => $id], ['foo' => 'bazbaz']);
        $row = $db->select($table, ['id' => $id])->fetchAll(PDO::FETCH_ASSOC)[0];
        $this->assertEquals('bazbaz', $row['foo']);

        // test delete where
        $db->delete($table, ['bar' => 456]);
        $this->assertEquals(0, $db->select($table, ['bar' => 456])->rowCount());
    }
}
