<?php
/**
 * Точка входа в трекер
 */

namespace Zotto;


use PhpBase\Mvc;
use Zotto\Actions\Action;
use Zotto\Request\Qupa;

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
     * Добавляет плагин
     *
     * @param Plugins\Plugin $plugin Объект плагина
     *
     * @return bool
     */
  /*  public function addPlugin (Plugins\Plugin $plugin)
    {
        $this->_plugins[] = $plugin;

        return true;
    } */


    /**
     * Обрабатывает запрос, выводит результат
     *
     * @param Mvc\Request $request Объект запроса
     * @param Mvc\IRouter $router  Объект фабрики действий
     *
     * @return void
     */
    public function run (Mvc\Request $request, Mvc\IRouter $router)
    {
        /** @var Plugins\Plugin $plugin */
      /*  foreach ($this->_plugins as $plugin) {
            $plugin->handleRequest($request);
        }*/

        $action = $router->getAction($request);
        unset($router);

        if ($action != null) {
            if($action instanceof Action){
                $action->mapParams($request);
            }
            $response = $action->run($request);
            unset($action);

            if (! ($response instanceof Mvc\Response)) {
                $response = $this->getDefaultResponse();
                $response->setStatus(500);
            }

        } else {
            $response = $this->getDefaultResponse();
            $response->setStatus(404);
        }


     /*   foreach ($this->_plugins as $plugin) {
            $plugin->handleResponse($response);
        } */

        $response->send();
    }
}