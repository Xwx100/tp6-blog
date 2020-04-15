<?php
return [
    'isOpen' => true || PHP_SAPI === 'cli',
    /**
     * 拦截的 url can to do what
     */
    'interceptUrl' => [
        'admin/index/login' => '',
    ],
];