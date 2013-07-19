<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */


class ImageResizeTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $helper = new \MyBase\View\Helper\ImageResize('./', '/data');
        $helper->setView(new \Zend\View\Renderer\PhpRenderer());
        $helper->getView()->plugin('basePath')->setBasePath('/');

        $this->helper = $helper;
    }

    public function testHelper()
    {
        $act = $this->helper->__invoke('./assets/unionjack.jpg', array('width' => 400, 'height' => 300));

        $this->assertEquals('/data/unionjack-default-400x300-80.jpg', $act);
    }
}
