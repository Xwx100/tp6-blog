<?php
/**
 * Created by PhpStorm.
 * User: Xu
 * Date: 2020/3/4
 * Time: 11:42
 */

namespace services\tool;

use think\Db;
use think\db\Query;
use think\Model;

trait DataBase {

    /**
     * 判断 增 或 改
     *
     * @param          $params
     * @param callable $add
     * @param callable $edit
     * @param string   $key
     *
     * @return mixed
     */
    public static function judgeAddOrUp($params, callable $add, callable $edit, $key = 'id') {
        if (!$params[$key]) {
            return call_user_func($add, $params);
        }
        return call_user_func($edit, $params);
    }

    /**
     * @param Query    $query
     * @param array    $params
     * @param callable $func
     *
     * @return mixed
     * @throws \Exception
     */
    public static function beginFunc(Query $query, array $params, callable $func) {
        try {
            $query->startTrans();
            $data = call_user_func($func, $query, $params);
            return $data;
        } catch (\Exception $e) {
            $query->rollback();
            throw new \Exception($e->getMessage());
        } finally {
            $query->commit();
        }
    }
}
