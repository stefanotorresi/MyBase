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
            'MyAsseticSassFilter' => __NAMESPACE__ . '\Service\AsseticSassFilterFactory'
        ),
    ),
);
