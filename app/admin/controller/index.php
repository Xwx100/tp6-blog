<?php


namespace app\admin\controller;


use app\admin\model\MenuRole;
use xu_model\Make;
use app\admin\model\UserGroup;
use app\admin\model\UserRole;
use app\BaseController;
use app\admin\model\Role;

class index extends BaseController {

    public function index() {
        $m = (new Make());
        $m->getDataBaseProp('xu_admin');
    }

    public function login() {
        $p = $this->app->request->param();
        setcookie('tp8', $p['session_id'], 0, '/');
        exit();
        return json([]);
    }

    public function session() {
        var_dump($this->app->request->cookie());
    }

    public function role() {
        (new Role())->alias('a')->select();
        exit();
//        return json((new MenuRole()) ->edit(input('post.')));
    }
}
