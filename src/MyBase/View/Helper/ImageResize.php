<?php

/**
 * Copyright (c) 2013 Stefano Torresi (http://stefanotorresi.it)
 * See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase\View\Helper;

use MyBase\Image\Resizer;
use Zend\Stdlib\ArrayUtils;
use Zend\View\Helper\AbstractHelper;

class ImageResize extends AbstractHelper
{
    /**
     * @var Resizer
     */
    protected $resizer;

    /**
     * @var array
     */
    protected $options = array(
        'destDir' => './public/img/generated',
        'relativeDir' => '/img/generated',
        'width' => 0,
        'height' => 0,
        'mode' => Resizer::DEFAULT_MODE,
        'overwrite' => false,
    );

    /**
     * @param null  $resizer
     * @param array $options
     */
    public function __construct($resizer = null, $options = array())
    {
        if ($resizer) {
            $this->setResizer($resizer);
        }

        if ($options) {
            $this->setOptions($options);
        }
    }

    /**
     * @param  string $src
     * @param  array  $options
     * @return string
     */
    public function __invoke($src, $options = array())
    {
        $options = $this->normalizeOptions($options);
        $resizer = $this->getResizer()->setOptions($options);

        $resize = $resizer->resize($src, $options['width'], $options['height']);

        $basePathHelper = $this->getView()->plugin('basePath');

        return $basePathHelper($options['relativeDir'].'/'.basename($resize));
    }

    /**
     * @param  Resizer     $resizer
     * @return ImageResize
     */
    public function setResizer(Resizer $resizer)
    {
        $this->resizer = $resizer;

        return $this;
    }

    /**
     * @return Resizer
     */
    public function getResizer()
    {
        if (!$this->resizer) {
            $this->resizer = new Resizer();
        }

        return $this->resizer;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    public function normalizeOptions($options)
    {
        $options = ArrayUtils::merge($this->options, $options);
        $options['relativeDir'] = ltrim(rtrim($options['relativeDir'], '/'), '/');

        return $options;
    }
}
