<?php
use app\test\event\Event1;
use app\test\event\Event2;
use app\test\event\Event3;
use app\test\event\Event4;

/**
 * 支持 应用级别的事件 定义
 */
return [
    'bind' => [
        'Test1' => 'test',
    ],

    'listen'    => [
        'AppInit'  => [],
        'HttpRun'  => [],
        'HttpEnd'  => [],
        'LogLevel' => [],
        'LogWrite' => [],
        'test' => [
            Event1::class,
            Event2::class,
            Event3::class,
        ]
    ],

    'subscribe' => [
        Event4::class
    ]
];