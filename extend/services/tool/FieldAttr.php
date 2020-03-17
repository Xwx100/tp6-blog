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
        $fieldAttr = array_combine(array_column($data, 'field'), array_values($data));
        $path = self::$info['field_attr_path'] ?? implode('/', [App::getRootPath(), 'extend', 'table_field_attr']);
        if (false === is_dir($path)) {
            mkdir($path);
        }
        return file_put_contents($path . DIRECTORY_SEPARATOR . $m->getTable() . '.txt', var_export($fieldAttr, true));
    }

    /**
     * 增加属性
     *
     * @param array $fieldAttr
     * @param array $prop ['alias' => 'a']
     *
     * @return array
     */
    public static function addProp(array $fieldAttr, array $prop) {
        foreach ($fieldAttr as &$row) {
            $row = array_merge($row, $prop);
        }

        return $fieldAttr;
    }

    /**
     * after_field => 手写（处理后字段） before_field => 数据库原始名（处理前字段） fieldAttr.key => 重命名
     *
     * @param array $fieldAttr
     *
     * @return array
     */
    public static function handleField(array $fieldAttr) {
        foreach ($fieldAttr as $k => &$prop) {
            // 用户 自定义
            $alias = '';
            if ($prop['alias']) {
                $alias = "{$prop['alias']}.";
            }
            // 优先读取 格式化字段
            if ($prop['format']) {
                $tmp = $prop['before_field'] ?? $k;
                $prop['after_field'] = sprintf("{$prop['format']} as {$k}", "{$alias}{$tmp}");
                continue;
            }
            // front_field !== before_field 则触发重命名 as
            if ($prop['before_field'] && $prop['before_field'] !== $k) {
                $prop['after_field'] = "{$alias}{$prop['before_field']} as {$k}";
                continue;
            }
            $prop['after_field'] = "{$alias}{$k}";
        }

        return $fieldAttr;
    }

    /**
     * 处理条件
     *
     * @param array $params
     * @param array $fieldAttr
     *
     * @return array
     */
    public static function handleWhere(array $params, array $fieldAttr): array {
        if (empty($params)) {
            return $fieldAttr;
        }
        array_walk($params, function ($frontValue, $k) use (&$fieldAttr, $params, &$where) {
            // 没有配置 属性 不展示
            if (!isset($fieldAttr[$k])) {
                return;
            }
            // 过滤 空字符串
            if ($params['ctl_no_empty'] && empty($value)) {
                return;
            }
            // 获取 字段 属性
            $prop = &$fieldAttr[$k];
            $afterField = self::getOriginField($fieldAttr, $k);
            self::handleProp($frontValue, $afterField, $prop);
        }, ARRAY_FILTER_USE_BOTH);

        return $fieldAttr;
    }


    /**
     * 处理 排序 字段
     *
     * @param array $params
     * @param array $fieldAttr
     *
     * @return array
     */
    public static function handleOrderBy(array $params, array $fieldAttr): array {
        $order = [];
        foreach ((array)$params['order_by'] as $item) {
            $p = $fieldAttr[$item['sort_field']];
            $v = ($p ? $p['after_field'] : $item['sort_field']) . " {$item['sort_type']}";
            $v && ($order[] = $v);
        }
        return $order;
    }

    /**
     * 处理属性（multi | like | range）
     *
     * @param        $frontValue
     * @param        $afterField
     * @param array  $prop
     */
    public static function handleProp($frontValue, $afterField, array &$prop) {
        if (is_array($frontValue)) {
            if ($prop['multi']) {
                $frontValue = array_unique($frontValue);
                $prop['after_where'] = [$afterField, 'in', $frontValue];
//                $where[$afterField] = ['in', $frontValue];
            } elseif ($prop['range']) {
                if ($frontValue[0] === 'between') {
                    if (!$frontValue[1] || !$frontValue[2]) {
                        return;
                    }
                    if ($prop['where_format']) {
                        $_ = sprintf($prop['where_format'], $afterField) . " between '{$frontValue[1]}' and '{$frontValue[2]}'";
                        $prop['after_where'] = $_;
//                        $where[$afterField] = $_;
                        return;
                    }
                    $prop['after_where'] = [$afterField, $frontValue[0], [$frontValue[1], $frontValue[2]]];
//                    $where[$afterField] = [$frontValue[0], [$frontValue[1], $frontValue[2]]];
                } else {
                    if (!$frontValue[1]) {
                        return;
                    }
                    if ($prop['where_format']) {
                        $_ = sprintf($prop['where_format'], $afterField) . " = '{$frontValue[1]}'";
                        $prop['after_where'] = $_;
//                        $where[$afterField] = $_;
                        return;
                    }
                    $prop['after_where'] = [$afterField, $frontValue[0], $frontValue[1]];
//                    $where[$afterField] = [$frontValue[0], $frontValue[1]];
                }
            }
            return;
        }
        if ($prop['like']) {
            $prop['after_where'] = [$afterField, 'like', "%{$frontValue}%"];
//            $where[$afterField] = ['like', "%{$frontValue}%"];
        } else {
            $prop['after_where'] = [$afterField, '=', $frontValue];
//            $where[$afterField] = ['=', $frontValue];
        }
    }

    /**
     * 原生before_field 处理后的 after_field
     *
     * @param array $params
     * @param array $fieldAttr
     *
     * @return array
     */
    public static function getField(array $params, array $fieldAttr): array {
        $all = $fieldAttr;
        if (isset($params['field'])) {
            $all = array_intersect_key($all, array_combine($params['field'], $params['field']));
        }
        return self::getColumn($all, 'after_field');
    }

    /**
     * @param $fieldAttr
     *
     * @return array
     */
    public static function getWhere($fieldAttr) {
        return self::getColumn($fieldAttr, 'after_where');
    }

    /**
     * @param $params
     * @param $fieldAttr
     *
     * @return array
     */
    public static function getGroup($params, $fieldAttr) {
        if (!isset($params['group_by'])) {
            return [];
        }
        $params['group_by'] = array_map(function ($v) use ($fieldAttr) {
            return $fieldAttr[$v] ?: [];
        }, $params['group_by']);
        return self::getColumn($params['group_by'], 'after_field');
    }

    public static function getColumn(array $fieldAttr, $name) {
        return array_column($fieldAttr, $name);
    }

    /**
     * 获取 属于where_format条件格式化 keys
     *
     * @param array $fieldAttr
     *
     * @return array
     */
    public static function getWhereFormatKeys(array $fieldAttr): array {
        return self::propOwnKeys($fieldAttr, 'where_format', true);
    }

    /**
     * 获取 属于timed_null的 keys
     *
     * @param array $fieldAttr
     *
     * @return array
     */
    public static function getTimedNullKeys(array $fieldAttr): array {
        return self::propOwnKeys($fieldAttr, 'timed_null', false);
    }

    /**
     * 判断 prop 是否拥有某属性
     *
     * @param array  $fieldAttr
     * @param string $propKey
     * @param bool   $handle
     *
     * @return array
     */
    public static function propOwnKeys(array $fieldAttr, string $propKey, $handle = false) {
        $k = [];
        foreach ($fieldAttr as $key => $prop) {
            if (!isset($prop[$propKey])) {
                continue;
            }
            $k[] = $handle ? self::getOriginField($fieldAttr, $key) : $key;
        }

        return $k;
    }

    /**
     * 获取带有alias处理前的原始字段   1.fieldAttr.key.before_field   2.fieldAttr.key
     *
     * @param array  $fieldAttr
     * @param string $key
     *
     * @return mixed|string
     */
    public static function getOriginField(array $fieldAttr, string $key) {
        $prop = $fieldAttr[$key];
        $field = $prop['before_field'] ?? $key;
        if ($prop['alias']) {
            $field = "{$prop['alias']}.{$field}";
        }

        return $field;
    }
}
