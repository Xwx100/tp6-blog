<?php
/**
 * 支持 应用级别的 容器注入 array_merge
 */
return [
    xu_add_name_pre('provider2') => \app\test\provider\Provider1::class,
    xu_add_name_pre('provider2') => \app\test\provider\Provider2::class
];