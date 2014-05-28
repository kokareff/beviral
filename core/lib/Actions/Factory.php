<?php
/**
 * Фабрика экшенов
 */

namespace Zotto\Actions;

use PhpBase\Mvc\Request;
use PhpBase\Registry;
use Zotto\Model\ACLUserModel;
use Zotto\Model\UserModel;
use Zotto\Request\Api;
use Zotto\Request\DMP;

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
        $allowedModules = [
            'admin',  'stat',
            'api', 'site',
            'rekl', 'wm'
        ];

        $aclModules = [
            'Admin', 'Stat',
            'Rekl', 'Wm'
        ];

        $path = $request->getPath();
        $path = trim($path, '/');
        $pathArray = explode('/', $path);
        $moduleName = array_shift($pathArray);
        $actionName = '';

        if (empty($moduleName)) {
            $moduleName = 'Site';
        } else {
            $moduleName = strtolower($moduleName);
            if (!in_array($moduleName, $allowedModules)) {
                $moduleName = 'Site';
                $actionName = $moduleName;
            } else {
                $actionName = array_shift($pathArray);
            }
        }
        if (empty($actionName)) {
            $actionName = 'Index';
        }

        $moduleName = self::generateCamelCaseName($moduleName);
        $actionName = self::generateCamelCaseName($actionName);

        $app = Registry::getInstance()->app;

        if(in_array($moduleName, $aclModules)){
            $acl = new ACLUserModel();

            if(!$acl->isAllowed($moduleName)){
                return new NotFound($app);
            }
        }

        if ($request instanceof Api) {
            $request->setParams($pathArray);
        }

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
        if(strpos($string, '.')){
            $string = implode('', array_map('ucfirst', explode('.', $string)));
        }
        return $string;
    }
}