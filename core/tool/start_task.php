<?php
/**
 * Created by crmMaster.
 * Date: 05.11.13
 */
require __DIR__ . '/../../core/zotto.php';

define('ENVIRONMENT', isset($_SERVER['ENV']) ? $_SERVER['ENV'] : 'production');
define('BASE_DIR', __DIR__ . '/../../');

use Zotto\Task\ITask;
use Zotto\Task\Scheduler\CliMongoScheduler;
use Zotto\Task\TaskModel;

$options = getopt('i:');
$taskId = $options['i'];

error_reporting(E_ALL);

set_error_handler(function($errno, $errstr, $errfile, $errline) use ($taskId){
    \Zotto\Task\TaskLog::log(new MongoId($taskId), $errstr.' FILE '.$errfile. ' LINE '.$errline);
});

$sm = new CliMongoScheduler();
$taskInfo = $sm->getTask($taskId);

if(ENVIRONMENT=='dev'){
    ini_set('mongo.native_long', 0);
    ini_set('mongo.long_as_object', 1);
}


if(TaskModel::isAvailable($taskInfo['name'])){
    try{
    $taskClass = 'Zotto\\Task\\Tasks\\'.$taskInfo['name'];
    $task = new $taskClass();

   if($task instanceof ITask){
      $task->start($taskInfo);

   }
    } catch (Exception $e){
        $sm->abortTask($taskId, array($e->getMessage().'    '.$e->getTraceAsString()));
    }
} else {
   $sm->abortTask($taskId, array('Task not allowed'));
}

$sm->update();




