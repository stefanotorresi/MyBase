<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase\Test;

use PHPUnit_Framework_TestCase as TestCase;

class AbstractModuleTest extends TestCase
{
    public function testPSR0Configuration()
    {
        $modulePath = __DIR__ . '/TestAsset/SamplePSR0Module/src/SamplePSR0Module/Module.php';
        $moduleDir = dirname(dirname(dirname($modulePath)));
        include $modulePath;
        $module = new \SamplePSR0Module\Module();

        $this->assertEquals('SamplePSR0Module', $module->getNamespace());
        $this->assertEquals($moduleDir, $module->getDir());

        $autoloaderConfig = $module->getAutoloaderConfig();
        $this->assertEquals(
            $moduleDir . '/src/' . $module->getNamespace(),
            $autoloaderConfig['Zend\Loader\StandardAutoloader']['namespaces'][$module->getNamespace()]
        );
    }

    public function testPSR4Configuration()
    {
        $modulePath = __DIR__ . '/TestAsset/SamplePSR4Module/src/Module.php';
        $moduleDir = dirname(dirname($modulePath));
        include $modulePath;
        $module = new \SamplePSR4Module\Module();

        $this->assertEquals('SamplePSR4Module', $module->getNamespace());
        $this->assertEquals($moduleDir, $module->getDir());

        $autoloaderConfig = $module->getAutoloaderConfig();
        $this->assertEquals(
            $moduleDir . '/src',
            $autoloaderConfig['Zend\Loader\StandardAutoloader']['namespaces'][$module->getNamespace()]
        );
    }

    public function testPrefixedPSR0Configuration()
    {
        $modulePath = __DIR__ . '/TestAsset/PrefixedPSR0Module/src/Prefix/SamplePSR0Module/Module.php';
        $moduleDir = dirname(dirname(dirname(dirname($modulePath))));
        include $modulePath;
        $module = new \Prefix\SamplePSR0Module\Module();

        $this->assertEquals('Prefix\SamplePSR0Module', $module->getNamespace());
        $this->assertEquals($moduleDir, $module->getDir());

        $autoloaderConfig = $module->getAutoloaderConfig();
        $this->assertEquals(
            $moduleDir . '/src/' . str_replace('\\', DIRECTORY_SEPARATOR, $module->getNamespace()),
            $autoloaderConfig['Zend\Loader\StandardAutoloader']['namespaces'][$module->getNamespace()]
        );
    }
}
