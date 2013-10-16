<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase;

use Zend\ModuleManager\Feature;

class Module implements
    Feature\ConfigProviderInterface,
    Feature\AutoloaderProviderInterface,
    Feature\ViewHelperProviderInterface,
    Feature\FilterProviderInterface
{
    /**
     * Base module directory
     *
     * @return string
     */
    public function getDir()
    {
        return __DIR__ . '/../..';
    }

    /**
     * {@inheritdoc}
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                $this->getDir() . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        return include $this->getDir() . '/config/module.config.php';
    }

    /**
     * {@inheritdoc}
     */
    public function getViewHelperConfig()
    {
        return array(
            'invokables' => array(
                'imageResize' => 'MyBase\View\Helper\ImageResize',
                'datePatternFormat' => 'MyBase\View\Helper\DatePatternFormat',
                'timeAgo' => 'MyBase\View\Helper\TimeAgo'
            ),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFilterConfig()
    {
        return array(
            'invokables' => array(
                'ellipsis' => 'MyBase\Filter\Ellipsis',
            ),
        );
    }
}
