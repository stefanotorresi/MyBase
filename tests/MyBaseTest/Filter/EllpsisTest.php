<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBaseTest\Filter;

use MyBase\Filter\Ellipsis;
use MyBaseTest\Bootstrap;
use PHPUnit_Framework_TestCase as TestCase;

class EllpsisTest extends TestCase
{
    /**
     * @var Ellipsis
     */
    public $filter;

    public function setUp()
    {
        $this->filter = new Ellipsis();
    }

    public function testOptionApi()
    {
        $this->filter->setEllipsis('...');
        $this->assertEquals('...', $this->filter->getEllipsis());

        $this->filter->setMaxLength(20);
        $this->assertEquals(20, $this->filter->getMaxLength());
    }

    public function testMaxLenghtIsCastedToInt()
    {
        $this->filter->setMaxLength('asd');
        $this->assertEquals(0, $this->filter->getMaxLength());
    }

    public function testFilter()
    {
        $text = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.";

        $this->filter->setMaxLength(15);
        $this->assertEquals("Lorem ipsum […]", $this->filter->filter($text));

        $this->filter->setMaxLength(27);
        $this->assertEquals("Lorem ipsum dolor sit […]", $this->filter->filter($text));

        $this->filter->setMaxLength(58);
        $this->assertEquals("Lorem ipsum dolor sit amet, consectetur adipisicing elit, […]", $this->filter->filter($text));
    }

    public function testFunctionalFilterManagerIntegration()
    {
        $filterManager = Bootstrap::getServiceManager()->get('FilterManager');

        $this->assertInstanceOf('MyBase\Filter\Ellipsis', $filterManager->get('ellipsis'));
    }
}
