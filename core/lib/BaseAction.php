<?php
/**
 * Базовый класс Api действия
 */


namespace Zotto\Actions;


use PhpBase\Config;
use PhpBase\Mvc\Request;
use PhpBase\Mvc\Response;
use PhpBase\Registry;
use PhpBase\SmartArray;
use Zotto\Application;
use Zotto\Request\Api;
use Smarty;
use Zotto\Templater\SmartyTemplater;
use Zotto\Templater\Templater;

/**
 * Абстрактный обработчик
 */
abstract class BaseAction
{
    /**
     * Объект приложения
     *
     * @var Application
     */
    protected $_app;

    /**
     * Шаблонизатор
     * @var Templater
     */
    protected $_templater;
    protected $_templateDir;

    protected $_mapper = array();

    public $params;
    public $rawParams;
    /**
     * Конструктор
     *
     * @param Application $app Объект приложения
     */
    public function __construct(Application $app)
    {
        $this->_app = $app;
    }


    /**
     * Выполняет действие
     *
     * @param Api $request Объект запроса
     *
     * @return Response
     */
    abstract public function run(Api $request);


    public function mapParams($mapper){
        $mappedParams = array();
        foreach ($mapper as $index => $mapName) {
            if(isset($this->rawParams[$index])){
                $mappedParams[$mapName] = $this->rawParams[$index];
            }
        }
        $this->params = new SmartArray($mappedParams);
    }

    public function setParams($newParams){
        $this->rawParams = $newParams;
    }

    public function handleAction($actionName, $request){
        if(!empty($actionName)){
            $actionName = 'on'.Factory::generateCamelCaseName($actionName);
            if(is_callable(array($this, $actionName))){
                return $this->{$actionName}($request);
            }
        }
        return null;
    }

    /**
     * Возвращает raw ответ
     *
     * @param string $body Тело ответа
     *
     * @return Response
     */
    protected function _renderRawData($body, $script = false)
    {
        $response = new Response();
        if($script){
            $response->setHeader('Content-Type', 'application/json; charset=utf8');
            $response->setHeader('Access-Control-Allow-Origin','*');
        }
        $response->setBody($body);


        return $response;
    }

    /**
     * Возвращает объект ответа с данными
     *
     * @param array $data Данные
     *
     * @return Response
     */
    protected function _renderJsonData(array $data)
    {
        return $this->_renderJsonToResponse('', $data);
    }


    /**
     * Возвращает объект ответа с ошибкой
     *
     * @param string $error      Ошибка
     * @param array  $data       Данные
     * @param int    $statusCode Статус код
     *
     * @return Response
     */
    protected function _renderJsonError($error, $data = [], $statusCode = 200)
    {
        return $this->_renderJsonToResponse($error, $data, $statusCode);
    }


    /**
     * Возвращает объект ответа
     *
     * @param string $error      Ошибка
     * @param array  $data       Данные
     * @param int    $statusCode Статус код
     *
     * @return Response
     */
    private function _renderJsonToResponse(
        $error, array $data, $statusCode = 200
    ) {
        $response = new Response;

        if(ENVIRONMENT=="production"){
            $response->setHeader('Content-Type', 'application/json; charset=utf8');
        } else {
            $response->setHeader('Content-Type', 'application/json; charset=utf8');
        }

        $response->setStatus($statusCode);

        $body = [
            'error' => $error,
            'data' => $data
        ];

        $opts = JSON_UNESCAPED_UNICODE;

        if (ENVIRONMENT === 'dev') {
            $opts |= JSON_PRETTY_PRINT;
        }

        $response->setBody(json_encode($body, $opts));

        return $response;
    }

    protected function _renderToResponse($template, $data = [])
    {
        $template = $template.'.tpl';
        if($this->_templateDir!==null){
            $template = $this->_templateDir.'/'.$template;
        }

        if($this->_templater===null){
            $this->_templater = new SmartyTemplater();
        }

        return $this->_renderRawData(
            $this->_templater->render($template, $data)
        );

    }
}