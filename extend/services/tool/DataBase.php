<?php
/**
 * Created by PhpStorm.
 * User: Xu
 * Date: 2020/3/4
 * Time: 11:42
 */

namespace tools;


trait DataBase {


    /**
     * 处理 增 改 通用参数 例如 adduser、edituser
     *
     * @param array $params
     *
     * @param       $key
     *
     * @return mixed
     */
    public static function addCommon(array $params, $key = 'id') {
        return self::judgeAddOrUp($params, function ($params) {
            $params['adduser'] = Util::currUser();
            $params['edituser'] = Util::currUser();
            return $params;
        }, function ($params) {
            $params['edituser'] = Util::currUser();
            // no update
            foreach (['adduser', 'addtime'] as $k) {
                unset($params[$k]);
            }
            return $params;
        }, $key);
    }

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
     * @param Model         $m
     * @param callable|null $func
     *
     * @return mixed
     * @throws \think\exception\PDOException
     */
    public static function beginFunc(Model $m, callable $func) {
        try {
            $m->startTrans();
            $data = call_user_func($func, $m);
            $m->commit();
            return $data;
        } catch (\Exception $e) {
            $m->rollback();
            lof($e->getMessage() . $e->getTraceAsString());
            throw new \Exception($e->getMessage());
        }
    }
}
