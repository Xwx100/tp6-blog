<?php
class A {
    public function handleMenu(array $menus, $levels = 1) {
        $tmp = [];
        foreach ($menus as $k => $menu) {
            if ($menu['pid'] == $levels) {
                $menu['children'] = $this->handleMenu($menus, $levels + 1);
                $tmp[] = $menu;
            }
        }

        return $tmp;
    }
}

$test = array(
    1 => array('id' => 1, 'pid' => 0, 'name' => '安徽省'),
    2 => array('id' => 2, 'pid' => 0, 'name' => '浙江省'),
    3 => array('id' => 3, 'pid' => 1, 'name' => '合肥市'),
    4 => array('id' => 4, 'pid' => 2, 'name' => '长丰县'),
    6 => array('id' => 5, 'pid' => 3, 'name' => '小溪村'),
    5 => array('id' => 6, 'pid' => 4, 'name' => '安庆市'),
);

$c = (new A)->handleMenu($test, 0);
print_r($c);

