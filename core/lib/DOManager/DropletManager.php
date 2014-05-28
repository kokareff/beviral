<?php

namespace Zotto\DOManager;

use Zotto\API\DOApi;
use Zotto\Db\MainCollectionAdapter;
use Zotto\Model\Collection\DropletGroupsCollection;
use Zotto\Model\Collection\DropletsCollection;

class DropletManager {

    /**
     * @var \Zotto\Model\Collection\DropletsCollection
     */
    private $dropletCol;
    private $dropletGroupsCol;


    /**
     * @var \Zotto\API\DOApi
     */
    private $doApi;


    public function __construct(){
        $this->dropletCol = new DropletsCollection();
        $this->dropletGroupsCol = new DropletGroupsCollection();

        $this->doApi = new DOApi();
    }

    public function addDroplet($name, $size, $image, $dropletData){
        $retData = $this->doApi->create($name, $size, $image);
        if(isset($retData['droplet'])){
            $insertData = ['name'=>$name, 'status'=>'new', 'id'=>$retData['droplet']['id']];
            $this->dropletCol->insert($insertData+$dropletData);
            if(isset($dropletData['group'])&&!$this->getDropletGroup($dropletData['group'])){
                $this->addDropletGroup($dropletData['group']);
            }
        }

    }

    public function getAllDropletGroups(){
        return array_map(function($elem){
            return $elem['name'];
        }, $this->dropletGroupsCol->getAll());
    }

    public function addDropletGroup($groupName){
        if(!$this->getDropletGroup($groupName)){
            $this->dropletGroupsCol->insert(['name'=>$groupName]);
        }
    }

    public function getDropletGroup($name){
        return $this->dropletGroupsCol->getOneBy(['name'=>$name]);
    }

    public function removeDropletGroup($group){
        $this->dropletCol->remove(['group'=>$group]);
        $this->dropletGroupsCol->remove(['name'=>$group]);
    }

    public function getGroup($groupName){
        return $this->dropletCol->getBy(['group'=>$groupName]);
    }

    public function removeDroplet($id){
        $this->doApi->remove($id);
        $this->dropletCol->remove(['id'=>$id]);
    }

    public function refreshDropletActivity(){
        $newDroplets = $this->dropletCol->getBy(['status'=>'new']);
        foreach ($newDroplets as $dropletData) {
            $refreshData = $this->doApi->show($dropletData['id'])['droplet'];
            if($refreshData['status']!='new'){
                $this->dropletCol->set(['_id'=>$dropletData['_id']], ['status'=>$refreshData['status'], 'ip'=>$refreshData['ip_address']]);
            }
        }
        return count($newDroplets);
    }
} 