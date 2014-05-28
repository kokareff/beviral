<?php
namespace Zotto\Task\Tasks;

use Crypt_RSA;
use Net_SSH2;
use Zotto\Config\Files;
use Zotto\DOManager\DropletManager;
use Zotto\Task\ITask;
use Zotto\Task\Scheduler\CliMongoScheduler;
use Zotto\Task\TaskLog;

class UpdateCode implements ITask
{

    public function getParams()
    {
        $groups = (new DropletManager())->getAllDropletGroups();
        return [
            'group' =>
                [
                    'options' => array_combine($groups, $groups),
                    'label' => 'Группа'
                ],
        ];
    }

    public function start($info)
    {
        $taskId = $info['_id'];
        $groupName = $info['params']['group'];

        $sm = new CliMongoScheduler();
        $dm = new DropletManager();
        TaskLog::log($taskId, 'Start '.$groupName);


        $sm->setTaskProgress($taskId, 1);

        $servers = $dm->getGroup($groupName);

        $sm->setTaskProgress($taskId, 30);
        TaskLog::log($taskId, 'Pulling '.$groupName);

        $sshConfig = (new Files(CORE_CONFIG))->get('ssh');

        foreach ($servers as $serverData) {
            $ssh = new Net_SSH2($serverData['ip']);
            $key = new Crypt_RSA();
            $key->setPassword($sshConfig['pass']);
            $key->loadKey(file_get_contents( $sshConfig['privKey']));


            if (!$ssh->login('root', $key)) {
                TaskLog::log($taskId,'Login Failed '.$serverData['name']);
            } else {
                TaskLog::log($taskId, 'Login success '.$serverData['name']);
            }

            $ssh->exec('cd /var/www/qupa; pwd;', function($data) use ($taskId, $ssh, $serverData){
                TaskLog::log($taskId, 'ON '.$serverData['name'].' DATA: '.$data);
            });
        }

        $sm->setTaskProgress($taskId, 90);

        TaskLog::log($taskId, 'End');

        $sm->setTaskProgress($taskId, 100);
    }
}