<?php


class XuModel extends \think\Model {

    public static $xuProp = null;
    public $xuInput = null;
    /**
     * @var array join['key' => { 'model_pos'=> '', 'alias' => '', 'on' => ''}]
     */
    public $xuJoin = null;
    /**
     * 用于 放置 处理后的数据 例如 处理后的 xuProp | xuJoin
     * @var array
     */
    public $xuAfterHandle = [
        'xuProp' => [],
        'xuJoin' => [],
    ];

    public function xuLists(array $params) {
        self::xuListsBefore($params);

        $fromQuery = $this->alias('a');
        if (empty($this->xuJoin) && $params['join']) {
            throw new \Exception(sprintf('[ %s.xuJoin ] 未配置', get_called_class()));
        }
        $fromQuery = $this->xuHandleJoin($fromQuery, $params);
        $prop = $this->xuAfterHandle['xuProp'];
        $prop = $this->xuHandleField($prop);
        $where = $this->xuHandleWhere($params, $prop);
        $group = $this->xuHandleGroup($params, $prop);
        $order = $this->xuHandleOrder($params, $prop);
        $fromQuery = $fromQuery
            ->where($where)
            ->group($group)->select();


        self::xuListsAfter($params, $lists);

        return $lists;
    }

    public function xuListsBefore(array &$params) {
    }

    public function xuListsAfter(array $params, array &$lists) {
    }

    /**
     * @param \think\db\Query $q
     * @param array           $params
     *
     * @return \think\db\Query
     * @throws Exception
     */
    public function xuHandleJoin(\think\db\Query $q, array $params): \think\db\Query {
        $joins = array_filter($this->xuJoin, function ($k) use ($params) {
            if (in_array($k, $params['join'])) {
                return true;
            }
            return false;
        }, ARRAY_FILTER_USE_KEY);
        $props = self::$xuProp;

        if (count($joins) > 4) {
            throw new \Exception('[ XuModel.xuHandleJoin ] join 不建议超出 4 个表');
        }

        $b = 98;
        foreach ($joins as $k => $join) {
            $modelPos = $join['model_pos'];
            $table = $q->getConnection()->getConfig('prefix')  . '_' . substr(strstr($modelPos, '\\'), 1);

            $q->join($table . ' ' . ($join['alias'] ?: chr($b)), $join['on'], $join['type']);

            $props = array_merge($props, $modelPos . '::$xuProp');
            ++$b;
        }

        $this->xuAfterHandle['xuJoin'] = $joins;
        $this->xuAfterHandle['xuProp'] = $props;

        return $q;
    }

    /**
     * 增加属性
     *
     * @param array $fieldAttr
     * @param array $prop ['alias' => 'a']
     *
     * @return array
     */
    public function xuAddProp(array $fieldAttr, array $prop) {
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
    public function xuHandleField(array $fieldAttr) {
        foreach ($fieldAttr as $k => &$prop) {
            // 用户 自定义
            $alias = '';
            if ($prop['alias']) {
                $alias = "{$prop['alias']}.";
            }
            // 优先读取 格式化字段
            if ($prop['field_format']) {
                $prop['after_field'] = sprintf("{$prop['field_format']} as {$k}", $alias . $this->xuBeforeField($prop));
                continue;
            }
            // front_field !== before_field 则触发重命名 as
            if ($this->xuBeforeField($prop) && $this->xuBeforeField($prop) !== $k) {
                $prop['after_field'] = sprintf('%s%s as %s', $alias, $prop['before_field'], $k);
                continue;
            }
            $prop['after_field'] = "{$alias}{$k}";
        }

        return $fieldAttr;
    }

    /**
     * 处理条件
     *
     * @param array $params ['ctl_no_empty' => 'true 则不使用empty函数进行过滤前端参数']
     * @param array $fieldAttr
     *
     * @return array
     */
    public function xuHandleWhere(array $params, array $fieldAttr): array {
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
            $afterField = $this->xuAliasBeforeField($fieldAttr, $k);
            $this->xuHandleProp($frontValue, $afterField, $prop);
        }, ARRAY_FILTER_USE_BOTH);

        return $fieldAttr;
    }

    /**
     * 处理属性（multi | like | range）
     *
     * @param        $frontValue
     * @param        $afterField
     * @param array  $prop
     */
    public function xuHandleProp($frontValue, $afterField, array &$prop) {
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
                    if ($prop['where_key_format']) {
                        $_ = sprintf($prop['where_key_format'], $afterField) . " between '{$frontValue[1]}' and '{$frontValue[2]}'";
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
                    if ($prop['where_key_format']) {
                        $_ = sprintf($prop['where_key_format'], $afterField) . " = '{$frontValue[1]}'";
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
     * @param array $params
     * @param array $fieldAttr
     *
     * @return string
     */
    public function xuHandleGroup(array $params, array $fieldAttr): string {
        return implode(',', $params['group_by']);
    }

    /**
     * 处理 排序 字段
     *
     * @param array $params
     * @param array $fieldAttr
     *
     * @return array
     */
    public function xuHandleOrder(array $params, array $fieldAttr): array {
        $order = [];
        foreach ((array)$params['order_by'] as $item) {
            $p = $fieldAttr[$item['sort_field']];
            $v = ($p ? $p['after_field'] : $item['sort_field']) . " {$item['sort_type']}";
            $v && ($order[] = $v);
        }
        return $order;
    }


    /**
     * 获取 prop.after_field (经过前端所传 field 过滤)
     *
     * @param array $params
     * @param array $fieldAttr
     *
     * @return array
     */
    public function xuAfterFields(array $params, array $fieldAttr): array {
        $all = $fieldAttr;
        if (isset($params['field'])) {
            $all = array_intersect_key($all, array_combine($params['field'], $params['field']));
        }
        return $this->xuGetColumn($all, 'after_field');
    }

    /**
     * 获取 prop.after_where
     *
     * @param $fieldAttr
     *
     * @return array
     */
    public function xuAfterWheres(array $fieldAttr) {
        return $this->xuGetColumn($fieldAttr, 'after_where');
    }


    /**
     * 获取 属于where_key_format条件格式化 keys
     *
     * @param array $fieldAttr
     *
     * @return array
     */
    public function xuWhereKeyFormat(array $fieldAttr): array {
        return $this->xuGetColumn($fieldAttr, 'where_key_format');
    }

    /**
     * 获取 属于timed_null的 keys
     *
     * @param array $fieldAttr
     *
     * @return array
     */
    public function getTimedNullKeys(array $fieldAttr): array {
        return $this->xuGetColumn($fieldAttr, 'timed_null');
    }

    public function xuGetColumn(array $fieldAttr, $name) {
        return array_column($fieldAttr, $name);
    }

    /**
     * 获取带有alias处理前的原始字段   1.fieldAttr.key.before_field   2.fieldAttr.key
     *
     * @param array  $fieldAttr
     * @param string $key
     *
     * @return mixed|string
     */
    public function xuAliasBeforeField(array $fieldAttr, string $key) {
        $prop = $fieldAttr[$key];
        $field = $this->xuBeforeField($prop);
        if ($prop['alias']) {
            $field = "{$prop['alias']}.{$field}";
        }

        return $field;
    }

    /**
     * @param array $prop
     *
     * @return string
     */
    public function xuBeforeField(array $prop): string {
        return $prop['before_field'];
    }


    public function test() {
        // 属性
        self::$xuProp = [
            'user_id' => [
                'before_field'     => '处理前字段 例如 user_id',
                'after_field'      => '处理后字段 例如 a.user_id',
                'alias'            => '重命名字段 例如 a.',
                'field_format'     => '自定义字段 例如 group_concat(%s=a.user_id) as user_ids',
                'multi'            => 'in条件',
                'range'            => 'between|>|<',
                'like'             => 'like',
                'where_key_format' => '自定义条件key 例如 DATE_FORMAT(:key, \'%Y-%m-%d\')',
                'after_where'      => 'tp6 数组语句',
            ],
        ];
        // 前端参数
        $this->xuInput = [
            'field'     => 'array 字段',
            'from'      => 'string 来自于哪个表',
            'join'      => 'array ',
            'where'     => 'array ',
            'group_by'  => 'array ',
            'order_by'  => [
                [
                    'sort_field' => '',
                    'sort_type'  => ''
                ]
            ],
            'page_info' => [
                'page'      => 'int 当前页',
                'page_size' => 'int 当前页有几条数据'
            ]
        ];
    }
}