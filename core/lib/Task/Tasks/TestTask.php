<?php
namespace Zotto\Task\Tasks;
use Crypt_RSA;
use Net_SSH2;
use React\EventLoop\Factory;
use React\EventLoop\Timer\Timer;
use React\Stream\Stream;
use Zotto\Config\Files;
use Zotto\Task\ITask;
use Zotto\Task\Scheduler\CliMongoScheduler;
use Zotto\Task\TaskLog;

class TestTask implements ITask
{

    public function getParams()
    {
        return ['type' =>
            ['options' => ['refresh' => 'Обновить', 'rewrite' => 'Перезаписать'],
                'label' => 'Действие'
            ],
            'tones' => ['options' => [
                'all' => 'Все',
                'neg' => 'Негативные',
                'pos' => 'Позитивные'
            ], 'label' => 'Тональность']];
    }

    public function start($info)
    {
        $sm = new CliMongoScheduler();
        $taskId=$info['_id'];

        $servers = [
            '146.185.165.248'
        ];

        $sshConfig = (new Files(CORE_CONFIG))->get('ssh');

        foreach ($servers as $ip) {
            $ssh = new Net_SSH2($ip);
            $key = new Crypt_RSA();
            $key->setPassword($sshConfig['pass']);
            $key->loadKey(file_get_contents( $sshConfig['privKey']));
            if (!$ssh->login('root', $key)) {
                TaskLog::log($taskId,'Login Failed');
            }

            $ssh->exec('pwd', function($data) use ($taskId){
                TaskLog::log($taskId, $data);
            });
        }

        $sm->setTaskProgress($info['_id'], 100);
    }
}