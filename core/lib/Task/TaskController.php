<?php
/**
 * Created by crmMaster.
 * Date: 05.11.13
 */

namespace Zotto\Task;

use PhpBase\Mvc\Request;
use Zotto\Request\Api;
use Zotto\Task\Scheduler\Scheduler;
use Zotto\Task\Scheduler\CliMongoScheduler;

class TaskController {

   public function summary() {
      $scheduler = new CliMongoScheduler();
      $tasks =  $scheduler->getCurrentTasks(array(Scheduler::TASK_STATUS_STARTED,
         Scheduler::TASK_STATUS_ABORTED_BY_ERRORS, Scheduler::TASK_STATUS_WAITING));
      return array('tasks' =>$tasks);
   }

   public function add_task() {
      $tm = new TaskModel();
      $scheduler = new CliMongoScheduler();
      return array('allowedTasks' => $tm->getAllowedTasks(), 'waitTasks'=>$scheduler->getCurrentTasks(array(Scheduler::TASK_STATUS_WAITING)));
   }

   public function get_task_params($taskName) {
      $tm = new TaskModel();
      return $tm->getTaskParams($taskName);
   }

   public function check_add_task(Request $request) {
      $errors = array();
       $taskName = $request->post->get('taskName', '');

      if (!TaskModel::isAvailable($taskName)) {
         $errors['taskName'] = 'Неверное название таска';
      }

       $params = $request->post->get('params', null);


      if (empty($errors)) {
         $sheduler = new CliMongoScheduler();
         $sheduler->addTask($taskName, $params);
      }
      return array('errors'=>$errors);
   }

   public function abort_task($taskId){
      $scheduler = new CliMongoScheduler();
      $scheduler->abortTask($taskId);
       return array();
   }

   public function update_schedule(){
      $scheduler = new CliMongoScheduler();
      $scheduler->update();
       return array();
   }
}