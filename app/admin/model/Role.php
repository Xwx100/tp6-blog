<?php


namespace app\admin\model;

/**
 * 角色
 * Class Role
 *
 * @package app\admin\model
 *
 */
class Role extends \XuModel {

    public static $xuProp = array(
        'role_id'    => array(
            'before_field' => 'role_id',
        ),
        'role_en'    => array(
            'before_field' => 'role_en',
            'comment'      => '角色英文名',
        ),
        'role_zh'    => array(
            'before_field' => 'role_zh',
            'comment'      => '角色中文名',
        ),
        'update_at'  => array(
            'before_field' => 'update_at',
        ),
        'is_deleted' => array(
            'before_field' => 'is_deleted',
        ),
    );
}