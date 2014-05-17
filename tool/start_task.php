<?php
/**
 * Created by crmMaster.
 * Date: 05.11.13
 */
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../core/core.php';

define('ENVIRONMENT', isset($_SERVER['ENV']) ? $_SERVER['ENV'] : 'production');
define('BASE_DIR', __DIR__ . '/../');

use Zotto\Task\ITask;
use Zotto\Task\Scheduler\CliMongoScheduler;
use Zotto\Task\TaskModel;

error_reporting(E_ALL);

$options = getopt('i:');
$taskId = $options['i'];

$sm = new CliMongoScheduler();
$taskInfo = $sm->getTask($taskId);

set_error_handler(function($errno, $errstr, $errfile, $errline) use ($taskId){
   file_put_contents(ZOTTO_ROOT.$taskId.'.log', $errstr.' FILE '.$errfile. ' LINE '.$errline);
});



if(TaskModel::isAvailable($taskInfo['name'])){
    $taskClass = 'Zotto\\Task\\Tasks\\'.$taskInfo['name'];
    $task = new $taskClass();

   if($task instanceof ITask){
      $task->start($taskInfo);
   }
} else {
   $sm->abortTask($taskId, array('Task not allowed'));
}

$sm->update();




