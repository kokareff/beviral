<?php
/**
 * Created by crmMaster.
 * Date: 05.11.13
 */
namespace Zotto\Task;

interface ITask {
   public function getParams();
   public function start($info);
}