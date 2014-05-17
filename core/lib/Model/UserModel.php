<?php

namespace Zotto\Model;
use PhpBase\Config\Files;
use Zotto\Db\CollectionAdapter;
use Zotto\Model\Collection\UserInfoCollection;
use Zotto\Model\Collection\VisitedPagesCollection;

class UserModel {
    public function writeTrack($data){
        return (new UserInfoCollection())->insert($data);
    }

    public function addVisitedPage($userId, $url, $data=array()){
        if (!empty($data['tradeId'])){
            UserPreferenciesModel::refreshUserRating($userId, $data['tradeId'], $data['refId'], array('visit'=>true));
        }

        return (new VisitedPagesCollection())
                    ->insert(array_merge(
                    array(),
                    $this->getPageData($userId, $url, $data))
         );
    }

    public function saveAction($userId, $url, $actionType, $data = array()){
        return (new CollectionAdapter('userAction_'.$actionType))
            ->insert(array_merge(
            array(),
            $this->getPageData($userId, $url, $data)));
    }

    private function getPageData($userId, $url, $data=array()){
        $urlData = parse_url($url);
        $domain = '';
        if (isset($urlData['host'])) {
            $domain = $urlData['host'];
        }
        return array_merge(
            array('user'=>new \MongoId($userId), 'time'=>$_SERVER['REQUEST_TIME'],
                  'ip'=>$_SERVER['REMOTE_ADDR'], 'domain'=>$domain, 'url'=>$url),
            $data
        );
    }

    public function getAllVisitedProducts(\MongoId $userId=null, $lastUpdateTime=null){
        $criteria = array('tradeId'=>array('$ne'=>''));
        if($lastUpdateTime!==null){
            $criteria['time']=array('$gt'=>$lastUpdateTime);
        }
        if($userId!==null){
            $criteria['user']=$userId;
        }

        return (new VisitedPagesCollection())->findBy($criteria);
    }

    public function getAllVisitedRefId(\MongoId $userId, $refId){
      return (new VisitedPagesCollection())->findBy(array('user'=>$userId, 'refId'=>$refId));
    }

    public function isRefresh($userId, $tradeId, $curTime){
        if(empty($tradeId)){
            return false;
        }

        $rankConf = (new Files(CORE_CONFIG))->get('Ranking');
        return ((new VisitedPagesCollection())->getOneBy(array(
            'user'=>new \MongoId($userId), 'tradeId'=>(String)$tradeId,
            'time'=>array('$gt'=>$rankConf['refresh'], '$lt'=>$rankConf['refresh_end'])))!==null);
    }

    public function isRetention($userId, $tradeId, $curTime){
        if(empty($tradeId)){
            return false;
        }

        $rankConf = (new Files(CORE_CONFIG))->get('Ranking');
        return ((new VisitedPagesCollection())->getOneBy(array(
            'user'=>new \MongoId($userId), 'tradeId'=>(String)$tradeId,
            'time'=>array('$gt'=>$rankConf['retention'], '$lt'=>$rankConf['retention_end']))) !==null);
    }

    public function testRefreshRetention($userId, $tradeId, $refId, $time){
        $userPref = UserPreferenciesModel::getUserRating($userId, $tradeId);
        $ranks = array();
        if(!isset($userPref['refresh']) || !$userPref['refresh']){
            $ranks['refresh']=$this->isRefresh($userId, $tradeId, $time);
        }
        if(!isset($userPref['retention']) || !$userPref['retention']){
            $ranks['retention']=$this->isRetention($userId, $tradeId, $time);
        }
        UserPreferenciesModel::refreshUserRating($userId, $tradeId, $refId, $ranks);
    }

    public function getInterest($userId, $page){
        return (new CollectionAdapter('userAction_interest'))->getOneBy(array('user'=>new \MongoId($userId), 'url'=>$page));
    }
}

