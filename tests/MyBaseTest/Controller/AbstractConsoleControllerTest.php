<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBaseTest\Controller;

use PHPUnit_Framework_TestCase as TestCase;
use Zend\Http\Request as HttpRequest;
use Zend\Console\Request as ConsoleRequest;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;

class AbstractConsoleControllerTest extends TestCase
{
    public function testSuccessfulDispatch()
    {
        $controller = new TestAsset\ConcreteConsoleController();
        $request = new ConsoleRequest();
        $event = new MvcEvent();
        $event->setRouteMatch(new RouteMatch(['action' => 'test']));
        $controller->setEvent($event);

        $this->assertEquals('testAction dispatched', $controller->dispatch($request));
    }

    public function testPreDispatchThrowsExceptionWithNonConsoleRequests()
    {
        $controller = new TestAsset\ConcreteConsoleController();
        $request = new HttpRequest();

        try {
            $controller->dispatch($request);
            $this->fail('Controller was able to dispatch a non Console request');
        } catch (\Exception $e) {
            $this->assertEquals(
                get_class($controller).' can only dispatch requests in a console environment',
                $e->getMessage()
            );
        }
    }

    public function testConsoleGetter()
    {
        $controller = new TestAsset\ConcreteConsoleController();

        $console = $this->getMock('Zend\Console\Adapter\AdapterInterface');

        $controller->setConsole($console);

        $this->assertSame($console, $controller->getConsole());
    }

    public function testConsoleGetterLazyness()
    {
        $controller = new TestAsset\ConcreteConsoleController();

        $console = $this->getMock('Zend\Console\Adapter\AdapterInterface');

        $serviceLocator = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');
        $serviceLocator->expects($this->atLeastOnce())
            ->method('get')
            ->with($this->logicalOr('console', 'ConsoleAdapter'))
            ->will($this->returnValue($console));

        $controller->setServiceLocator($serviceLocator);
        $controller->setConsole(null);

        $this->assertSame($console, $controller->getConsole());
    }
}
