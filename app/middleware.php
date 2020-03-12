<?php
// 全局中间件定义文件
// handle && end 的 执行顺序 = FIFO
return [
    // 全局请求缓存
    // \think\middleware\CheckRequestCache::class,
    // 多语言加载
    // \think\middleware\LoadLangPack::class,
    // Session初始化
    middles\login\Log::class,
    middles\login\Login::class,
     \think\middleware\SessionInit::class,
];
