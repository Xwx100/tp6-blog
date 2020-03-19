<?php


namespace app\admin\model;


use think\db\Query;
use think\Model;

class Base extends Model {

    protected $addReFormat = true;

    public function edit(array $params) {
        $ok = xu_get_service('mysql_utils')->judgeAddOrUp($params, [$this, 'add'], [$this, 'up'], $this->getPk());
        return $this->wrapData(
            [self::getPk() => $params[self::getPk()] ?? $ok],
            $ok ? '成功' : '失败',
            $ok ? 0 : 1
        );
    }

    /**
     * 是否 装饰 xu_add_pre_format
     *
     * @param $data
     * @param $msg
     * @param $code
     *
     * @return array
     */
    public function wrapData($data, $msg, $code) {
        if ($this->addReFormat) {
            return xu_add_re_format((array)$data, $msg, $code);
        }
        return $data;
    }

    /**
     * 增加 数据
     *
     * @param array $params
     *
     * @return int|string
     */
    public function add(array $params) {
        return self::insertGetId($params);
    }

    /**
     * 修改数据
     *
     * @param array $params
     *
     * @return bool
     */
    public function up(array $params) {
        try {
            xu_get_service('mysql_utils')->beginFunc($this->db(), $params, function (Query $m, $params) {
                $originData = self::where([$m->getPk() => $params[$m->getPk()]])
                    ->lock(true)
                    ->allowEmpty(true)
                    ->find();
                if ($originData->isEmpty()) {
                    $msg = xu_str_f('[ id=%s ]: 找不到数据=%s', $params[$m->getPk()], $originData);
                    throw new \Exception($msg);
                } else {
                    return $originData->save($params);
                }
            });
        } catch (\Exception $e) {
            app()->log->error(xu_str_f('[ admin.base.up ]: %s',  $e->getMessage()));
        }
        return false;
    }
}