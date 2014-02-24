<?php

/**
 * Copyright (c) 2013 Stefano Torresi (http://stefanotorresi.it)
 * See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase\Test\Image;

use Imagick;
use PHPUnit_Framework_TestCase;
use MyBase\Image\Resizer;

class ResizerTest extends PHPUnit_Framework_TestCase
{
    protected $assetsDir;
    protected $outputDir;

    public function setUp()
    {
        if (!extension_loaded('imagick')) {
            $this->markTestSkipped('Imagick extension not loaded');
        }

        $this->assetsDir = __DIR__ . DIRECTORY_SEPARATOR . '_files';
        $this->outputDir = realpath($this->assetsDir) . DIRECTORY_SEPARATOR . 'output';

        if (!is_dir($this->outputDir) && !mkdir($this->outputDir)) {
            $this->markTestSkipped('Cannot create output directory');
        }
    }

    public function testAccessors()
    {
        $options = [
            'destDir' => $this->outputDir,
            'overwrite' => true,
            'mode' => Resizer::CROP_MODE,
            'quality' => 66,
            'fillColor' => '#000000',
            'dpi' => 72,
        ];

        $resizer = new Resizer($options);

        foreach ($options as $key => $option) {
            $method = 'get'.$key;
            if ($key == 'destDir') {
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
     * @param  string $source
     * @param $width
     * @param $height
     * @param $mode
     * @return string
     */
    public function testResize($source, $width, $height, $mode = null)
    {
        $source = $this->assetsDir . DIRECTORY_SEPARATOR . $source;

        $options = [
            'destDir' => $this->outputDir,
            'overwrite' => true,
            'mode' => $mode,
        ];

        $resizer = new Resizer($options);

        $resize = $resizer->resize($source, $width, $height);

        $this->assertFileExists($resize);

        $resize = new Imagick($resize);

        $ratio = $resize->getImageWidth() / $resize->getImageHeight();
        $inverseRatio = $resize->getImageHeight() / $resize->getImageWidth();

        if ($width == 0) {
            $expected = (int) round($height * $ratio);
            $this->assertEquals($expected, $resize->getimagewidth());
        } else {
            $this->assertEquals($width, $resize->getimagewidth());
        }

        if ($height == 0) {
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
        return [
            ['unionjack.jpg', 400, 0],
            ['unionjack.jpg', 400, 0, Resizer::CROP_MODE],
            ['unionjack.jpg', 0, 400],
            ['unionjack.jpg', 0, 400, Resizer::CROP_MODE],
            ['unionjack.jpg', 400, 300],
            ['unionjack.jpg', 400, 300, Resizer::FILL_MODE],
            ['unionjack.jpg', 400, 300, Resizer::CROP_MODE],
            ['beermug.jpg', 300, 200],
            ['beermug.jpg', 300, 200, Resizer::FILL_MODE],
            ['beermug.jpg', 300, 200, Resizer::CROP_MODE],
            ['beermug.jpg', 300, 0],
            ['beermug.jpg', 300, 0, Resizer::CROP_MODE],
            ['beermug.jpg', 0, 300],
            ['beermug.jpg', 0, 300, Resizer::CROP_MODE],
        ];
    }

    /**
     * @dataProvider invalidImages
     *
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidImages($source, $width, $height, $mode)
    {
        $source = realpath($this->assetsDir.DIRECTORY_SEPARATOR.$source);

        $resizer = new Resizer([
            'destDir' => $this->outputDir,
            'mode' => $mode,
            'overwrite' => true,
        ]);

        $resize = $resizer->resize($source, $width, $height);
    }

    public function invalidImages()
    {
        return [
            ['unionjack.jpg', 400, 0, Resizer::FILL_MODE],
            ['unionjack.jpg', 0, 400, Resizer::FILL_MODE],
            ['unionjack.jpg', 0, 0, Resizer::FILL_MODE],
            ['unionjack.jpg', 0, 0, Resizer::CROP_MODE],
            ['nonexistent', 400, 300, Resizer::DEFAULT_MODE],
        ];
    }

    public function testExistentDestinationIsReturnedWhenOverwriteIsOff()
    {
        // @todo
    }
}
