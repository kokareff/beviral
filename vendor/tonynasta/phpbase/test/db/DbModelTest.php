<?php


require_once __DIR__ . '/../autoload.php';
require_once __DIR__ . '/DbModelStub.php';


class DbModelTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $db = new \PhpBase\Db\Adapter\Mysql('mysql:dbname=test;host=127.0.0.1', 'root', '');
        $db->delete(DbModelStub::$_table, []);

        $db->insert(DbModelStub::$_table, ['foo' => 'bar', 'bar' => 100500]);
        $db->insert(DbModelStub::$_table, ['foo' => 'bar', 'bar' => 42]);
        $this->db = $db;
    }



    public function testAccessors()
    {
        $model = new DbModelStub($this->db, ['id' => 4, 'bar' => 42]);

        $this->assertEquals(4, $model->get('id'));
        $this->assertEquals(4, $model->id);
        $this->assertEquals(5, $model->get('nonexist', 5));
        $this->assertEquals('defaultfoo', $model->foo);
        $this->assertEquals(42, $model->bar);

        $model->set('foo', 'newfoo');
        $model->bar = 53;

        $this->assertEquals('newfoo', $model->foo);
        $this->assertEquals(53, $model->bar);
    }



    public function testStaticMethods()
    {
        $this->assertCount(2, DbModelStub::find($this->db, []));
        $this->assertEquals(2, DbModelStub::count($this->db, []));
        $this->assertInstanceOf('DbModelStub',
            DbModelStub::findOne($this->db, ['foo' => 'bar'], ['bar' => 'ASC']));

        $this->assertEquals(42,
            DbModelStub::findById($this->db, $this->db->getInsertId())->bar);
    }



    public function testSaveAndDelete()
    {
        $model = new DbModelStub($this->db, ['foo' => 'fresh new foo']);
        $model->save();

        $id = $model->id;

        $this->assertEquals('fresh new foo', DbModelStub::findById($this->db, $id)->foo);

        $model->foo = 'updatedfoo';
        $model->save();

        $this->assertEquals('updatedfoo', $model->findById($this->db, $id)->foo);

        $model->delete();
        $this->assertEquals(null, $model->findById($this->db, $id));
    }
}
