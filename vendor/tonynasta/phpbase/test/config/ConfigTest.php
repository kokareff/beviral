<?php


require_once __DIR__ . '/../autoload.php';
require_once __DIR__ . '/ConfigAdapteStub.php';


class ConfigTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \PhpBase\Config
     */
    protected $_config;

    public function setUp()
    {
        $adapter = new configAdapteStub([
            'global' => [
                'key' => 'value'
            ],
            'example' => [
                'foo' => 'bar',
                'bar' => 666
            ],
            'invalid' => 3
        ]);

        $this->_config = new \PhpBase\Config($adapter);
    }

    public function tearDown()
    {
        $this->_config = null;
    }



    public function testGet()
    {
        $this->assertEquals('value', $this->_config->get('global', 'key'));
        $this->assertEquals(null, $this->_config->get('nonexistent', 'key'));
        $this->assertEquals(null, $this->_config->get('global', 'nonexistent'));
        $this->assertEquals('bar', $this->_config->get('example', 'foo'));
        $this->assertEquals(666, $this->_config->get('example', 'bar'));
        $this->assertEquals(null, $this->_config->get('invalid', 'key'));
    }


    public function testSet()
    {
        $this->_config->set('global', 'key', 'overwrite');
        $this->_config->set('example', 'key', 'newval');
        $this->_config->set('nonexistent', 'bar', 'newkeyspace');
        $this->assertEquals('overwrite', $this->_config->get('global', 'key'));
        $this->assertEquals('newval', $this->_config->get('example', 'key'));
        $this->assertEquals('newkeyspace', $this->_config->get('nonexistent', 'bar'));
    }


    public function testKeySpaces()
    {
        $keys = $this->_config->getDefinedKeyspaces();
        $this->assertTrue(in_array('global', $keys));
        $this->assertTrue(in_array('example', $keys));
        $this->assertTrue(in_array('invalid', $keys));

        $this->assertTrue(is_array($this->_config->getValues('global')));
    }
}
