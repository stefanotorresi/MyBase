<?php

/**
 * Copyright (c) 2013 Stefano Torresi (http://stefanotorresi.it)
 * See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase\Entity;

abstract class Entity
{
    /**
     *
     * @var int
     */
    protected $id;

    /**
     *
     * @param mixed $properties
     */
    public function __construct($properties = array())
    {
        if (!is_array($properties)) {
            $properties = (array) $properties;
        }

        foreach ($properties as $key => $value) {
            $method = 'set'.$key;
            if (method_exists($this, $method)) {
                $this->$method($value);
            } 
        }
    }

    /**
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public static function fqcn()
    {
        return get_called_class();
    }
}
