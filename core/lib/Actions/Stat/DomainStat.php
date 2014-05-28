<?php

namespace Zotto\Actions\Stat;

use Zotto\Actions\BaseAction;
use Zotto\Model\Collection\UserInfoCollection;
use Zotto\Model\Collection\VisitedPagesCollection;
use Zotto\Request;
use PhpBase\Mvc\Response;
use Zotto\StatTools\Metric\Domain;


class DomainStat extends BaseAction
{
    protected $_templateDir = 'Admin';

    use TimeFormManagement;

    /**
     * Выполняет действие
     *
     * @param Request/Api $request Объект запроса
     * @return Response
     */
    public function run(Request\Api $request)
    {

        $metric = new Domain();

        $startTime = intval($this->getStartTime($request));
        $endTime = intval($this->getEndTime($request));
        $refs = $metric->getSum('count', 'domain', $startTime, $endTime);

        $graphData[] = ['Домен', 'Количество'];

        if(!empty($refs)){
            //print_r($refs);
            foreach ($refs as $elem) {
                $graphData[]=array($elem['_id'], $elem['res']);
            }
        }




        $response = $this->_renderToResponse('DomainStat', array(
            'graphData'=>json_encode(array_values($graphData)),
            //'refs'=>$refs
            'refs'=>array()
        ));
        return $response;
    }
}