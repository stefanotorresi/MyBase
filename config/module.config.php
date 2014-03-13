<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase;

return [
    __NAMESPACE__ => [
        'AsseticSassFilter' => [
            'sass_path' => '/usr/bin/sass',
            'scss' => true,
            'style' => 'compressed',
            'unix_newlines' => true
        ]
    ],

    'service_manager' => [
        'factories' => [
            'MyAsseticSassFilter'   => 'MyBase\ServiceManager\AsseticSassFilterFactory',
            'HTMLPurifier'          => 'MyBase\ServiceManager\HTMLPurifierFactory',
        ],
    ],

    'view_helpers' => [
        'invokables' => [
            'imageResize' => 'MyBase\View\Helper\ImageResize',
            'datePatternFormat' => 'MyBase\View\Helper\DatePatternFormat',
            'timeAgo' => 'MyBase\View\Helper\TimeAgo',
            'formfieldset' => 'MyBase\View\Helper\FormFieldset',
            'bootstrapFormFile' => 'MyBase\View\Helper\BootstrapFormFile',
        ],
    ],

    'filters' => [
        'invokables' => [
            'ellipsis' => 'MyBase\Filter\Ellipsis',
            'fileArrayToString' => 'MyBase\Filter\FileArrayToString',
        ],
    ],

    'soflomo_purifier' => [
        'config' => [
            'HTML.DefinitionID' => 'my-base custom definition',
            'HTML.DefinitionRev' => 2,
        ],
        'definitions' => [
            'HTML' => [
                'addAttribute' => [
                    'a', 'target', 'Enum#_blank,_self,_target,_top'
                ],
            ],
        ],
    ],

    /**
     * Doctrine module
     */
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ => [
                'class' => 'Doctrine\ORM\Mapping\Driver\XmlDriver',
                'paths' => __DIR__
            ],
            'orm_default' =>[
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__
                ]
            ]
        ]
    ],
];
