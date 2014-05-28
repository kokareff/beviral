<?php
namespace Zotto\MessagePack;

use Zotto\PerfomanceTester;
use Zotto\Task\TaskLog;

require_once dirname(__FILE__) . '/MessagePackRPC_Back.php';

class MessagePackRPC_Client
{
    public $back = null;
    public $host = null;
    public $port = null;

    public function __construct($host, $port, $back = null)
    {
        $this->back = $back == null ? new MessagePackRPC_Back() : $back;
        $this->host = $host;
        $this->port = $port;
    }

    public function send($func, $args)
    {
        $host = $this->host;
        $port = $this->port;
        $code = 0;
        $call = $this->back->clientCallObject($code, $func, $args);
        $send = $this->back->clientConnection($host, $port, $call);
        $feature = $this->back->clientRecvObject($send);

        $result = $feature->getResult();
        $errors = $feature->getErrors();

        if (!is_null($errors)) {
            print_r($errors);
            if (is_array($errors)) {
                $errors = '[' . implode(', ', $errors) . ']';
            } else if (is_object($errors)) {
                if (method_exists($errors, '__toString')) {
                    $errors = $errors->__toString();
                } else {
                    $errors = print_r($errors, true);
                }
            }

            throw new MessagePackRPC_Error_RequestError("{$errors}");
        }

        return $result;
    }

    public function call($func, $args)
    {
       // PerfomanceTester::startPerfomanceTest();

        $data =  $this->send($func, $args);

      //  PerfomanceTester::endPerfomanceTest();
      //sus  TaskLog::log('PerfomanceTest', print_r(PerfomanceTester::getPerfomanceMetrics()+['func'=>$func, 'arg'=>$args], true));

        return $data;
    }
}
