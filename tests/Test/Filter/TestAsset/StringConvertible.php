<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase\Test\Filter\TestAsset;

class StringConvertible
{
    public function __toString()
    {
        return __CLASS__;
    }
}
