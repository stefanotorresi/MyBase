<?php

/**
 * Copyright (c) 2013 Stefano Torresi (http://stefanotorresi.it)
 * See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase\Service;

use Doctrine\ORM\EntityManager;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\AbstractPluginManager;

class ObjectManagerAwareInitializer implements InitializerInterface
{
    /**
     * {@inheritDoc}
     */
    public function initialize($instance, ServiceLocatorInterface $serviceLocator)
    {
        if (! $instance instanceof ObjectManagerAwareInterface) {
            return;
        }

        $serviceManager = ( $serviceLocator instanceof AbstractPluginManager ) ?
            $serviceLocator->getServiceLocator () : $serviceLocator;

        /* @var $objectManager EntityManager */
        $objectManager = $serviceManager->get('Doctrine\ORM\EntityManager');

        $instance->setObjectManager($objectManager);
    }

}
