<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase\Test\View\Helper;

use MyBase\Image\Resizer;
use MyBase\View\Helper\ImageResize;
use Zend\View\Renderer\PhpRenderer;

class ImageResizeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ImageResize
     */
    protected $helper;

    const BASE_PATH = '/';

    public function setUp()
    {
        $helper = new ImageResize();
        $helper->setView(new PhpRenderer());
        $helper->getView()->plugin('basePath')->setBasePath(self::BASE_PATH);

        $this->helper = $helper;
    }

    public function testConstructorAndAccessors()
    {
        $resizer = $this->getMock('MyBase\Image\Resizer');
        $options = ['foo' => 'bar'];
        $helper = new ImageResize($resizer, $options);

        $this->assertEquals($resizer, $helper->getResizer());
        $this->assertEquals($options, $helper->getOptions());
    }

    public function testResizerLazyLoad()
    {
        $this->assertInstanceOf('MyBase\Image\Resizer', $this->helper->getResizer());
    }

    /**
     * @dataProvider dataHelperInvokeOptions
     *
     * @param $image
     * @param $options
     */
    public function testHelperFunctionality($image, $options)
    {
        $helper = $this->helper;
        $resizer = $this->getMock('MyBase\Image\Resizer'); /** @var $resizer Resizer */
        $helper->setResizer($resizer);

        $normalizedOptions = $helper->normalizeOptions($options);

        $resizer->expects($this->once())
            ->method('resize')
            ->with($image, $normalizedOptions['width'], $normalizedOptions['height'])
            ->will($this->returnValue('some-filename'));

        $this->assertEquals(
            self::BASE_PATH . $normalizedOptions['relativeDir'].'/some-filename',
            $helper->__invoke($image, $options)
        );
    }

    public function dataHelperInvokeOptions()
    {
        return [
            ['test-image.jpg', ['width' => 400, 'height' => 300]],
            ['test-image.jpg', ['quality' => 75]],
            ['test-image.jpg', ['relativeDir' => 'foo/']],
            ['test-image.jpg', ['relativeDir' => '/foo/']],
            ['test-image.jpg', ['relativeDir' => '/foo']],
        ];
    }

    public function testResizerExceptionAreCatchedAndPlaceholderIsDisplayed()
    {
        $helper = $this->helper;
        $resizer = $this->getMock('MyBase\Image\Resizer');
        $translator = $this->getMock('Zend\I18n\Translator\Translator');

        $resizer->expects($this->once())
            ->method('resize')
            ->will($this->throwException(new \Exception()));

        $translator->expects($this->once())
            ->method('translate')
            ->will($this->returnValue('translated message'));

        $helper->setTranslator($translator);
        $helper->setResizer($resizer);

        $result = $helper->__invoke('missing-image', ['width' => 100, 'height' => 100]);

        $this->assertEquals('http://placehold.it/100x100&text=translated message', $result);
    }

    public function testShowResizerExceptionMessageAsPlaceholderText()
    {
        $helper = $this->helper;
        $resizer = $this->getMock('MyBase\Image\Resizer');

        $resizer->expects($this->once())
            ->method('resize')
            ->will($this->throwException(new \Exception('some exception message')));

        $helper->setResizer($resizer);

        $result = $helper->__invoke('missing-image', ['width' => 100, 'height' => 100, 'showResizerExceptions' => true]);

        $this->assertEquals('http://placehold.it/100x100&text=some exception message', $result);
    }
}
