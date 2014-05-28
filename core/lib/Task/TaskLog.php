<?php
/**
 * Created by crmMaster.
 * Date: 05.11.13
 */
namespace Zotto\Task;

class TaskLog {

    public static function log($taskId, $what){
        (new \Zotto\Model\Collection\LogCollection())->insert(array('taskId'=>$taskId, 'time'=>time(), 'message'=>$what));
    }

    public static function getLog($taskId){
        if(!empty($taskId)){
            return (new \Zotto\Model\Collection\LogCollection())->getBy('taskId', new \MongoId($taskId));
        }
        return [];
    }
}


