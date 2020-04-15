<?php


namespace app\admin\model;

/**
 * 菜单
 * Class Menu
 *
 * @package app\admin\model
 *
 */
class Menu extends \XuModel {

    public static $xuProp = array(
        'menu_id'    => array(
            'before_field' => 'menu_id',
        ),
        'menu_path'  => array(
            'before_field' => 'menu_path',
        ),
        'menu_level' => array(
            'before_field' => 'menu_level',
            'comment'      => '菜单等级',
        ),
        'menu_pid'   => array(
            'before_field' => 'menu_pid',
        ),
        'is_deleted' => array(
            'before_field' => 'is_deleted',
        ),
    );
}