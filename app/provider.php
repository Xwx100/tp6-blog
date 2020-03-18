<?php
use app\ExceptionHandle;
use app\Request;

// 容器Provider定义文件 禁止使用 空间名类 当 key
return [
    'think\Request'          => Request::class,
    'think\exception\Handle' => ExceptionHandle::class,
];
