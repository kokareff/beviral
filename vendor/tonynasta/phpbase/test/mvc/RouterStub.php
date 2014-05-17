<?php



class RouterStub implements \PhpBase\Mvc\IRouter
{
    public function __construct($action) {
        $this->action = $action;
    }


    public function getAction(\PhpBase\Mvc\Request $request)
    {
        return $this->action;
    }
}
