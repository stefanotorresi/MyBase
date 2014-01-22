<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBaseTest\ServiceManager;

use HTMLPurifier;
use MyBase\ServiceManager\HTMLPurifierFactory;
use MyBaseTest\Bootstrap;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\Stdlib\ArrayUtils;

class HTMLPurifierFactoryTest extends TestCase
{
    /**
     * @var HTMLPurifierFactory
     */
    protected $factory;

    /**
     * @var array
     */
    protected $config;

    public function setUp()
    {
        if (!class_exists('HTMLPurifier')) {
            $this->markTestSkipped('HTMLPurifier not found');
        }

        $this->factory = new HTMLPurifierFactory();

        $this->config = ArrayUtils::merge(
            include '../vendor/soflomo/purifier/config/module.config.php',
            [
                'soflomo_purifier' => [
                    'definitions' => [
                    ],
                ],
            ]
        );
    }

    protected function getServiceManagerMock()
    {
        $serviceManager = $this->getMock('Zend\ServiceManager\ServiceManager');
        $serviceManager
            ->expects($this->once())
            ->method('get')
            ->with('config')
            ->will($this->returnValue($this->config));

        return $serviceManager;
    }

    public function testCreateService()
    {
        /** @var HTMLPurifier $htmlPurifier */
        $htmlPurifier = $this->factory->createService($this->getServiceManagerMock());

        $this->assertInstanceOf('HTMLPurifier', $htmlPurifier);
    }

    public function testConfigurationSetter()
    {
        $this->config['soflomo_purifier']['config']['HTML.Doctype'] = 'HTML 4.01 Strict';

        /** @var HTMLPurifier $htmlPurifier */
        $htmlPurifier = $this->factory->createService($this->getServiceManagerMock());

        $this->assertEquals('HTML 4.01 Strict', $htmlPurifier->config->get('HTML.Doctype'));
    }

    public function testDefinitionsSetting()
    {
        $this->config = ArrayUtils::merge($this->config, [
            'soflomo_purifier' => [
                'config' => [
                    'HTML.DefinitionID' => 'test',
                    'Cache.DefinitionImpl' => null,
                ],
                'definitions' => [
                    'HTML' => [
                        'addAttribute' => [ 'div', 'data-id', 'Number#1' ],
                    ]
                ],
            ]
        ]);

        /** @var HTMLPurifier $htmlPurifier */
        $htmlPurifier = $this->factory->createService($this->getServiceManagerMock());

        $this->assertEquals('<div data-id="1"></div>', $htmlPurifier->purify('<div data-id="1" data-foo="invalid"></div>'));
    }

    public function testServiceManagerIntegration()
    {
        $serviceManager = Bootstrap::getServiceManager();

        /** @var HTMLPurifier $htmlPurifier */
        $htmlPurifier = $serviceManager->get('HTMLPurifier');

        $this->assertInstanceOf('HTMLPurifier', $htmlPurifier);

        // test default module configuration
        $this->assertEquals('<a target="_blank"></a>', $htmlPurifier->purify('<a target="_blank"></a>'));
    }
}
