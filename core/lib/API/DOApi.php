<?php

namespace Zotto\API;

use Zotto\Task\TaskLog;

define('DOAPI_HOST', 'https://api.digitalocean.com/v1/');

class DOApi implements ApiInterface {
    const DOAPI_CID = 'HKGeYDpV7lDMPzkeCyAce';
    const DOAPI_KEY = '315cd9793fdd9ada76e379ffda7fba45';
    const DOAPI_KEYS = '98280,55405';

    public function request($act, $params=[])
    {
        $url = DOAPI_HOST.$act.'?'
            .'client_id='.self::DOAPI_CID.'&api_key='.self::DOAPI_KEY.'&'
            .http_build_query($params);
        return json_decode(file_get_contents($url), true);
    }

    public function getAvailable(){
        return $this->request('droplets/');
    }

    public function remove($id){
        return $this->request('droplets/'.$id.'/destroy/');
    }

    public function show($id){
        return $this->request('droplets/'.$id);
    }

    public function sshKeys(){
        return $this->request('ssh_keys/');
    }

    public function create($name, $sizeId, $imageId, $region=2){

        return $this->request('droplets/new', [
            'name'=>$name,
            'size_id'=>$sizeId,
            'image_id'=>$imageId,
             'region_id'=>$region,
            'ssh_key_ids'=>self::DOAPI_KEYS
        ]);
    }


}