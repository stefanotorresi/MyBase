<?php

/**
 * Copyright (c) 2013 Stefano Torresi (http://stefanotorresi.it)
 * See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase\Filter;

use Zend\Filter\AbstractFilter;

class Slugify extends AbstractFilter
{
    /**
     * @var string $separator
     */
    private $separator = '-';

    /**
     * Returns the string $value, removing all non alphabetical characters
     *
     * @param  string $value
     * @return string
     */
    public function filter($value, $separator = null)
    {
        if ($separator === null) {
            $separator = $this->separator;
        }

        $value = strtolower(trim($value));

        if (extension_loaded('iconv')) {
            $value = @iconv('UTF-8', 'ASCII//TRANSLIT', $value);
        }

        $value = preg_replace('/[^a-z0-9]/', $separator, $value);
        $value = preg_replace('/'.$separator.'{2,}/', $separator, $value);
        $value = trim($value, $separator);

        return $value;
    }

    /**
     *
     * @param  type    $separator
     * @return Slugify
     */
    public function setSeparator($separator)
    {
        $this->separator = $separator;

        return $this;
    }
}
