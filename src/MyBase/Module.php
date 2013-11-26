<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase;

use Zend\ModuleManager\Feature;

class Module extends AbstractModule implements
    Feature\ViewHelperProviderInterface,
    Feature\FilterProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getViewHelperConfig()
    {
        return array(
            'invokables' => array(
                'imageResize' => 'MyBase\View\Helper\ImageResize',
                'datePatternFormat' => 'MyBase\View\Helper\DatePatternFormat',
                'timeAgo' => 'MyBase\View\Helper\TimeAgo',
                'formfieldset' => 'MyBase\View\Helper\FormFieldset',
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
