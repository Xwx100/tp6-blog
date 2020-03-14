<?php
/**
 * Created by PhpStorm.
 * User: Xu
 * Date: 2020/3/4
 * Time: 15:34
 */

namespace services\tool;


trait Out {

    /**
     * @param       $format
     * @param array ...$args
     *
     * @return string
     */
    public static function strF($format, ...$args) {
        foreach ($args as &$arg) {
            if (is_array($arg)) {
                $arg = json_encode($arg, JSON_UNESCAPED_UNICODE);
            } elseif (is_object($arg)) {
                if (method_exists($arg, '__toString')) {
                    $arg = $arg->__toString();
                } else {
                    $arg = json_encode($arg, JSON_UNESCAPED_UNICODE);
                }
            }
        }
        unset($arg);
        return sprintf($format, ...$args);
    }
}
