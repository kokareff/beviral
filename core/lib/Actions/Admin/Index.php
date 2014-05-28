<?php

namespace Zotto\Actions\Admin;

use Zotto\Actions\BaseAction;
use Zotto\Request;
use PhpBase\Mvc\Response;


class Index extends BaseAction
{
    protected $_templateDir = 'Admin';

    /**
     * Выполняет действие
     *
     * @param Request/Api $request Объект запроса
     * @return Response
     */
    public function run(Request\Api $request)
    {
        $response = $this->_renderToResponse('Index', array('tst'=>'tst'));
        return $response;
    }
}