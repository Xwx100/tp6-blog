<?php


namespace app\admin\model;


class User extends Base {


    public static $fieldAttr = [

    ];

    /**
     * user join user_role join role join menu
     *
     * @param $params
     *
     * @return string
     */
    public function login($params): string {
        var_dump(get_service('mysql_utils')->descToAttr($this));
    }


}
