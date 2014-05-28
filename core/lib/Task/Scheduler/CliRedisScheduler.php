<?php
namespace Zotto\Scheduler;
use Zotto\Config\Files;
use Zotto\Task\Scheduler\Scheduler;

/**
 * Created by crmMaster.
 * Date: 05.11.13
 */

class CliRedisScheduler extends Scheduler
{

    protected $maxWorkersCount = 16;
    protected $_redis = null;

    const NAME_TASK_INFO = 'taskInfo';

    public function __construct(\Redis $redis)
    {
        $this->_redis = $redis;
    }

    public function getCurrentTasks($status = array(self::TASK_STATUS_STARTED), $limit = null, $offset = null)
    {
        $data = array();
        foreach ($status as $statusId) {
            $key= $this->getTaskListName($statusId);
            $data[$statusId] = $this->_redis->lRange($key, $offset, $limit);
        }

        return $data;
    }

    protected function getTaskListName($status){
        return 'taskList_'.$status;
    }

    protected function setTaskInfo($taskId, $newFields)
    {
        if(is_null($taskId)){
            $taskId = uniqid();
        }
        $oldFields = array();
        if($this->_redis->hExists(self::NAME_TASK_INFO, $taskId)){
            $oldFields = $this->_redis->hGet(self::NAME_TASK_INFO, $taskId);
        }
        $newFields = array_merge($oldFields, $newFields);

        $this->_redis->hSet(self::NAME_TASK_INFO, $taskId, json_encode($newFields));
    }

    public function getTask($taskId)
    {
      return $this->_redis->hGet(self::NAME_TASK_INFO, $taskId);
    }

    public function startTask($taskId)
    {
        parent::startTask($taskId);
        $dir = (new Files(CORE_CONFIG))->get('tasks');
        $dir = $dir['cli'];
        $cmd = 'php '.$dir.'start_task.php -i=' . $taskId;
        self::execInBackground($cmd);
    }

    public function getCurrentTasksCount()
    {
        return count($this->getTaskListName(self::TASK_STATUS_STARTED));
    }
}