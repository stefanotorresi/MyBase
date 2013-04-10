<?php

/**
 * Copyright (c) 2013 Stefano Torresi (http://stefanotorresi.it)
 * See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase\Mail;

use DomainException;
use Zend\Mail\Transport;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TransportFactory implements FactoryInterface
{
    /**
     *
     * @var array
     */
    protected $config = array(
        'class' => 'Zend\Mail\Transport\Sendmail',
        'options' => array()
    );

    /**
     *
     * @param ServiceLocatorInterface $services
     * @return Transport\TransportInterface
     * @throws DomainException
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $config = $this->getConfig($services);

        $class   = $config['class'];
        $options = $config['options'];

        switch ($class) {
            case 'Zend\Mail\Transport\Sendmail':
            case 'Sendmail':
            case 'sendmail';
                $transport = new Transport\Sendmail();
                break;
            case 'Zend\Mail\Transport\Smtp';
            case 'Smtp';
            case 'smtp';
                $options = new Transport\SmtpOptions($options);
                $transport = new Transport\Smtp($options);
                break;
            case 'Zend\Mail\Transport\File';
            case 'File';
            case 'file';
                $options = new Transport\FileOptions($options);
                $transport = new Transport\File($options);
                break;
            default:
                throw new DomainException(sprintf(
                    'Unknown mail transport type provided ("%s")',
                    $class
                ));
        }

        return $transport;
    }

    protected function getConfig(ServiceLocatorInterface $serviceLocator)
    {
        return $this->config;
    }
}
