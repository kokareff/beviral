<?php

namespace Zotto\Actions\Admin;

use Zotto\Actions\Action;
use Zotto\Request;
use PhpBase\Mvc\Response;


class Main extends Action
{
    protected $_templateDir = 'Admin';

    /**
     * Выполняет действие
     *
     * @param Request\Qupa $request Объект запроса
     * @return Response
     */
    public function run(Request\Qupa $request)
    {
        $response = $this->_renderToResponse('Main', array('tst'=>'tst'));
        return $response;
    }
}