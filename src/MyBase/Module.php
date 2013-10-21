<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase;

use Zend\ModuleManager\Feature;

class Module extends BaseModule implements
    Feature\ViewHelperProviderInterface,
    Feature\FilterProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDir()
    {
        return __DIR__ . '/../..';
    }

    /**
     * {@inheritdoc}
     */
    public function getNamespace()
    {
        return __NAMESPACE__;
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
