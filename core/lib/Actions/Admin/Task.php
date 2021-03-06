<?php

namespace Zotto\Actions\Admin;

use PhpBase\Mvc\Request;
use PhpBase\Mvc\Response;
use Zotto\Actions\BaseAction;
use Zotto\Request\Api;
use Zotto\Task\TaskController;
use Zotto\Task\TaskLog;


class Task extends BaseAction
{
    protected $_templateDir = 'Admin';

    /**
     * @var TaskController
     */
    protected $taskController;


    /**
     * Выполняет действие
     *
     * @param Request $request Объект запроса
     * @return Response
     */
    public function run(Api $request)
    {
        $this->taskController = new TaskController();

        $actionReturn = null;
        if (isset($request->rawParams[0])) {
            $actionReturn = $this->handleAction($request->rawParams[0], $request);
        }

        if (!$actionReturn) {
            return $this->_renderToResponse('Task', $this->taskController->summary());
        } else {
            return $actionReturn;
        }
    }


    public function onAdd($request)
    {
        return $this->_renderToResponse('Task.add', $this->taskController->add_task());
    }

    public function onParams($request)
    {
        return $this->_renderJsonData($this->taskController->get_task_params($request->post->get('taskName', '')));
    }

    public function onDel($request)
    {
        return $this->_renderJsonData($this->taskController->abort_task($request->get->get('taskId', '')));
    }

    public function onUpdate($request)
    {
        return $this->_renderJsonData($this->taskController->update_schedule());
    }

    public function onWrite($request)
    {
        return $this->_renderJsonData($this->taskController->check_add_task($request));
    }

    public function onLog($request){
        return $this->_renderToResponse('Task.log', ['logs'=>TaskLog::getLog($request->get->get('taskId', null))]);
    }


}