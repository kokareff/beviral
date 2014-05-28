<?php
/**
 * Точка входа в трекер
 */

namespace Zotto;


use PhpBase\Mvc;
use PhpBase\Mvc\Request;
use Zotto\Actions\BaseAction;
use Zotto\Request\Api;

/**
 * Класс приложения
 */
class Application extends Mvc\Application
{

    /**
     * Плагины
     *
     * @var array
     */
    protected $_plugins = [];


    /**
     * Конструктор класса
     */
    public function __construct ()
    {

    }

    /**
     * Обрабатывает запрос, выводит результат
     *
     * @param Mvc\Request $request Объект запроса
     * @param Mvc\IRouter $router  Объект фабрики действий
     *
     * @return void
     */
    public function run (Request $request, Mvc\IRouter $router)
    {

        $action = $router->getAction($request);

        if ($action != null) {
            $response = null;
            if($action instanceof BaseAction && $request instanceof Api){
                $request->params = $action->mapParams($request);
                $innerAction = $request->params->get('action', false);
                if($innerAction){
                    $response = $action->handleAction($innerAction, $request);
                }
            }
            if($response===null){
                $response = $action->run($request);
            }
            unset($action);

            if (! ($response instanceof Mvc\Response)) {
                $response = $this->getDefaultResponse();
                $response->setStatus(500);
            }

        } else {
            $response = $this->getDefaultResponse();
            $response->setStatus(404);
        }

        $response->send();
    }
}