<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase\Controller;

use Zend\Console\Request as ConsoleRequest;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;

abstract class AbstractConsoleController extends AbstractActionController
{
    public function setEventManager(EventManagerInterface $events)
    {
        $events->attach(MvcEvent::EVENT_DISPATCH, array($this, 'preDispatch'), 100);

        return parent::setEventManager($events);
    }

    public function preDispatch(MvcEvent $event)
    {
        if (! $event->getRequest() instanceof ConsoleRequest) {
            throw new \RuntimeException(sprintf(
                '%s can only be executed in a console environment',
                __CLASS__
            ));
        }
    }
}
