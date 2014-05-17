<?php


require_once __DIR__ . '/autoload.php';


class RegistryTest extends PHPUnit_Framework_TestCase
{
    public  function testInstance()
    {
        $instance1 = \PhpBase\Registry::getInstance();
        $instance2 = \PhpBase\Registry::getInstance();

        $this->assertEquals($instance1, $instance2);

        $instance1->foo = 'bar';
        $this->assertEquals($instance1->foo, $instance2->foo);
    }


    public function testGetSet()
    {
        $registry = new \PhpBase\Registry;

        $registry->foo = 'bar';
        $registry->set('baz', 666);

        $this->assertEquals(666, $registry->baz);
        $this->assertEquals('bar', $registry->get('foo'));
        $this->assertEquals('default', $registry->get('nonexistent', 'default'));
    }

}
