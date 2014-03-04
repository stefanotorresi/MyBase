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
     * @var string
     */
    protected $namespaceDir;

    /**
     * @var int
     */
    protected $psr = 0;

    /**
     * @var string
     */
    protected $configGlob = '*.config.php';

    /**
     *
     */
    public function __construct()
    {
        $className = get_class($this);
        $reflector = new \ReflectionClass($className);
        $fileName  = $reflector->getFileName();

        $this->namespaceDir = dirname($fileName);
        $this->namespace    = $reflector->getNamespaceName();
        $this->dir          = $this->detectModuleDir($fileName);
    }

    /**
     * {@inheritdoc}
     */
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\ClassMapAutoloader' => [
                $this->getDir() . '/autoload_classmap.php',
            ],
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    $this->getNamespace() => $this->namespaceDir,
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
        return $this->configGlob;
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
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param $fileName
     * @throws \RuntimeException
     * @internal param $classDir
     * @return string
     */
    protected function detectModuleDir($fileName)
    {
        $classDir = dirname($fileName);
        $baseModuleClassDir = pathinfo($classDir, PATHINFO_BASENAME);

        if (strrpos($this->getNamespace(), $baseModuleClassDir)
            === strlen($this->getNamespace()) - strlen($baseModuleClassDir)) {
            $dir = $classDir;
            $nestLevel = substr_count($this->getNamespace(), '\\');
            for ($i = 0; $i < $nestLevel; $i++) {
                $dir = dirname($dir);
            };

            return dirname(dirname($dir)); // PSR-0 i.e. src/Namespace/Module.php
        }

        if ($baseModuleClassDir === 'src') {
            $this->psr = 4;

            return dirname($classDir); // PSR-4 i.e. src/Module.php
        }

        throw new \RuntimeException("Could not detect module root directory. Please either use PSR-0 or PSR-4 structure.");
    }
}
