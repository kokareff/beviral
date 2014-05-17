<?php
/**
 * Класс MVC приложения
 */
namespace PhpBase\Mvc;

/**
 * Класс MVC приложения
 */
class Application
{
    /**
     * Обрабатывает запрос, выводит результат
     *
     * @param Request $request Объект запроса
     * @param IRouter $router Объект фабрики действий
     * @return void
     */
    public  function run(Request $request, IRouter $router)
    {
        $action = $router->getAction($request);
        unset($router);

        if ($action != null) {
            $response = $action->run($request);
            unset($action);

            if (!($response instanceof Response)) {
                $response = $this->getDefaultResponse();
                $response->setStatus(500);
            }

        } else {
            $response = $this->getDefaultResponse();
            $response->setStatus(404);
        }

        $response->send();
    }


    /**
     * Возвращат объект ответа по-умолчанию
     *
     * @return Response
     */
    public function getDefaultResponse()
    {
        return new Response();
    }
}
