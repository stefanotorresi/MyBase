<?php

namespace MyBase\Util;

use DateTime;
use ReflectionClass;
use Traversable;

class Serializor
{
    /**
     * Serializes our Doctrine Entities
     *
     * This is the primary entry point, because it assists with handling collections
     * as the primary Object
     *
     * @param  mixed      $mixed     something to converto to array
     * @param  integer    $depth     The Depth of the object graph to pursue
     * @param  array      $whitelist List of entity=>array(parameters) to convert
     * @param  array      $blacklist List of entity=>array(parameters) to skip
     * @return NULL|Array
     *
     */
    public static function toArray($mixed, $depth = 1, $whitelist = [], $blacklist = [])
    {

        // If we drop below depth 0, just return NULL
        if ($depth < 0) {
            return NULL;
        }

        // If this is an array, we need to loop through the values
        if (is_array($mixed) || $mixed instanceof Traversable) {
            // Somthing to Hold Return Values
            $anArray = [];

            // The Loop
            foreach ($mixed as $key => $value) {
                if (is_array($value) || $value instanceof Traversable) {
                    $anArray[] = self::toArray($value, $depth - 1, $whitelist, $blacklist);
                } elseif (is_object($value)) {
                    $anArray[] = self::arrayizor($value, $depth, $whitelist, $blacklist);
                } else {
                    $anArray[$key] = $value;
                }
            }

            // Return it
            return $anArray;
        } elseif (is_object($mixed)) {
            // Just return it
            return self::arrayizor($mixed, $depth, $whitelist, $blacklist);
        } else {
            return $mixed;
        }
    }

    /**
     * This does all the heavy lifting of actually converting to an array
     *
     * @param  object     $object    The Object (Typically a Doctrine Entity) to convert to an array
     * @param  integer    $depth     The Depth of the object graph to pursue
     * @param  array      $whitelist List of entity=>array(parameters) to convert
     * @param  array      $blacklist List of entity=>array(parameters) to skip
     * @return NULL|Array
     */
    private static function arrayizor($anObject, $depth, $whitelist = [], $blacklist = [])
    {
        // Determine the next depth to use
        $nextDepth = $depth - 1;

        // Lets get our Class Name
        // @TODO: Making some assumptions that only objects get passed in, need error checking
        $clazzName = get_class($anObject);

        // Now get our reflection class for this class name
        $reflectionClass = new ReflectionClass($anObject);

        // Then grap the class properites
        $clazzProps = $reflectionClass->getProperties();

        if (is_a($anObject, 'Doctrine\ORM\Proxy\Proxy')) {
            $parent = $reflectionClass->getParentClass();
            $clazzName = $parent->getName();
            $clazzProps = $parent->getProperties();
        }
        // A new array to hold things for us
        $anArray = [];

        // Lets loop through those class properties now
        foreach ($clazzProps as $prop) {

            // If a Whitelist exists
            if (@count($whitelist[$clazzName]) > 0) {
                // And this class property is not in it
                if (!@in_array($prop->name, $whitelist[$clazzName])) {
                    // lets skip it.
                    continue;
                }
                // Otherwise, if a blacklist exists
            } elseif (@count($blacklist[$clazzName] > 0)) {
                // And this class property is in it
                if (@in_array($prop->name, $blacklist[$clazzName])) {
                    // lets skip it.
                    continue;
                }
            }

            // We know the property, lets craft a getProperty method
            $method_name = 'get' . ucfirst($prop->name);
            // And check to see that it exists for this object
            if (method_exists($anObject, $method_name)) {
                // It did, so lets call it!
                $aValue = $anObject->$method_name();
            } else {
                $prop->setAccessible(true);
                $aValue = $prop->getValue($anObject);
            }

            // If it is a datetime, lets make it a string
            if ($aValue instanceof DateTime) {
                $anArray[$prop->name] = $aValue->format('Y-m-d H:i:s');

                continue;
            }

            // recursion
            if (is_object($aValue) || is_array($aValue)) {
                $anArray[$prop->name] = Serializor::toArray($aValue, $nextDepth, $whitelist, $blacklist);

                continue;
            }

            $anArray[$prop->name] = $aValue;
        }
        // All done, send it back!
        return $anArray;
    }
}
