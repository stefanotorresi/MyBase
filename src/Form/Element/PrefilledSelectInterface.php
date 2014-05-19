<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase\Form\Element;

use Traversable;

interface PrefilledSelectInterface
{
    /**
     * @return array|Traversable
     */
    public static function getDefaultOptions();
}
