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
                    __NAMESPACE__ => $this->getDir() . '/src/' . __NAMESPACE__,
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
                'CKEditor' => 'MyBlog\View\Helper\CKEditor',
                'imageResize' => 'MyBlog\View\Helper\ImageResize',
                'datePatternFormat' => 'MyBlog\View\Helper\DatePatternFormat',
                'timeAgo' => 'MyBlog\View\Helper\TimeAgo'
            ),
        );
    }
}
