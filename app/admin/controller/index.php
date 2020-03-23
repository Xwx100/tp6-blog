<?php


namespace app\admin\controller;


use app\admin\model\MenuRole;
use app\admin\model\UserRole;
use app\BaseController;
use app\admin\model\Role;

class index extends BaseController {

    public function index() {

    }

    public function login() {

    }

    public function role() {
        var_dump((new MenuRole())->db()->getConnection()->getTableInfo((new MenuRole())->getTable()));
        exit();
//        return json((new MenuRole()) ->edit(input('post.')));
    }
}
