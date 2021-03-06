<?php

/**
 *
 * @author Stefano Torresi <webdeveloper@stefanotorresi.it>
 */

namespace MyBase\Captcha;

use Imagick;
use ImagickPixel;
use ImagickDraw;
use Zend\Captcha\Image;
use Zend\Captcha\Exception;

class ImagickCaptcha extends Image
{
    /**
     * Constructor
     *
     * @param  array|\Traversable                    $options
     * @throws Exception\ExtensionNotLoadedException
     */
    public function __construct($options = null)
    {
        if (! extension_loaded("imagick")) {
            throw new Exception\ExtensionNotLoadedException(sprintf("%s requires Imagick extension", __CLASS__));
        }

        parent::__construct($options);
    }

    /**
     * Generate image captcha
     *
     * @param  string                            $id   Captcha ID
     * @param  string                            $word Captcha word
     * @throws Exception\NoFontProvidedException
     */
    protected function generateImage($id, $word)
    {
        $font = $this->getFont();

        if (empty($font)) {
            throw new Exception\NoFontProvidedException('Image CAPTCHA requires font');
        }

        $w     = $this->getWidth();
        $h     = $this->getHeight();
        $fsize = $this->getFontSize();

        $img_file   = $this->getImgDir() . $id . $this->getSuffix();

        if (empty($this->startImage)) {
            $img = new Imagick();
            $img->newImage($w, $h, new ImagickPixel('#FFFFFF'), 'png');
        } else {
            $img = new Imagick($this->startImage);
            $w = $img->getImageWidth();
            $h = $img->getImageHeight();
        }

        $text = new ImagickDraw();
        $text->setFillColor('#000000');
        $text->setFont($font);
        $text->setFontSize(empty($fsize) ? $h - 10 : $fsize);
        $text->setGravity(Imagick::GRAVITY_CENTER);
        $text->annotation(0, 0, $word);
        $img->drawImage($text);

        // generate noise
        $noise = new ImagickDraw();
        $noise->setFilLColor('#000000');
        for ($i=0; $i<$this->dotNoiseLevel; $i++) {
            $x = mt_rand(0, $w);
            $y = mt_rand(0, $h);
            $noise->circle($x, $y, $x+mt_rand(0.3, 1.7), $y+mt_rand(0.3, 1.7));
        }
        for ($i=0; $i<$this->lineNoiseLevel; $i++) {
            $noise->line(mt_rand(0, $w), mt_rand(0, $h), mt_rand(0, $w), mt_rand(0, $h));
        }

        $img->waveImage(5, mt_rand(60, 100));
        $img->scaleimage($w, $h);
        $img->drawImage($noise);
        $img->swirlImage(mt_rand(10, 30));

        file_put_contents($img_file, $img);
        unset($img);
    }

}
