<?php
/**
 * Created by PhpStorm.
 * User: Xu
 * Date: 2020/3/4
 * Time: 11:41
 */

namespace services\tool;


use think\facade\App;
use think\Model;

trait FieldAttr {

    protected static $info = [
        'field_attr_path' => EXTEND_FIELD_ATTR_PATH
    ];


    /**
     * 生成 表 fieldAttr 基础属性
     *
     * @param Model $m
     *
     * @return bool
     */
    public static function descToAttr(Model $m): bool {
        $data = $m->query("desc {$m->getTable()}");
        $data = array_map(function ($row) {
            $row['before_field'] = $row['Field'];
            foreach (['Field', 'Key', 'Default', 'Extra'] as $k) {
                unset($row[$k]);
            }
            return array_change_key_case($row, CASE_LOWER);
        }, $data);
        $fieldAttr = array_combine(array_column($data, 'before_field'), array_values($data));
        $path = self::$info['field_attr_path'] ?? implode('/', [App::getRootPath(), 'extend', 'table_field_attr']);
        if (false === is_dir($path)) {
            mkdir($path);
        }
        return file_put_contents($path . DIRECTORY_SEPARATOR . $m->getTable() . '.txt', var_export($fieldAttr, true));
    }


}
