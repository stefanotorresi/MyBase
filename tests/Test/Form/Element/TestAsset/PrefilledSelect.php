<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase\Test\Form\Element\TestAsset;

use MyBase\Form\Element\AbstractPrefilledSelect;
use Traversable;

class PrefilledSelect extends AbstractPrefilledSelect
{
    /**
     * @return array|Traversable
     */
    public static function getDefaultOptions()
    {
        return [
            'value' => 'label',
        ];
    }
}
