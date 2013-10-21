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

abstract class BaseModule implements
    Feature\AutoloaderProviderInterface,
    Feature\ConfigProviderInterface
{
    /**
     * Module root directory
     *
     * @return string
     */
    abstract public function getDir();

    /**
     * Module namespace
     *
     * @return string
     */
    abstract public function getNamespace();

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
                    $this->getNamespace() => $this->getDir() . '/src/' . $this->getNamespace(),
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

        $configFiles = Glob::glob($this->getDir() . '/config/*.config.php');

        foreach ($configFiles as $configFile) {
            $config = ArrayUtils::merge($config, include $configFile);
        }

        return $config;
    }
}
