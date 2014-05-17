<?php
/**
 *
 */

namespace Zotto\Actions;

use Zotto\Actions\Action;
use Zotto\Request;
use PhpBase\Mvc\Response;

/**
 * Страница не найдена
 */
class NotFound extends Action
{
    /**
     * Выполняет действие
     *
     * @param Request\Qupa $request Объект запроса
     * @return Response
     */
    public function run(Request\Qupa $request)
    {
        $response = $this->_renderJsonError('Not found', ['code' => 404001], 404);
        //:
        //    $this->_renderToResponse('error/notfound');

        $response->setStatus(404);

        return $response;
    }
}