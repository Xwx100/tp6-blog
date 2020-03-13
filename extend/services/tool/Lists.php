<?php
/**
 * Created by PhpStorm.
 * User: Xu
 * Date: 2020/3/4
 * Time: 11:42
 */

namespace tools;


trait Lists {

    /**
     * 固定列表返回
     *
     * @param array $info
     *
     * @return array
     */
    public static function listR(array $info) {
        return [
            'list'      => (array)$info['list'],
            'total'     => (array)$info['total'],
            'page_info' => (array)$info['page_info'],
        ];
    }


    /**
     * 获取要转成json的key
     *
     * @param array  $fieldAttr
     * @param string $k
     *
     * @return array
     */
    public static function getJsonKeys(array $fieldAttr, string $k): array {
        $jsonKeys = array_filter($fieldAttr, function ($v) use ($k) {
            if ($v['data_type'] === $k) {
                return true;
            }
            return false;
        });

        return array_keys($jsonKeys);
    }

    /**
     * array => json
     *
     * @param array  $params
     * @param array  $jsonKeys
     * @param string $type
     */
    public static function changeType(array &$params, array $jsonKeys, string $type = 'array2Json') {
        foreach ($jsonKeys as $key) {
            if (!array_key_exists($key, $params)) {
                continue;
            }
            if ($type === 'array2Json' && !is_string($params[$key])) {
                $params[$key] = json_encode((array)$params[$key], JSON_UNESCAPED_UNICODE);
            } else if ($type === 'json2Array' && !is_array($params[$key])) {
                $params[$key] = (array)json_decode((string)$params[$key], true);
            }
        }
    }


    /**
     * 格式化 数据类型
     *
     * @param  array $v
     * @param string $funcName
     *
     * @return array
     */
    public static function formatType(array $v, $funcName = 'intval') {
        return array_map(function ($vv) use ($funcName) {
            return call_user_func($funcName, $vv);
        }, $v);
    }

}
