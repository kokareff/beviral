<?php



require_once __DIR__ . '/../autoload.php';




class RedisAdapterTest extends PHPUnit_Framework_TestCase
{
    public function testAdapter()
    {
        $prefix = 'config_test:';
        $redis = new Redis;
        $redis->connect('127.0.0.1', 6379);

        foreach ($redis->keys($prefix . '*') as $key) {
            $redis->del($key);
        }


        $adapter = new \PhpBase\Config\Redis($redis, $prefix);

        $adapter->set('foo', 'bar');
        $adapter->set('barrray', ['foo' => 'baz']);

        $this->assertEquals('bar', $adapter->get('foo'));
        $this->assertEquals('baz', $adapter->get('barrray')['foo']);
        $this->assertCount(2, $adapter->getKeys());
    }
}
