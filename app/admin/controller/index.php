<?php


namespace app\admin\controller;


use app\BaseController;
use think\facade\Db;
use app\admin\model\User;

class index extends BaseController {

    public function login() {
        $data = Db::query('desc xu_user');
        (new User)->login([]);

        return json($data);
    }
}
