<?php
/**
 * Created by PhpStorm.
 * User: Xu
 * Date: 2020/3/4
 * Time: 11:41
 */

namespace tools;


trait FieldAttr {

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
     * field => 手写（处理后字段） _field => 数据库原始名（处理前字段） fieldAttr.key => 重命名
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
            if ($prop['format']) {
                $tmp = $prop['_field'] ?? $k;
                $prop['field'] = str_replace([":key"], ["{$alias}{$tmp}"], $prop['format']) . " as {$k}";
                continue;
            }
            if ($prop['_field'] && $prop['_field'] !== $k) {
                $prop['field'] = "{$alias}{$prop['_field']} as {$k}";
                continue;
            }
            $prop['field'] = "{$alias}{$k}";
        }

        return $fieldAttr;
    }

    /**
     * 处理条件
     *
     * @param array $params
     * @param array $fieldAttr
     * @param array $where
     */
    public static function handleWhere(array $params, array $fieldAttr, array &$where) {
        if (!$params) {
            return;
        }
        $params = Util::trimArray($params);
        foreach ($params as $key => $value) {
            $continueCond = !$fieldAttr[$key];
            // _no_default 不忽略 空数组 []
            $_default = $params['_no_default'] ? (!$value && !is_array($value)) : !$value;
            $_default = $_default && !in_array($value, [0, '0'], true);
            $continueCond = $continueCond || $_default;
            if ($continueCond) continue;
            $prop = $fieldAttr[$key];
            $field = self::getOriginField($fieldAttr, $key);
            if (is_array($value)) {
                if ($fieldAttr[$key]['multi']) {
                    $value = array_unique($value);
                    $where[$field] = ['in', $value];
                } elseif ($fieldAttr[$key]['range']) {
                    if ($value[0] === 'between') {
                        if (!$value[1] || !$value[2]) {
                            continue;
                        }
                        if ($prop['where_format']) {
                            $_ = str_replace(':key', $field, $prop['where_format']) . " between '{$value[1]}' and '{$value[2]}'";
                            $where[$field] = $_;
                            continue;
                        }
                        $where[$field] = [$value[0], [$value[1], $value[2]]];
                    } else {
                        if (!$value[1]) {
                            continue;
                        }
                        if ($prop['where_format']) {
                            $_ = str_replace(':key', $field, $prop['where_format']) . " = '{$value[1]}'";
                            $where[$field] = $_;
                            continue;
                        }
                        $where[$field] = [$value[0], $value[1]];
                    }
                }
                continue;
            }
            if ($fieldAttr[$key]['moHu']) {
                $where[$field] = ['like', "%{$value}%"];
            } else {
                $where[$field] = ['=', $value];
            }
        }
    }


    /**
     * 原生_field 处理后的 field
     *
     * @param array $fieldAttr
     *
     * @return array
     */
    public static function getField(array $fieldAttr): array {
        return array_column($fieldAttr, 'field');
    }

    /**
     * 获取 属于where_format条件格式化 keys
     *
     * @param array  $fieldAttr
     *
     * @return array
     */
    public static function getWhereFormatKeys(array $fieldAttr): array {
        return self::propOwnKeys($fieldAttr, 'where_format', true);
    }

    /**
     * 获取 属于timed_null的 keys
     *
     * @param array  $fieldAttr
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
            $v = null;
            if ($handle) {
                $v = self::getOriginField($fieldAttr, $key);
            } else {
                $v = $key;
            }
            $k[] = $v;
        }

        return $k;
    }

    /**
     * 获取带有alias处理前的原始字段   1.fieldAttr.key._field   2.fieldAttr.key
     *
     * @param array  $fieldAttr
     * @param string $key
     *
     * @return mixed|string
     */
    public static function getOriginField(array $fieldAttr, string $key) {
        $prop = $fieldAttr[$key];
        $field = $prop['_field'] ?? $key;
        if ($prop['alias']) {
            $field = "{$prop['alias']}.{$field}";
        }

        return $field;
    }
}
