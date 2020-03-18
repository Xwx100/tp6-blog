<?php


namespace app\test\event;


use think\Event;

class Event4 {

    public function onUserLogin($user) {
        // UserLogin事件响应处理
        var_dump(__FUNCTION__);
    }

    public function onUserLogout($user) {
        // UserLogout事件响应处理
        var_dump(__FUNCTION__);
    }

    public function subscribe(Event $event) {
        $event->listen('UserLogin', [$this, 'onUserLogin']);
        $event->listen('UserLogout', [$this, 'onUserLogout']);
    }
}