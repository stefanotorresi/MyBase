<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase\Controller;

use Zend\Console\Adapter\AdapterInterface as ConsoleAdapterInterface;
use Zend\Console\Request as ConsoleRequest;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;

abstract class AbstractConsoleController extends AbstractActionController
{
    /**
     * @var ConsoleAdapterInterface
     */
    protected $console;

    public function setEventManager(EventManagerInterface $events)
    {
        $events->attach(MvcEvent::EVENT_DISPATCH, [$this, 'preDispatch'], 100);

        return parent::setEventManager($events);
    }

    public function preDispatch(MvcEvent $event)
    {
        if (! $event->getRequest() instanceof ConsoleRequest) {
            throw new \RuntimeException(sprintf(
                '%s can only dispatch requests in a console environment',
                get_called_class()
            ));
        }
    }

    /**
     * @return ConsoleAdapterInterface
     */
    public function getConsole()
    {
        if (! $this->console) {
            $this->console = $this->getServiceLocator()->get('console');
        }

        return $this->console;
    }

    /**
     * @param ConsoleAdapterInterface $console
     */
    public function setConsole($console)
    {
        $this->console = $console;
    }
}
