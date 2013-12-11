<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBaseTest\ServiceManager;

use MyBase\ServiceManager\AsseticSassFilterFactory;
use PHPUnit_Framework_TestCase;

class AsseticSassFilterFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var AsseticSassFilterFactory
     */
    public $factory;

    public function setUp()
    {
        if (!class_exists('Assetic\Filter\Sass\SassFilter')) {
            $this->markTestSkipped('Assetic Sass filter class not found');
        }

        $this->factory = new AsseticSassFilterFactory();
    }

    protected function getServiceManagerMock($config)
    {
        $serviceManager = $this->getMock('Zend\ServiceManager\ServiceManager');
        $serviceManager
            ->expects($this->once())
            ->method('get')
            ->with('Config')
            ->will($this->returnValue($config));

        return $serviceManager;
    }

    public function testFactoryFunctionality()
    {
        $config = array(
            'MyBase' => array(
                'AsseticSassFilter' => array(
                    'sass_path' => 'foo',
                    'ruby_path' => 'bar',
                    'scss' => true
                )
            )
        );
        $filter = $this->factory->createService($this->getServiceManagerMock($config));

        $this->assertInstanceOf('Assetic\Filter\Sass\SassFilter', $filter);

        $reflection = new \ReflectionObject($filter);

        $sassPathProp = $reflection->getProperty('sassPath');
        $sassPathProp->setAccessible(true);

        $rubyPathProp = $reflection->getProperty('rubyPath');
        $rubyPathProp->setAccessible(true);

        $scssProp = $reflection->getProperty('scss');
        $scssProp->setAccessible(true);

        $this->assertEquals('foo', $sassPathProp->getValue($filter));
        $this->assertEquals('bar', $rubyPathProp->getValue($filter));
        $this->assertTrue($scssProp->getValue($filter));
    }

    public function testDefaultConstructorParameters()
    {
        $config = array(
            'MyBase' => array(
                'AsseticSassFilter' => array()
            )
        );
        $filter = $this->factory->createService($this->getServiceManagerMock($config));

        $reflection = new \ReflectionObject($filter);

        $sassPathProp = $reflection->getProperty('sassPath');
        $sassPathProp->setAccessible(true);

        $rubyPathProp = $reflection->getProperty('rubyPath');
        $rubyPathProp->setAccessible(true);

        $this->assertNull($sassPathProp->getValue($filter));
        $this->assertNull($rubyPathProp->getValue($filter));
    }
}
