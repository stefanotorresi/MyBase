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

class ResizerTest extends PHPUnit_Framework_TestCase
{

    protected $assetsDir = './assets/';

    protected $outputDir = './data/';

    public function testAccessors()
    {
        $options = array(
            'destDir' => $this->outputDir,
            'overwrite' => true,
            'mode' => Resizer::CROP_MODE,
            'quality' => 66,
            'fillColor' => '#000000',
        );

        $resizer = new Resizer($options);

        foreach($options as $key => $option) {
            $method = 'get'.$key;
            if($key == 'destDir') {
                $option = realpath($option);
            }
            $this->assertEquals($option, $resizer->{$method}());
        }
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidDestDirThrowsException()
    {
        $resizer = new Resizer();
        $resizer->setDestDir('nonexistant');
    }

    /**
     * @dataProvider dataImages
     *
     * @param string $source
     * @param $width
     * @param $height
     * @param $mode
     * @return string
     */
    public function testResize($source, $width, $height, $mode = null)
    {
        $source = realpath($this->assetsDir.$source);

        $options = array(
            'destDir' => $this->outputDir,
            'overwrite' => true,
            'mode' => $mode,
        );

        $resizer = new Resizer($options);

        $resize = $resizer->resize($source, $width, $height);

        $this->assertFileExists($resize);

        $resize = new Imagick($resize);

        $ratio = $resize->getImageWidth() / $resize->getImageHeight();
        $inverseRatio = $resize->getImageHeight() / $resize->getImageWidth();

        if ( $width == 0 ) {
            $expected = (int) round($height * $ratio);
            $this->assertEquals($expected, $resize->getimagewidth());
        } else {
            $this->assertEquals($width, $resize->getimagewidth());
        }

        if ( $height == 0 ) {
            $expected = (int) round($width * $inverseRatio);
            $this->assertEquals($expected, $resize->getimageheight());
        } else {
            $this->assertEquals($height, $resize->getimageheight());
        }

        if ($resize->getcompression() === Imagick::COMPRESSION_JPEG) {
            $this->assertEquals($resizer->getQuality(), $resize->getcompressionquality());
        }
    }

    public function dataImages()
    {
        return array(
            array('unionjack.jpg', 400, 0),
            array('unionjack.jpg', 400, 0, Resizer::CROP_MODE),
            array('unionjack.jpg', 0, 400),
            array('unionjack.jpg', 0, 400, Resizer::CROP_MODE),
            array('unionjack.jpg', 400, 300),
            array('unionjack.jpg', 400, 300, Resizer::FILL_MODE),
            array('unionjack.jpg', 400, 300, Resizer::CROP_MODE),
            array('beermug.jpg', 300, 200),
            array('beermug.jpg', 300, 200, Resizer::FILL_MODE),
            array('beermug.jpg', 300, 200, Resizer::CROP_MODE),
            array('beermug.jpg', 300, 0),
            array('beermug.jpg', 300, 0, Resizer::CROP_MODE),
            array('beermug.jpg', 0, 300),
            array('beermug.jpg', 0, 300, Resizer::CROP_MODE),
        );
    }

    /**
     * @dataProvider invalidImages
     *
     * @expectedException \InvalidArgumentException
     */
    public function testZeroValueInFillModeThrowsException($source, $width, $height)
    {
        $source = realpath($this->assetsDir.$source);

        $resizer = new Resizer(array(
            'destDir' => $this->outputDir,
            'mode' => Resizer::FILL_MODE,
            'overwrite' => true,
        ));

        $resize = $resizer->resize($source, $width, $height);
    }

    public function invalidImages()
    {
        return array(
            array('unionjack.jpg', 400, 0),
            array('unionjack.jpg', 0, 400),
            array('unionjack.jpg', 0, 0),
            array('nonexistent', 400, 300),
        );
    }
}
