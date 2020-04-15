<?php


namespace app\admin\model;

/**
 * 组-角色
 * Class GroupRole
 *
 * @package app\admin\model
 *
 */
class GroupRole extends \XuModel {

    public static $xuProp = array(
        'group_id' => array(
            'before_field' => 'group_id',
        ),
        'role_id'  => array(
            'before_field' => 'role_id',
        ),
    );
}