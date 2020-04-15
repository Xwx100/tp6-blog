<?php


namespace app\admin\model;

/**
 * 用户
 * Class User
 *
 * @package app\admin\model
 *
 */
class User extends \XuModel {

    public static $xuProp = array(
        'user_id'    => array(
            'before_field' => 'user_id',
        ),
        'user_en'    => array(
            'before_field' => 'user_en',
        ),
        'user_zh'    => array(
            'before_field' => 'user_zh',
        ),
        'password'   => array(
            'before_field' => 'password',
        ),
        'email'      => array(
            'before_field' => 'email',
        ),
        'phone'      => array(
            'before_field' => 'phone',
        ),
        'create_at'  => array(
            'before_field' => 'create_at',
        ),
        'update_at'  => array(
            'before_field' => 'update_at',
        ),
        'is_deleted' => array(
            'before_field' => 'is_deleted',
        ),
    );
}