<?php
use app\ExceptionHandle;
use app\Request;
use app\app2\commons\TestProvider;

// 容器Provider定义文件
return [
    'think\Request'          => Request::class,
    'think\exception\Handle' => ExceptionHandle::class,
    'test_provider' => TestProvider::class,
];
