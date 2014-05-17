<?php


class ResponseStub extends \PhpBase\Mvc\Response
{
    public function setStatus($code) {
        $this->setBody($code);
    }

    public function send() {
        echo $this->_body;
    }
}
