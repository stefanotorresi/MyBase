<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase;

use Zend\ModuleManager\Feature;
use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\Glob;

abstract class AbstractModule implements
    Feature\AutoloaderProviderInterface,
    Feature\ConfigProviderInterface
{
    protected $dir;

    /**
     * Module root directory
     *
     * @return string
     */
    public function getDir()
    {
        if (! $this->dir) {
            $reflector = new \ReflectionClass(get_class($this));
            $classDir = dirname($reflector->getFileName());
            $this->dir = realpath($classDir . '/../..'); // assume PSR-0 compliant structure, e.g. src/Namespace/Module.php
        }

        return $this->dir;
    }

    /**
     * {@inheritdoc}
     */
    public function getAutoloaderConfig()
    {
        $className = get_class($this);
        $namespace = substr($className, 0, strpos($className, '\\'));

        return [
            'Zend\Loader\ClassMapAutoloader' => [
                $this->getDir() . '/autoload_classmap.php',
            ],
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    $namespace => $this->getDir() . '/src/' . $namespace,
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $config = [];

        $configFiles = Glob::glob($this->getDir() . '/config/' . $this->getConfigGlob(), Glob::GLOB_BRACE);

        foreach ($configFiles as $configFile) {
            $config = ArrayUtils::merge($config, include $configFile);
        }

        return $config;
    }

    /**
     * @return string
     */
    public function getConfigGlob()
    {
        return '*.config.php';
    }
}
