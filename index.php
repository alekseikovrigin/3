<?php


interface MyInterface{}
class Foo implements MyInterface{}
class Bar implements MyInterface{}
class Baz
{
    public function __construct(MyInterface $foo)
    {
        $this->foo = $foo;
    }
}

$container = new Container();
$container->set('Baz', 'Baz');
$container->set('MyInterface', 'Foo');
$baz = $container->get('Baz');
print_r($baz);
$container->set('MyInterface', 'Bar');
$baz = $container->get('Baz');
print_r($baz);