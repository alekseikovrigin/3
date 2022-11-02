<?php
include_once ('vendor/autoload.php');
use alekseikovrigin\DependencyInjectionContainer;

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

$container = new \DependencyInjectionContainer();
$container->set('Baz', 'Baz');
$container->set('MyInterface', 'Foo');
$baz = $container->get('Baz');
print_r($baz);
$container->set('MyInterface', 'Bar');
$baz = $container->get('Baz');
print_r($baz);