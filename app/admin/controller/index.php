<?php


namespace app\admin\controller;


use app\admin\model\UserRole;
use app\BaseController;
use app\admin\model\Role;

class index extends BaseController {

    public function index() {

    }

    public function role() {
        return json((new UserRole())->edit(input('post.')));
    }
}
