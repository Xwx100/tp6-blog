<?php
/**
 * Created by PhpStorm.
 * User: Xu
 * Date: 2020/3/4
 * Time: 11:40
 */

namespace services;


use services\tool\DataBase;
use services\tool\FieldAttr;
use services\tool\Lists;

/**
 * mysql 通用工具包
 *
 * Class MysqlUtils
 *
 * @mixin FieldAttr
 * @package services
 */
class MysqlUtils {
    use DataBase,FieldAttr,Lists;
}
