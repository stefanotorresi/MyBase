<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase;

use Zend\ModuleManager\Feature;

class Module implements
    Feature\AutoloaderProviderInterface,
    Feature\ViewHelperProviderInterface
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
}
