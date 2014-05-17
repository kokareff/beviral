<?php


class ApplicationStub extends \PhpBase\Mvc\Application
{
    public function getDefaultResponse()
    {
        return new ResponseStub();
    }
}
