<?php
/**
 * Фабрика экшенов
 */

namespace Zotto\Actions;

use PhpBase\Registry;
use Zotto\Request\Qupa;

/**
 * Фабрика обработчиков запросов экшенов
 */
class Factory implements \PhpBase\Mvc\IRouter
{
    /**
     * Возвращает объект действия для запроса
     *
     * @param \PhpBase\Mvc\Request $request Объект запроса
     *
     * @return \PhpBase\Mvc\IAction
     */
    public function getAction(\PhpBase\Mvc\Request $request)
    {
        $allowedModules = array(
            'Admin'
        );

        $path = $request->getPath();
        $path = trim($path, '/');
        $pathArray = explode('/', $path);
        $moduleName = array_shift($pathArray);
        $actionName = '';
        if (empty($moduleName)) {
            $moduleName = 'Site';
        } else {
            $moduleName = strtolower($moduleName);
            if (!in_array($moduleName, array_keys($allowedModules))) {
                $moduleName = 'Site';
                $actionName = $moduleName;
            } else {
                $actionName = array_shift($pathArray);
            }
        }
        if (empty($actionName)) {
            $actionName = 'Index';
        }


        $actionName = self::generateCamelCaseName($actionName);
        $moduleName = self::generateCamelCaseName($moduleName);
        if ($request instanceof Qupa) {
            $request->setParams($pathArray);
        }

        $app = Registry::getInstance()->app;
        if (file_exists(CORE_ACTIONS . $moduleName . DIRECTORY_SEPARATOR . $actionName . '.php')) {
            $action = __NAMESPACE__ . '\\' . $moduleName . '\\' . $actionName;
            return new $action($app);
        }


        return new NotFound($app);
    }

    public static function generateCamelCaseName($string)
    {
        $string = ucwords(strtolower($string));
        if (strpos($string, '-') !== false) {
            $string = implode('', array_map('ucfirst', explode('-', $string)));
        }
        return $string;
    }
}