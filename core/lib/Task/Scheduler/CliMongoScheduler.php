<?php
namespace Zotto\Task\Scheduler;
use Zotto\Config\Files;
use Zotto\Db\QupaCacheCollectionAdapter;
use Zotto\Db\MainCollectionAdapter;

/**
 * Created by crmMaster.
 * Date: 05.11.13
 */

class CliMongoScheduler extends Scheduler {

   protected $maxWorkersCount = 2;
   protected $idFieldName = '_id';

   public function getCurrentTasks($status = array(self::TASK_STATUS_STARTED), $limit = null, $offset = null) {
      $sm = new SchedulerCol();
      $find = $sm->findBy('status', $status);
      if (!is_null($offset)) {
         $find = $find->skip($offset);
      }
      if (!is_null($limit)) {
         $find = $find->limit($limit);
      }

      return iterator_to_array($find);
   }

   protected function setTaskInfo($taskId, $newFields) {
      $sm = new SchedulerCol();
      if (is_null($taskId)) {
         $sm->insert($newFields);
      }
      else {
         if (!($taskId instanceof \MongoId)) {
            $taskId = new \MongoId($taskId);
         }
         $sm->set($taskId, $newFields);
      }
   }

   public function getTask($taskId) {
      $sm = new SchedulerCol();
      if (!($taskId instanceof \MongoId)) {
         $taskId = new \MongoId($taskId);
      }
      return $sm->getById($taskId);
   }

   public function startTask($taskId) {
      parent::startTask($taskId);
       $dir = (new Files(CORE_CONFIG))->get('tasks');
       $dir = $dir['cli'];
       $cmd = 'php '.$dir.'start_task.php -i=' . $taskId;

      if(self::execInBackground($cmd)===false){
          $this->abortTask($taskId);
      };
   }

    public function getCurrentTasksCount()
    {
        $sm = new SchedulerCol();
        return $sm->findBy('status', self::TASK_STATUS_STARTED)->count();
    }
}

class SchedulerCol extends MainCollectionAdapter {
    protected $collectionName = 'scheduler';
}