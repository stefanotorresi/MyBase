<?php

/**
 * Copyright (c) 2013 Stefano Torresi (http://stefanotorresi.it)
 * See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBaseTest\Image;

use Imagick;
use PHPUnit_Framework_TestCase;
use MyBase\Image\Resizer;

class ResizerTest extends PHPUnit_Framework_TestCase{

    protected $assetsDir = './assets/';

    protected $outputDir = './data/';

    /**
     * @dataProvider dataImages
     *
     * @param string $source
     * @return string
     */
    public function testCanCreateFile($source, $width, $height, $mode = null)
    {
        $source = realpath($this->assetsDir.$source);

        $resizer = new Resizer($source, $this->outputDir);

        $resize = $resizer->getResize($width, $height, $mode, true);

        $this->assertFileExists($resize);

        return array(
            'resize' => $resize,
            'width' => $width,
            'height' => $height,
        );
    }

    /**
     * @depends testCanCreateFile
     */
    public function testImageSizeIsCorrect($data)
    {
        $image = new Imagick($data['resize']);

        if ( $data['width'] > 0 ) {
            $this->assertEquals($data['width'], $image->getimagewidth());
        }

        if ( $data['height'] > 0 ) {
            $this->assertEquals($data['height'], $image->getimageheight());
        }
    }

    public function dataImages()
    {
        return array(
            array('unionjack.jpg', 400, 0),
            array('unionjack.jpg', 400, 0, Resizer::FILL_MODE),
            array('unionjack.jpg', 400, 0, Resizer::CROP_MODE),
            array('unionjack.jpg', 0, 400),
            array('unionjack.jpg', 0, 400, Resizer::FILL_MODE),
            array('unionjack.jpg', 0, 400, Resizer::CROP_MODE),
            array('unionjack.jpg', 400, 300),
            array('unionjack.jpg', 400, 300, Resizer::FILL_MODE),
            array('unionjack.jpg', 400, 300, Resizer::CROP_MODE),
            array('beermug.jpg', 300, 200),
            array('beermug.jpg', 300, 200, Resizer::FILL_MODE),
            array('beermug.jpg', 300, 200, Resizer::CROP_MODE),
            array('beermug.jpg', 300, 0),
            array('beermug.jpg', 300, 0, Resizer::FILL_MODE),
            array('beermug.jpg', 300, 0, Resizer::CROP_MODE),
            array('beermug.jpg', 0, 300),
            array('beermug.jpg', 0, 300, Resizer::FILL_MODE),
            array('beermug.jpg', 0, 300, Resizer::CROP_MODE),
        );
    }
}
