<?php
/**
 *
 */

namespace Zotto\Actions;

use Zotto\Request;
use PhpBase\Mvc\Response;

/**
 * Страница не найдена
 */
class NotFound extends BaseAction
{
    /**
     * Выполняет действие
     *
     * @param Request/Api $request Объект запроса
     * @return Response
     */
    public function run(Request\Api $request)
    {
        $response = $this->_renderJsonError('Not found', ['code' => 404001], 404);
        //:
        //    $this->_renderToResponse('error/notfound');

        $response->setStatus(404);

        return $response;
    }
}