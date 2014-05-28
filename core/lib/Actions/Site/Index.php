<?php

namespace Zotto\Actions\Site;


use Zotto\Actions\BaseAction;
use Zotto\Request;
use PhpBase\Mvc\Response;


class Index extends BaseAction
{
    protected $_templateDir = 'Site';

    protected $_mapper = ['action'];
    /**
     * Выполняет действие
     *
     * @param Request/Api $request Объект запроса
     * @return Response
     */
    public function run(Request\Api $request)
    {
        return $this->onIndex($request);
    }

    public function onIndex(){
       return $this->_renderToResponse('Index', []);
    }
}