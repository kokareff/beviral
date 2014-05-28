<?php
/**
 * Created by crmMaster.
 * Date: 04.11.13
 */

namespace Zotto\Task\Scheduler;

use Zotto\Task\TaskLog;

abstract class Scheduler
{

    const TASK_STATUS_WAITING = 1;
    const TASK_STATUS_STARTED = 2;
    const TASK_STATUS_FINISHED = 3;
    const TASK_STATUS_ABORTED = 4;
    const TASK_STATUS_ABORTED_BY_ERRORS = 5;

    protected $maxWorkersCount;
    protected $idFieldName = 'id';

    public function addTask($taskName, $taskParams)
    {
        $this->setTaskInfo(null, array('status' => self::TASK_STATUS_WAITING, 'progress' => 0, 'params' => $taskParams,
            'name' => $taskName));
    }

    abstract protected function setTaskInfo($taskId, $newFields);

    public function abortTask($taskId, $errors = array())
    {
        if (count($errors) == 0) {
            $this->setTaskInfo($taskId, array('status' => self::TASK_STATUS_ABORTED));
        } else {
            $this->setTaskInfo($taskId, array('status' => self::TASK_STATUS_ABORTED_BY_ERRORS, 'errors' => $errors));
        }
    }

    public function getTaskStatus($taskId)
    {
        $taskInfo = $this->getTask($taskId);
        return $taskInfo['status'];
    }

    abstract public function getTask($taskId);

    public function setTaskProgress($taskId, $newProgress)
    {
        if ($newProgress >= 100) {
            $newProgress = 100;
            $this->setTaskInfo($taskId, array('progress' => $newProgress, 'status' => self::TASK_STATUS_FINISHED));
        } else {
            $this->setTaskInfo($taskId, array('progress' => $newProgress));
        }
    }

    public function update()
    {
        TaskLog::log('Sheduler update', print_r($this->getCurrentTasks(), true));
        $currTasksCount = $this->getCurrentTasksCount();
        if ($this->maxWorkersCount > $currTasksCount) {
            $tasksForStart = $this->getCurrentTasks(array(self::TASK_STATUS_WAITING), $this->maxWorkersCount - $currTasksCount);
            foreach ($tasksForStart as $task) {
                $this->startTask($task[$this->idFieldName]);
            }
        }

    }

    abstract public function getCurrentTasks($status = array(self::TASK_STATUS_STARTED), $limit = null);

    abstract public function getCurrentTasksCount();

    protected function startTask($taskId)
    {
        $this->setTaskInfo($taskId, array('status' => self::TASK_STATUS_STARTED));
    }

    public static function execInBackground($cmd) {
        if (substr(php_uname(), 0, 7) == "Windows") {
            $handler =  popen("start /B " . $cmd, "r");
            if(!$handler){
                return false;
            } else {
                 pclose(
                    $handler
                );
            }


        }
        else {
            exec($cmd . " > /dev/null &");
        }
    }

    public static function exec($cmd) {
        if (substr(php_uname(), 0, 7) == "Windows") {
            $handler =  popen("start /B " . $cmd, "r");
            if(!$handler){
                return false;
            } else {
                return pclose(
                    $handler
                );
            }

        }
        else {
            return exec($cmd . ' 2>&1');
        }
    }

}