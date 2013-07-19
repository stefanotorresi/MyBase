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
     * @param array $options
     */
    public function __construct($options = array())
    {
        if (!extension_loaded('imagick')) {
            throw new \RuntimeException('Imagick extension not loaded');
        }

        $this->setOptions($options);

        if ($this->mode === null) {
            $this->mode = self::DEFAULT_MODE;
        }
    }

    /**
     * @param array|Traversable $options
     * @throws \InvalidArgumentException
     * @return Resizer
     */
    public function setOptions($options)
    {
        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        } elseif (!is_array($options)) {
            throw new \InvalidArgumentException(
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
     * @param int $quality
     * @return Resizer
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
     * @param string $mode
     * @return Resizer
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
     * @param string $fillColor
     * @return Resizer
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
     * @param string $destDir
     * @throws \InvalidArgumentException
     * @return Resizer
     */
    public function setDestDir($destDir)
    {
        $destDir = realpath($destDir);

        if (!is_dir($destDir) || !is_writable($destDir)) {
            throw new \InvalidArgumentException("Destination is not a writable directory");
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
     * @param boolean $overwrite
     * @return Resizer
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
     * @param $sourceFile
     * @param $width
     * @param $height
     * @throws \ImagickException
     * @throws \InvalidArgumentException
     * @throws \Exception
     * @return string
     */
    public function resize($sourceFile, $width, $height)
    {
        if (!file_exists($sourceFile)) {
            throw new \InvalidArgumentException("Source file not found {$sourceFile}");
        }

        $srcFileName = pathinfo($sourceFile, PATHINFO_FILENAME);

        $destination = vsprintf(
            '%s-%s-%dx%d.jpg',
            array(
                $this->destDir.DIRECTORY_SEPARATOR.$srcFileName,
                $this->mode,
                $width,
                $height
            )
        );

        if (file_exists($destination) && !$this->overwrite) {
            return $destination;
        }

        if (file_exists($destination) && !is_writable($destination)) {
            throw new Exception("Destination file is not writeable");
        }

        $command = "convert " . escapeshellarg($sourceFile) . " " .
            "-quality {$this->quality} ";

        $image = new Imagick($sourceFile);

        switch($this->mode){
            case self::FILL_MODE :
                if (!$width || !$height) {
                    throw new \InvalidArgumentException("Zero size is not accepted in '{$this->mode}' mode");
                }

                $command .= "-resize {$width}x{$height}\> ".
                    "-size {$width}x{$height} xc:{$this->fillColor} ".
                    "+swap -gravity center -composite ";
                break;

            case self::CROP_MODE :
                // check ratio
                if ( $height > 0 && ($image->getImageWidth() / $image->getImageHeight()) > ($width / $height) ) {
                    $argResize = "x".$height;
                } else {
                    $argResize = $width."x";
                }

                $command .= "-resize {$argResize} -gravity center ".
                    "-crop {$width}x{$height}+0+0 ";
                break;

            default :
                $size = max(array($width, $height));

                $command .= "-resize {$size}x{$size} ";
        }

        $command .= escapeshellarg($destination);

        $return = -1;
        @exec($command, $output, $return);
        if ($return !== 0) {
            throw new \RuntimeException("Imagick execution error");
        }

        return $destination;
    }
}
