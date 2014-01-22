<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase\ServiceManager;

use HTMLPurifier;
use HTMLPurifier_Config;
use HTMLPurifier_Definition;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class HTMLPurifierFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $moduleConfig = $serviceLocator->get('config')['soflomo_purifier'];

        $config = $moduleConfig['config'];

        if ($moduleConfig['standalone'] && file_exists($moduleConfig['standalone_path'])) {
            include $moduleConfig['standalone_path'];
        }

        $purifierConfig   = HTMLPurifier_Config::createDefault();

        foreach ($config as $key => $value) {
            $purifierConfig->set($key, $value);
        }

        foreach ($moduleConfig['definitions'] as $type => $definitions) {
            $definitionGetter = "maybeGetRaw{$type}Definition";
            if (! method_exists($purifierConfig, $definitionGetter)) {
                throw new \DomainException('Invalid definition type specified');
            }

            $definition = $purifierConfig->$definitionGetter();

            if ($definition instanceof HTMLPurifier_Definition) {
                foreach ($definitions as $method => $args) {
                    call_user_func_array([$definition, $method], $args);
                }
            }
        }

        $purifier = new HTMLPurifier($purifierConfig);

        return $purifier;
    }
}
