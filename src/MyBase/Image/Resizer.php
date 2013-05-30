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

class Resizer
{
    const CROP_MODE = 'crop';
    const FILL_MODE = 'fill';
    const DEFAULT_MODE = 'default';

    /**
     *
     * @var string
     */
    private $sourceFile;

    /**
     *
     * @var string
     */
    private $destDir;

    /**
     *
     * @var integer
     */
    public $quality = 80;

    /**
     *
     * @var string
     */
    public $fillColor = '#ffffff';

    /**
     *
     * @var string
     */
    public $mode;

    /**
     *
     * @param string $sourceFile        absolute source file path
     * @param $destDir
     * @throws \Exception
     * @internal param string $destPath absolute destinataion directory path
     */
    public function __construct($sourceFile, $destDir)
    {
        if (!file_exists($sourceFile)) {
            throw new Exception("Source file not found {$sourceFile}");
        }

        $destDir = rtrim($destDir, "\\/");

        if (!is_dir($destDir)) {
            throw new Exception("Destination is not a directory");
        }

        if (!is_writable($destDir)) {
            throw new Exception("Destination directory is not writeable");
        }

        $this->sourceFile   = $sourceFile;
        $this->destDir      = $destDir;
    }

    /**
     *
     * @param int $width
     * @param int $height
     * @param string $mode
     * @param bool $overwrite
     * @throws \ImagickException
     * @throws \Exception
     * @return string   Destination absolute file path
     */
    public function getResize($width, $height, $mode = self::CROP_MODE, $overwrite = false)
    {
        if (!$mode) {
            $mode = self::DEFAULT_MODE;
        }

        $srcFileName = pathinfo($this->sourceFile, PATHINFO_FILENAME);

        $destination = $this->destDir.DIRECTORY_SEPARATOR
                .$srcFileName.'-'.$mode.'-'.$width.'x'.$height.'.jpg';

        if (file_exists($destination) && !$overwrite) {
            return $destination;
        }

        if (file_exists($destination) && !is_writable($destination)) {
            throw new Exception("Destination file is not writeable");
        }

        $command = "convert " . escapeshellarg($this->sourceFile) . " " .
                    "-quality {$this->quality} ";

        $image = new Imagick($this->sourceFile);

        switch($mode){
            case self::FILL_MODE :
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

        $return = 1;
        @exec($command, $output, $return);
        if ($return > 0) {
            throw new ImagickException("Execution error");
        }

        return $destination;
    }
}
