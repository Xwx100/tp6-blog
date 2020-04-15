<?php


namespace app\admin\model;

/**
 * 角色-菜单
 * Class RoleMenu
 *
 * @package app\admin\model
 *
 */
class RoleMenu extends \XuModel {

    public static $xuProp = array(
        'role_id' => array(
            'before_field' => 'role_id',
        ),
        'menu_id' => array(
            'before_field' => 'menu_id',
        ),
    );
}