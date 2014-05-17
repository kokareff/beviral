<?php
namespace Zotto\Task\Scheduler;
use PhpBase\Config\Files;

/**
 * Created by crmMaster.
 * Date: 05.11.13
 */

class CliMongoScheduler extends Scheduler {

   protected $maxWorkersCount = 4;
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
}

class SchedulerCol extends \Zotto\Db\CollectionAdapter {
    protected $collectionName = 'scheduler';
}