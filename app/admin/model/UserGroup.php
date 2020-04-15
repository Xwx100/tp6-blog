<?php


namespace app\admin\model;

/**
 * 用户-组
 * Class UserGroup
 *
 * @package app\admin\model
 *
 */
class UserGroup extends \XuModel {

    public static $xuProp = array(
        'user_id'  => array(
            'before_field' => 'user_id',
        ),
        'group_id' => array(
            'before_field' => 'group_id',
        ),
    );
}