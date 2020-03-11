<?php
// +----------------------------------------------------------------------
// | 会话设置
// +----------------------------------------------------------------------

return [
    // session name
    'name'           => SESSION_NAME,
    // SESSION_ID的提交变量,解决flash上传跨域
    'var_session_id' => '',
    // 驱动方式 支持file cache
    'type'           => SESSION_STORE,
    // 存储连接标识 当type使用cache的时候有效
    'store'          => SESSION_STORE_TYPE,
    // 过期时间
    'expire'         => 1440,
    // 前缀
    'prefix'         => '',
];
