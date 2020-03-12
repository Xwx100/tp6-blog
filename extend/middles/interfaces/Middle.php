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

interface Middle {

    public function handle(Request $request, \Closure $next);
}
