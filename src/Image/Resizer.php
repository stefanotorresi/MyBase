<?php

/**
 * Copyright (c) 2013 Stefano Torresi (http://stefanotorresi.it)
 * See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase\Image;

use Exception;
use ImagickException;
use Imagick;
use InvalidArgumentException;
use RuntimeException;
use Traversable;
use Zend\Stdlib\ArrayUtils;

class Resizer
{
    const CROP_MODE = 'crop';
    const FILL_MODE = 'fill';
    const DEFAULT_MODE = 'default';

    /**
     * @var string
     */
    protected $destDir;

    /**
     * @var integer
     */
    protected $quality = 80;

    /**
     * @var string
     */
    protected $fillColor = '#ffffff';

    /**
     * @var string
     */
    protected $mode;

    /**
     * @var bool
     */
    protected $overwrite = false;

    /**
     * @var int
     */
    protected $dpi = 96;

    /**
     * @param  array            $options
     * @throws RuntimeException
     */
    public function __construct($options = array())
    {
        if (!extension_loaded('imagick')) {
            throw new RuntimeException('Imagick extension not loaded');
        }

        $this->setOptions($options);

        if ($this->mode === null) {
            $this->mode = self::DEFAULT_MODE;
        }
    }

    /**
     * @param  array|Traversable        $options
     * @throws InvalidArgumentException
     * @return $this
     */
    public function setOptions($options)
    {
        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        } elseif (!is_array($options)) {
            throw new InvalidArgumentException(
                'The options parameter must be an array or a Traversable'
            );
        }

        foreach ($options as $key => $value) {
            $method = 'set'.$key;
            if (property_exists($this, $key) && method_exists($this, $method)) {
                $this->$method($value);
            }
        }

        return $this;
    }

    /**
     * @param  int   $quality
     * @return $this
     */
    public function setQuality($quality)
    {
        $this->quality = $quality;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuality()
    {
        return $this->quality;
    }

    /**
     * @param  string $mode
     * @return $this
     */
    public function setMode($mode)
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param  string $fillColor
     * @return $this
     */
    public function setFillColor($fillColor)
    {
        $this->fillColor = $fillColor;

        return $this;
    }

    /**
     * @return string
     */
    public function getFillColor()
    {
        return $this->fillColor;
    }

    /**
     * @param  string                   $destDir
     * @throws InvalidArgumentException
     * @return $this
     */
    public function setDestDir($destDir)
    {
        $destDir = realpath($destDir);

        if (!is_dir($destDir) || !is_writable($destDir)) {
            throw new InvalidArgumentException("Destination is not a writable directory");
        }

        $this->destDir = $destDir;

        return $this;
    }

    /**
     * @return string
     */
    public function getDestDir()
    {
        return $this->destDir;
    }

    /**
     * @param  boolean $overwrite
     * @return $this
     */
    public function setOverwrite($overwrite)
    {
        $this->overwrite = (bool) $overwrite;

        return $this;
    }

    /**
     * @return bool
     */
    public function getOverwrite()
    {
        return $this->overwrite;
    }

    /**
     * @return int
     */
    public function getDpi()
    {
        return $this->dpi;
    }

    /**
     * @param  int   $dpi
     * @return $this
     */
    public function setDpi($dpi)
    {
        $this->dpi = $dpi;

        return $this;
    }

    /**
     * @param $sourceFile
     * @param $width
     * @param $height
     * @throws \ImagickException
     * @throws InvalidArgumentException
     * @throws \Exception
     * @return string
     */
    public function resize($sourceFile, $width, $height)
    {
        if (!file_exists($sourceFile)) {
            throw new InvalidArgumentException("Source file not found {$sourceFile}");
        }

        $srcFileName = pathinfo($sourceFile, PATHINFO_FILENAME);

        $destination = vsprintf(
            '%s-%s-%dx%d-%d.jpg',
            array(
                $this->getDestDir().DIRECTORY_SEPARATOR.$srcFileName,
                $this->getMode(),
                $width,
                $height,
                $this->getQuality()
            )
        );

        if (file_exists($destination) && !$this->overwrite) {
            return $destination;
        }

        if (file_exists($destination) && !is_writable($destination)) {
            throw new Exception("Destination file is not writeable");
        }

        $image = new Imagick($sourceFile);
        $image->setCompression(Imagick::COMPRESSION_JPEG);
        $image->stripImage();
        $image->setCompressionQuality($this->getQuality());
        $image->setImageResolution($this->getDpi(), $this->getDpi());
        $image->resampleImage($this->getDpi(), $this->getDpi(), Imagick::FILTER_LANCZOS, true);

        switch ($this->mode) {
            case self::FILL_MODE :
                if (!$width || !$height) {
                    throw new InvalidArgumentException("Zero size is not accepted in '{$this->mode}' mode");
                }

                $image->scaleImage($width, $height, true);
                $oldWidth = $image->getImageWidth();
                $oldHeight = $image->getImageHeight();
                $image->setImageBackgroundColor($this->getFillColor());
                $image->extentImage($width, $height, ($oldWidth - $width) / 2, ($oldHeight - $height) / 2);
                $image->writeImage($destination);

                break;

            case self::CROP_MODE :
                if (!$width && !$height) {
                    throw new InvalidArgumentException("Either width or height must be non zero in '{$this->mode}' mode");
                }

                if (!$width) {
                    $width = $height;
                }

                if (!$height) {
                    $height = $width;
                }

                $image->cropThumbnailImage($width, $height);
                $image->writeImage($destination);

                break;

            default :
                $image->resizeImage($width, $height, Imagick::FILTER_LANCZOS, 1);
                $image->writeImage($destination);
        }

        return $destination;
    }
}
