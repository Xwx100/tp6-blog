<?php
/**
 *
 * Created by PhpStorm.
 * User: Xu
 * Date: 2020/3/11
 * Time: 15:18
 */

namespace middles\interfaces;

use think\Request;

/**
 * 中间件接口类
 *
 * Interface Middle
 *
 * @package middles\interfaces
 */
interface Middle {

    /**
     * @param Request  $request
     * @param \Closure $next
     */
    public function handle(Request $request, \Closure $next);
}
