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
use Zend\Stdlib\StringUtils;

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

    public function testConstructor()
    {
        $filter = new Ellipsis(66);

        $this->assertEquals(66, $filter->getMaxLength());

        $filter = new Ellipsis(['maxLength' => 66, 'ellipsis' => '...']);

        $this->assertEquals(66, $filter->getMaxLength());
        $this->assertEquals('...', $filter->getEllipsis());
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
        $text = "Lorem ìpsum dòlor sit àmet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.";

        $this->filter->setMaxLength(15);
        $this->assertEquals("Lorem ìpsum […]", $this->filter->filter($text));

        $this->filter->setMaxLength(27);
        $this->assertEquals("Lorem ìpsum dòlor sit àmet, […]", $this->filter->filter($text));

        $this->filter->setMaxLength(57);
        $this->assertEquals("Lorem ìpsum dòlor sit àmet, consectetur adipisicing elit, […]", $this->filter->filter($text));
    }

    public function testNonStringFiltering()
    {
        $fake = new \stdClass();
        $this->assertSame($fake, $this->filter->filter($fake));
    }

    public function testFunctionalFilterManagerIntegration()
    {
        $filterManager = Bootstrap::getServiceManager()->get('FilterManager');

        $this->assertInstanceOf('MyBase\Filter\Ellipsis', $filterManager->get('ellipsis'));
    }

    public function testOneLongWordIsNotElided()
    {
        $text = "Loremipsumdolorsitamet";

        $this->filter->setMaxLength(15);
        $this->assertEquals('Loremipsumd […]', $this->filter->filter($text));
    }

    public function testEncodingSetterAlsoSetsStringWrapper()
    {
        $this->filter->setEncoding('ISO-8859-1');
        $this->assertEquals('ISO-8859-1', $this->filter->getStringWrapper()->getEncoding());
    }

    public function testStringWrapperSetterOverridesEncoding()
    {
        $this->filter->setStringWrapper(StringUtils::getWrapper('ISO-8859-1'));
        $this->assertEquals('UTF-8', $this->filter->getStringWrapper()->getEncoding());
    }

    public function testJustReturnIfMaxLengthIsHigherThanValueLength()
    {
        $text = "Lorem ipsum dolor sit amet";

        $this->filter->setMaxLength(26);
        $this->assertEquals($text, $this->filter->filter($text));

        $this->filter->setMaxLength(27);
        $this->assertEquals($text, $this->filter->filter($text));
    }
}
