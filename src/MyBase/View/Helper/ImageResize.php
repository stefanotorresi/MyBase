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
     * @var string
     */
    protected $documentRoot;

    /**
     * @var string
     */
    protected $relativeDestDir;

    /**
     * @var Resizer
     */
    protected $resizer;

    /**
     * @var array
     */
    protected $options = array(
        'width' => 0,
        'height' => 0,
        'mode' => Resizer::DEFAULT_MODE,
        'overwrite' => false,
    );

    /**
     * @param string $documentRoot
     * @param string $relativeDestDir
     */
    public function __construct($documentRoot = './public', $relativeDestDir = '/img/generated')
    {
        $this->documentRoot = $documentRoot;
        $this->relativeDestDir = $relativeDestDir;
    }

    /**
     * @param string $src
     * @param array $options
     * @return string
     */
    public function __invoke($src, $options = array())
    {
        $options = ArrayUtils::merge($this->options, $options);

        $options['destDir'] = $this->getDestinationDir();
        $resizer = $this->getResizer()->setOptions($options);

        $resize = $resizer->resize($src, $options['width'], $options['height']);

        $basePathHelper = $this->getView()->plugin('basePath');

        return $basePathHelper($this->relativeDestDir.'/'.basename($resize));
    }

    /**
     * @return string
     */
    public function getDestinationDir()
    {
        return $this->documentRoot . $this->relativeDestDir;
    }

    /**
     * @param Resizer $resizer
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
}
