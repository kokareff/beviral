<?php



require_once __DIR__ . '/../autoload.php';

require_once 'ApplicationStub.php';
require_once 'RequestStub.php';
require_once 'RouterStub.php';
require_once 'ResponseStub.php';
require_once 'ActionStub.php';



class ApplicationTest extends PHPUnit_Framework_TestCase
{
    public  function testCallChain()
    {
        $app = new \PhpBase\Mvc\Application;

        $this->expectOutputString('stub response');
        $app->run(new RequestStub, new RouterStub(new ActionStub('stub response')));
    }

    public function testInvalidActionResponse()
    {
        $app = new ApplicationStub;

        $this->expectOutputString('404');
        $app->run(new RequestStub, new RouterStub(null));
    }
}
