<?php
namespace Zotto\Task\Tasks;
use Zotto\Task\ITask;
use Zotto\Task\Scheduler\CliMongoScheduler;

class TestTask implements ITask
{

    public function getParams()
    {
        return array('type' =>
            array('options' => array('refresh' => 'Обновить', 'rewrite' => 'Перезаписать'),
                'label' => 'Действие'
            ),
            'tones' => array('options' => array(
                'all' => 'Все',
                'neg' => 'Негативные',
                'pos' => 'Позитивные'
            ), 'label' => 'Тональность'));
    }

    public function start($info)
    {
        $sm = new CliMongoScheduler();

        $sm->setTaskProgress($info['_id'], 100);
    }
}