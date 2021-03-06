<?php

namespace Zotto\Actions\Site;

use PhpBase\Mvc\Request;
use PhpBase\Mvc\Response;
use Zotto\Actions\BaseAction;
use Zotto\Actions\Factory;
use Zotto\Model\ACLUserModel;
use Zotto\Request\Api;
use Zotto\Task\TaskController;


class User extends BaseAction
{
    protected $_templateDir = 'Site';

    protected $_mapper = ['action', 'acl'];
    protected $allowRegister = ['rekl', 'wm'];

    /**
     * Выполняет действие
     *
     * @param Request $request Объект запроса
     * @return Response
     */
    public function run(Api $request)
    {
       return $this->_renderToResponse('User',[]);
    }


    public function onLogout($request){
        $um = new ACLUserModel();
        $um->logOut();
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    public function onLogin($request){
        $um = new ACLUserModel();
        if($um->isAuth(false)) {
            header("Location: /");
        }
    }

    public function onCheckLogin($request){

        $errors = array();

        $nick = trim(htmlspecialchars($request->post->get('nick', '')));
        $pass = trim($request->post->get('pass', ''));

        if(empty($pass)) {
            $errors['pass'] = 'Пароль не может быть пустым';
        }
        if(empty($errors)) {
            $um = new ACLUserModel();
            $user = $um->getOneBy('nick', $nick);
            if(!empty($user)) {
                if($user['pass'] == $um->generateHash($nick, $pass)) {
                    $um->auth($user['nick'], $user['pass']);
                } else {
                    $errors['pass'][] = 'Неверный пароль';
                }
            } else {
                $errors['nick'][] = 'Такого пользователя не существует';
            }
        }

        return $this->_renderJsonData(['errors' => $errors, 'location'=>'/']);
    }

    public function onRegister($request){
        return $this->_renderToResponse('User.register',['acl'=>$request->params->get('acl', 'wm')]);
    }


    public function onCheckRegister($request){
        $errors = array();
        $um = new ACLUserModel();

        $nick = trim(htmlspecialchars($request->post->get('nick', '')));

        if(strlen($nick) < 3)
            $errors['nick'] = 'Слишком короткий ник';


        if(strlen($nick) > 100)
            $errors['nick'] = 'Слишком длинный ник';


        $user = $um->getOneBy('nick', $nick);
        if(!empty($user)) {
            $errors['nick'] = 'Такой пользователь уже есть';
        }

        $pass = trim($request->post->get('pass', ''));
        $passConfirm = trim($request->post->get('pass_confirm', ''));

        if(empty($pass)) {
            $errors['pass'] = 'Пароль не может быть пустым';
        }
        if($pass != $passConfirm) {
            $errors['pass_confirm'] = 'Пароль и подтверждение не совпадают';
        }

        $location = '';
        if(empty($errors)) {
            // register user
            $acl = $request->post->get('acl', 'wm');
            if(in_array($acl, $this->allowRegister)){
                $location='/'.$acl;
                $acl = [Factory::generateCamelCaseName($acl)];
            } else {
                $acl = [];
            }
            $um->register($nick, $pass, $acl);
            $um->auth($nick, $pass);
        }

        return $this->_renderJsonData(["errors" => $errors, "location"=>$location]);
    }


}