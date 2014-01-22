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
    /**
     * @var string
     */
    protected $dir;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var int
     */
    protected $psr = 0;

    public function __construct()
    {
        $className = get_class($this);
        $this->namespace = substr($className, 0, strpos($className, '\\'));

        $reflector = new \ReflectionClass($className);
        $classDir = dirname($reflector->getFileName());
        $baseClassDir = pathinfo($classDir, PATHINFO_BASENAME);

        if ($baseClassDir === $this->getNamespace()) {
            $this->dir = realpath(dirname(dirname($classDir))); // PSR-0 i.e. src/Namespace/Module.php
        } elseif ($baseClassDir === 'src') {
            $this->dir = realpath(dirname($classDir)); // PSR-4 i.e. src/Module.php
            $this->psr = 4;
        } else {
            throw new \RuntimeException("Could not detect module root directory. Please either use PSR-0 or PSR-4 structure.");
        }
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Module root directory
     *
     * @return string
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * {@inheritdoc}
     */
    public function getAutoloaderConfig()
    {
        $moduleNamespaceDir = $this->getDir() . '/src/';

        if ($this->psr == 0) {
            $moduleNamespaceDir .= $this->getNamespace();
        }

        return [
            'Zend\Loader\ClassMapAutoloader' => [
                $this->getDir() . '/autoload_classmap.php',
            ],
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    $this->getNamespace() => $moduleNamespaceDir,
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
