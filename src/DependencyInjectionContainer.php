<?php
use alekseikovrigin\DependencyInjectionContainer\Base;
use alekseikovrigin\DependencyInjectionContainer\Exceptions;

class DependencyInjectionContainer implements Base\Container
{
    protected $settings = array();

    public function set($abstract, $concrete = null)
    {
        if ($concrete === null) {
            $concrete = $abstract;
        }

        $this->settings[$abstract] = $concrete;
    }

    public function get($abstract, $parameters = array())
    {
        if (!isset($this->settings[$abstract])) {
            return null;
        }

        return $this->build($this->settings[$abstract], $parameters);
    }

    public function build($concrete, $parameters)
    {
        if ($concrete instanceof Closure) {
            return $concrete($this, $parameters);
        }

        $reflector = new ReflectionClass($concrete);

        if (!$reflector->isInstantiable()) {
            throw new Exception("Class {$concrete} is not instantiable");
        }

        $constructor = $reflector->getConstructor();

        if (is_null($constructor)) {
            return $reflector->newInstance();
        }

        $parameters = $constructor->getParameters();
        $dependencies = $this->getDependencies($parameters);

        return $reflector->newInstanceArgs($dependencies);
    }

    public function getDependencies($parameters)
    {
        $dependencies = array();
        foreach ($parameters as $parameter) {
            $dependency = $parameter->getClass();
            if ($dependency === null) {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new Exception("Can not be resolve class dependency {$parameter->name}");
                }
            } else {
                $dependencies[] = $this->get($dependency->name);
            }
        }

        return $dependencies;
    }
}