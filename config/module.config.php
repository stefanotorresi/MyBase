<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase;

return array(
    __NAMESPACE__ => array(
        'AsseticSassFilter' => array(
            'sass_path' => '/usr/bin/sass',
            'scss' => true,
            'style' => 'compressed',
            'unix_newlines' => true
        )
    ),

    'service_manager' => array(
        'factories' => array(
            'MyAsseticSassFilter'   => 'MyBase\ServiceManager\AsseticSassFilterFactory',
            'HTMLPurifier'          => 'MyBase\ServiceManager\HTMLPurifierFactory',
        ),
    ),

    'view_helpers' => array(
        'invokables' => array(
            'imageResize' => 'MyBase\View\Helper\ImageResize',
            'datePatternFormat' => 'MyBase\View\Helper\DatePatternFormat',
            'timeAgo' => 'MyBase\View\Helper\TimeAgo',
            'formfieldset' => 'MyBase\View\Helper\FormFieldset',
            'bootstrapFormFile' => 'MyBase\View\Helper\BootstrapFormFile',
        ),
    ),

    'filters' => array(
        'invokables' => array(
            'ellipsis' => 'MyBase\Filter\Ellipsis',
            'fileArrayToString' => 'MyBase\Filter\FileArrayToString',
        ),
    ),

    'soflomo_purifier' => array(
        'config' => array(
            'HTML.DefinitionID' => 'my-base custom definition',
            'HTML.DefinitionRev' => 2,
        ),
        'definitions' => array(
            'HTML' => array(
                'addAttribute' => array(
                    'a', 'target', 'Enum#_blank,_self,_target,_top'
                ),
            ),
        ),
    ),
);
