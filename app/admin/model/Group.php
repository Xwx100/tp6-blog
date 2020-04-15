<?php


namespace app\admin\model;

/**
 * 组
 * Class Group
 *
 * @package app\admin\model
 *
 */
class Group extends \XuModel {

    public static $xuProp = array(
        'group_id'   => array(
            'before_field' => 'group_id',
        ),
        'group_en'   => array(
            'before_field' => 'group_en',
            'comment'      => '组名',
        ),
        'group_zh'   => array(
            'before_field' => 'group_zh',
            'comment'      => '组名',
        ),
        'update_at'  => array(
            'before_field' => 'update_at',
        ),
        'is_deleted' => array(
            'before_field' => 'is_deleted',
        ),
    );
}