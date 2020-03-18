<?php
// 全局中间件定义文件
// handle && end 的 执行顺序 = FIFO
use app\test\middle\Middle1;

/**
 * 支持 应用级别的 中间件注入
 */
return [
    Middle1::class,
];
