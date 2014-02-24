<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase\ServiceManager;

use Assetic\Filter\Sass\SassFilter;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class AsseticSassFilterFactory implements FactoryInterface
{

    /**
     * {@inheritdoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $hydrator = new ClassMethods();
        $config = $serviceLocator->get('Config');
        $options = array_merge([
            'sass_path' => null,
            'ruby_path' => null
        ], $config['MyBase']['AsseticSassFilter']);

        $filter = new SassFilter($options['sass_path'], $options['ruby_path']);
        $hydrator->hydrate($options, $filter);

        return $filter;
    }
}
