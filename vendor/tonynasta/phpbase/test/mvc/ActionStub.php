<?php



class ActionStub implements \PhpBase\Mvc\IAction
{
    public function __construct($body) {
        $this->body = $body;
    }


    public function run(\PhpBase\Mvc\Request $request)
    {
        $response = new ResponseStub;
        $response->setBody($this->body);
        return $response;
    }
}
