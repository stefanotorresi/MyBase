<?php

/**
 * Copyright (c) 2013 Stefano Torresi (http://stefanotorresi.it)
 * See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase\View\Helper;

use MyBase\Image\Resizer;
use Zend\View\Helper\AbstractHelper;

class ImageResize extends AbstractHelper
{
    protected $options = array(
        'dest_dir' => './public/img/generated',
        'public_dir' => '/img/generated',
        'width' => 0,
        'height' => 0,
        'mode' => Resizer::DEFAULT_MODE,
        'overwrite' => false,
    );

    public function __invoke($src, $options = array())
    {
        $options = array_merge($this->options, $options);

        if ( strrpos($options['public_dir'], '/')
                != (strlen($options['public_dir'] - 1)) ) {
            $options['public_dir'] .= '/';
        }

        $resizer = new Resizer($src, $options['dest_dir']);

        $resize = $resizer->getResize($options['width'], $options['height'],
                $options['mode'], $options['overwrite']);

        $basePathHelper = $this->getView()->plugin('basePath');

        return $basePathHelper($options['public_dir'].basename($resize));
    }
}
