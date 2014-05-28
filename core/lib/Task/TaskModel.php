<?php
namespace Zotto\Task;
use Zotto\Task\ITask;
use Zotto\Task\Tasks;
/**
 * Created by crmMaster.
 * Date: 05.11.13
 */
class TaskModel
{
    protected static  $_config;
    public function __construct()
    {
        self::$_config = (new \Zotto\Config\Files(CORE_CONFIG))->get('tasks');
    }

    public function getAllowedTasks()
    {
        $data = scandir(self::$_config['dir']);
        unset($data[0]);
        unset($data[1]);
        foreach ($data as &$taskName) {
            $taskName = substr($taskName, 0, strlen($taskName) - 4);
        }
        return $data;
    }

    public function getTaskParams($taskName)
    {
        if ($this->isAvailable($taskName)) {
            $taskClass = 'Zotto\\Task\\Tasks\\'.$taskName;
            $task = new $taskClass();
            if ($task instanceof ITask) {
                return $task->getParams();
            }
        }
        return array();
    }

    public static function isAvailable($taskName)
    {
        self::$_config = (new \Zotto\Config\Files(CORE_CONFIG))->get('tasks');
        return file_exists(self::$_config['dir'] . $taskName . '.php');
    }
}