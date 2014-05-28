<?php

namespace Zotto\Model;

use Zotto\Cookies;
use Zotto\Db\MainCollectionAdapter;

class ACLUserModel extends MainCollectionAdapter {
    protected $collectionName = 'ACLUser';

    protected $currentUser;

    public function generateHash($id, $pass){
        return sha1($id.$pass);
    }

    public function isAuth(){
        if(Cookies::isSetted('login')){
            $logUser = $this->getCurrentUser();
            if($logUser['pass'] == Cookies::get('token')){
                return true;
            } else {
                $this->logOut();
            }
        }
        return false;
    }

    public function isAllowed($module){
       if($this->isAuth()){

           if(isset($this->currentUser['ACL'])){
                if(in_array($module, $this->currentUser['ACL'])){
                    return true;
                }
           }
       }
       return false;
    }

    public function getCurrentUser(){
        if($this->currentUser === null){
            $this->currentUser = $this->getOneBy("nick", Cookies::get('login'));
        }
        return $this->currentUser;
    }

    public function getCurrentUserId(){
        if($this->currentUser === null){
            $this->currentUser = $this->getOneBy("nick", Cookies::get('login'));
        }
        return $this->currentUser['_id'];
    }

    public function auth($login, $pass){

            $logUser = $this->getOneBy("nick", $login);
            $this->currentUser=$logUser;
            Cookies::set('id', $logUser['_id']);
            Cookies::set('login', $logUser['nick']);
            Cookies::set('token', $logUser['pass']);

        return $this->currentUser;
    }

    public function logOut(){
        $this->currentUser = null;
        Cookies::del('login');
        Cookies::del('token');
        Cookies::del('id');
    }

    public function register($nick, $pass, $acl=[]){
        $this->insert([
                'nick'=>$nick,
                'pass'=>$this->generateHash($nick, $pass),
                'ACL'=>$acl
            ]
        );
    }
} 