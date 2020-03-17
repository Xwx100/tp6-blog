<?php
class A {

    public static $cc = [];

    public static function getCC() {
        return self::$cc;
    }
}

class B extends A {

    public static $cc = [];
}

class C extends A {

}

$a = new A;
$b = new B;
$c = new C;

array_push(A::$cc, 11);

var_dump(A::$cc);
$CC = 'A';
var_dump($CC::$cc);
