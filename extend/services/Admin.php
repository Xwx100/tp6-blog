<?php


namespace services;


use think\facade\Db;
use think\facade\Log;

/**
 * 用户权限管理 类
 * Class Admin
 *
 * @package services
 *
 */
class Admin {

    protected $fieldAttr = [];
    protected $params = null;
    /**
     * @var Db|null
     */
    protected $base = null;
    protected $registers = [
        'user'      => [
            'alias'      => 'a',
            'field_attr' => array(
                'user_id'      => array(
                    'before_field' => 'user_id',
                    'type'         => 'bigint(20) unsigned',
                    'null'         => 'NO',
                    'multi'        => true
                ),
                'user_name_en' => array(
                    'before_field' => 'user_name_en',
                    'type'         => 'varchar(50)',
                    'null'         => 'NO',
                    'multi'        => true
                ),
                'user_name_zh' => array(
                    'before_field' => 'user_name_zh',
                    'type'         => 'varchar(50)',
                    'null'         => 'NO',
                    'like'         => true
                ),
                'email'        => array(
                    'before_field' => 'email',
                    'type'         => 'varchar(255)',
                    'null'         => 'YES',
                ),
                'phone'        => array(
                    'before_field' => 'phone',
                    'type'         => 'varchar(255)',
                    'null'         => 'NO',
                ),
                'create_at'    => array(
                    'before_field' => 'create_at',
                    'type'         => 'timestamp',
                    'null'         => 'NO',
                ),
                'update_at'    => array(
                    'before_field' => 'update_at',
                    'type'         => 'timestamp',
                    'null'         => 'NO',
                ),
                'is_deleted'   => array(
                    'before_field' => 'is_deleted',
                    'type'         => 'tinyint(4)',
                    'null'         => 'NO',
                ),
            )
        ],
        'menu'      => [
            'alias'      => 'b',
            'on'         => 'b.menu_id = e.menu_id',
            'field_attr' => array(
                'menu_id'      => array(
                    'before_field' => 'menu_id',
                    'type'         => 'bigint(20) unsigned',
                    'null'         => 'NO',
                    'multi'        => true
                ),
                'menu_name_en' => array(
                    'before_field' => 'menu_name_en',
                    'type'         => 'varchar(50)',
                    'null'         => 'NO',
                    'multi'        => true,
                ),
                'menu_name_zh' => array(
                    'before_field' => 'menu_name_zh',
                    'type'         => 'varchar(50)',
                    'null'         => 'NO',
                    'like'         => true
                ),
                'path'         => array(
                    'before_field' => 'path',
                    'type'         => 'varchar(255)',
                    'null'         => 'NO',
                ),
                'level'        => array(
                    'before_field' => 'level',
                    'type'         => 'smallint(5) unsigned',
                    'null'         => 'NO',
                ),
                'menu_ids'     => array(
                    'before_field' => 'menu_id',
                    'format'       => 'group_concat(%s)'
                )
            )
        ],
        'role'      => [
            'alias'      => 'c',
            'on'         => 'c.role_id = d.role_id',
            'field_attr' => array(
                'role_id'      => array(
                    'before_field' => 'role_id',
                    'type'         => 'bigint(20) unsigned',
                    'null'         => 'NO',
                    'multi'        => true
                ),
                'role_name_en' => array(
                    'before_field' => 'role_name_en',
                    'type'         => 'varchar(50)',
                    'null'         => 'NO',
                    'multi'        => true,
                ),
                'role_name_zh' => array(
                    'before_field' => 'role_name_zh',
                    'type'         => 'varchar(50)',
                    'null'         => 'NO',
                    'like'         => true
                ),
                'role_ids'     => array(
                    'before_field' => 'role_id',
                    'format'       => 'group_concat(%s)'
                )
            )
        ],
        'user_role' => [
            'alias'      => 'd',
            'on'         => 'a.user_id = d.user_id',
            'field_attr' => array(
                'user_id'    => array(
                    'before_field' => 'user_id',
                    'type'         => 'bigint(20) unsigned',
                    'null'         => 'NO',
                ),
                'role_id'    => array(
                    'before_field' => 'role_id',
                    'type'         => 'bigint(20) unsigned',
                    'null'         => 'NO',
                ),
                'update_at'  => array(
                    'before_field' => 'update_at',
                    'type'         => 'timestamp',
                    'null'         => 'NO',
                ),
                'is_deleted' => array(
                    'before_field' => 'is_deleted',
                    'type'         => 'tinyint(4)',
                    'null'         => 'NO',
                ),
            )
        ],
        'menu_role' => [
            'alias'      => 'e',
            'on'         => 'e.role_id = c.role_id',
            'field_attr' => array(
                'menu_id'    => array(
                    'before_field' => 'menu_id',
                    'type'         => 'bigint(20) unsigned',
                    'null'         => 'NO',
                ),
                'role_id'    => array(
                    'before_field' => 'role_id',
                    'type'         => 'bigint(20) unsigned',
                    'null'         => 'NO',
                ),
                'update_at'  => array(
                    'before_field' => 'update_at',
                    'type'         => 'timestamp',
                    'null'         => 'NO',
                ),
                'is_deleted' => array(
                    'before_field' => 'is_deleted',
                    'type'         => 'tinyint(4)',
                    'null'         => 'NO',
                ),
            )
        ]
    ];
    /**
     * @var callable|null
     */
    protected $addNamePre = null;
    /**
     * @var null|MysqlUtils
     */
    protected $utils = null;

    /**
     * Admin constructor.
     *
     * @param array           $params     ['join' => [['no_pre_name' => '','type' => 'left']], 'order_by' => [['sort_field' => '', 'sort_type']], 'group_by' => []]]
     * @param callable|null   $addNamePre 数据库 表 前缀
     * @param MysqlUtils|null $utils      数据库 通用 工具包
     */
    public function __construct(array $params, callable $addNamePre = null, MysqlUtils $utils = null) {
        $this->initFunc($addNamePre, $utils);
        $this->initParams($params);
    }

    /**
     * 初始化 注入的函数
     *
     * @param callable|null   $addNamePre
     * @param MysqlUtils|null $utils
     */
    public function initFunc(callable $addNamePre = null, MysqlUtils $utils = null) {
        // 设置 mysql 工具通用包
        if (!isset($utils)) {
            $this->utils = get_service('mysql_utils');
        } else {
            $this->utils = $utils;
        }
        // 设置mysql前缀
        if (!isset($addNamePre)) {
            $this->addNamePre = function ($name) {
                return implode('_', [ALL_PRE_NAME ?? 'xu', $name]);
            };
        } else {
            $this->addNamePre = $addNamePre;
        }
    }

    /**
     * 初始化 参数
     *
     * @param array $params
     */
    public function initParams(array $params) {
        // join | order_by | group_by => array
        $params = $this->utils->formatType($params, 'array', ['join', 'order_by', 'group_by']);

        // 设置参数
        $this->params = $params;
        // 设置基础模型表
        $this->base = Db::name('user')->alias($this->registers['user']['alias']);
        // 设置基础属性
        $this->fieldAttr = $this->utils->addProp($this->registers['user']['field_attr'], ['alias' => $this->registers['user']['alias']]);


        // 设置 join 和 所有属性表
        $this->handleJoin();

        // 生成 处理后 after_field
        $this->fieldAttr = $this->utils->handleField($this->fieldAttr);
        $this->fieldAttr = $this->utils->handleWhere($this->params, $this->fieldAttr);
    }

    public function lists() {
        $field = $this->utils->getField($this->params, $this->fieldAttr);
        $where = $this->utils->getWhere($this->fieldAttr);
        $group = $this->utils->getGroup($this->params, $this->fieldAttr);

        $order = $this->utils->handleOrderBy($this->params, $this->fieldAttr);

        $data = $this->base->field(implode(',', $field))->where($where)->group(implode(',', $group))->order(implode(',', $order))->select();
        Log::sql($this->base->getLastSql());

        return $data;
    }

    public function addNamePre($name) {
        return call_user_func($this->addNamePre, $name);
    }

    public function handleJoin() {
        foreach ((array)$this->params['join'] as $item) {
            $register = $this->registers[$item['no_pre_name']];
            $this->base->join("{$this->addNamePre($item['no_pre_name'])} {$register['alias']}", $register['on'], $item['type']);
            // left join 设置唯一性
            $this->fieldAttr = (array)$this->fieldAttr + $this->utils->addProp((array)$register['field_attr'], ['alias' => $register['alias']]);
        }
    }

}