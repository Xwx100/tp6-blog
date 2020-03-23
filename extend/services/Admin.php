<?php


namespace services;


use http\Url;
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

    /**
     * 表属性
     * @var array
     */
    protected $fieldAttr = [];
    /**
     * 注入的 前端参数
     * @var null
     */
    protected $params = null;
    /**
     * 基础表
     * @var Db|null
     */
    protected $base = null;
    /**
     * 注册表属性
     * @var array
     */
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
        ],
        'user_login_log' => array (
            'log_id' =>
                array (
                    'type' => 'bigint(20) unsigned',
                    'null' => 'NO',
                    'before_field' => 'log_id',
                ),
            'user_id' =>
                array (
                    'type' => 'bigint(20) unsigned',
                    'null' => 'NO',
                    'before_field' => 'user_id',
                ),
            'user_name_en' =>
                array (
                    'type' => 'varchar(50)',
                    'null' => 'NO',
                    'before_field' => 'user_name_en',
                ),
            'req_id' =>
                array (
                    'type' => 'varchar(40)',
                    'null' => 'NO',
                    'before_field' => 'req_id',
                ),
            'req_params' =>
                array (
                    'type' => 'text',
                    'null' => 'NO',
                    'before_field' => 'req_params',
                ),
            'res_params' =>
                array (
                    'type' => 'text',
                    'null' => 'NO',
                    'before_field' => 'res_params',
                ),
            'remote_addr' =>
                array (
                    'type' => 'varchar(20)',
                    'null' => 'NO',
                    'before_field' => 'remote_addr',
                ),
            'ua' =>
                array (
                    'type' => 'varchar(100)',
                    'null' => 'NO',
                    'before_field' => 'ua',
                ),
            'update_at' =>
                array (
                    'type' => 'timestamp',
                    'null' => 'NO',
                    'before_field' => 'update_at',
                ),
        )
    ];
    /**
     * 增加 名字前缀 函数
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
     * @param string          $name
     * @param callable|null   $addNamePre 数据库 表 前缀
     * @param MysqlUtils|null $utils      数据库 通用 工具包
     */
    public function __construct(array $params, string $name = null, callable $addNamePre = null, MysqlUtils $utils = null) {
        $this->initFunc($addNamePre, $utils)
            ->initBase($name ?? 'user')
            ->initParams($params);
    }

    /**
     * 初始化 注入的函数
     *
     * @param callable|null   $addNamePre
     * @param MysqlUtils|null $utils
     *
     * @return Admin
     */
    public function initFunc(callable $addNamePre = null, MysqlUtils $utils = null) {
        // 设置 mysql 工具通用包
        if (!isset($utils)) {
            $this->utils = xu_get_service('mysql_utils');
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

        return $this;
    }

    /**
     * 初始化 参数
     *
     * @param array $params
     *
     * @return Admin
     */
    public function initParams(array $params) {
        // join | order_by | group_by => array
        $params = $this->utils->formatType($params, 'array', ['join', 'order_by', 'group_by']);

        // 设置参数
        $this->params = $params;

        // 设置 join 和 所有属性表
        $this->handleJoin();

        // 生成 处理后 after_field
        $this->fieldAttr = $this->utils->handleField($this->fieldAttr);
        $this->fieldAttr = $this->utils->handleWhere($this->params, $this->fieldAttr);

        return $this;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function initBase(string $name) {
        // 复用
        if (isset($this->base) && $this->base->getName() === $name) {
            return $this;
        }
        // prop
        $prop = $this->getRegister($name);
        // 设置基础模型表
        $this->base = Db::name($name)->alias($prop['alias']);
        // 设置基础属性
        $this->fieldAttr = $this->utils->addProp($prop['field_attr'], ['alias' => $prop['alias']]);

        return $this;
    }

    /**
     * 获取用户 所处的角色 所拥有的菜单
     *
     * @param array $params
     *
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getUserMenuRole(array $params) {
        $params = array_merge($params, [
            'field' => ['user_id', 'user_name_en','menu_ids', 'role_ids'],
            'join' => [
                ['no_pre_name' => 'user_role', 'type' => 'left'],
                ['no_pre_name' => 'role', 'type' => 'left'],
                ['no_pre_name' => 'menu_role', 'type' => 'left'],
                ['no_pre_name' => 'menu', 'type' => 'left'],
            ],
            'group_by' => ['user_id'],
            'order_by' => [
                ['sort_field' => 'user_id', 'sort_type' => 'desc']
            ],
        ]);
        $userRoleMenus = $this->initParams($params)->lists();

        return $userRoleMenus;
    }

    /**
     * 处理
     *
     * @param array $menus ['menu_name_en', 'path' => '', 'level', 'children' => []]
     * @param mixed $levels
     */
    public function handleMenu(array $menus, $levels = 1) {
        $tmp = [];
        foreach ($menus as $menu) {
            $curPid = $levels;
            if ($menu['level'] == $curPid) {
                $menu['children'] = $this->handleMenu($menus, ++ $levels);
                $tmp[] = $menu;
            }
        }

        return $tmp;
    }

    /**
     * 列表
     *
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function lists() {
        $field = $this->utils->getField($this->params, $this->fieldAttr);
        $where = $this->utils->getWhere($this->fieldAttr);
        $group = $this->utils->getGroup($this->params, $this->fieldAttr);

        $order = $this->utils->handleOrderBy($this->params, $this->fieldAttr);

        $data = $this->base->field(implode(',', $field))->where($where)->group(implode(',', $group))->order(implode(',', $order))->select();
        Log::sql($this->base->getLastSql());

        return $data;
    }

    /**
     * 增加 记录 日志
     *
     * @param array $params
     *
     * @return int|string
     */
    public function addLog(array $params) {
        if (empty($params['user_id'])) {
            $params = array_merge($params, app()->session->get(SESSION_USER_INFO));
        }
        if (empty($params['req_params'])) {
            $params['req_params'] = app()->request->param();
        }
        if (empty($params['res_params'])) {
            $params['res_params'] = response()->getData();
        }
        if (empty($params['remote_addr'])) {
            $params['remote_addr'] = app()->request->ip();
        }
        if (empty($params['ua'])) {
            $params['ua'] = app()->request->header('user_agent');
        }
        $this->utils->changeType($params, ['req_params', 'res_params'], 'array2Json');

        return $this->initBase('user_login_log')->base->strict(false)->json(['req_params', 'res_params'])->insertGetId($params);
    }

    /**
     * @param $name
     *
     * @return mixed
     * @throws \Exception
     */
    public function &getRegister($name) {
        $prop = &$this->registers[$name];
        if (!isset($prop)) {
            throw new \Exception(xu_str_f('[ admin.getRegister ] 为获取表=%s属性', $name));
        }
        return $prop;
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