<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase\Filter;

use Traversable;
use Zend\Filter\AbstractFilter;

class Ellipsis extends AbstractFilter
{
    /**
     * @var int
     */
    protected $options = array(
        'maxLength' => null,
        'ellipsis' => '[…]',
    );

    public function __construct($maxLengthOrOptions = null)
    {
        if ($maxLengthOrOptions !== null) {
            if (! is_array($maxLengthOrOptions) && ! $maxLengthOrOptions instanceof Traversable)
            {
                $this->setMaxLength($maxLengthOrOptions);
            } else {
                $this->setOptions($maxLengthOrOptions);
            }
        }
    }

    /**
     * Returns the result of filtering $value
     *
     * @param  string $value
     * @return string
     */
    public function filter($value)
    {
        if (!is_string($value)) {
            return $value;
        }

        return substr($value, 0, strrpos(substr($value, 0, $this->getMaxLength()), ' ')) . ' ' . $this->getEllipsis();
    }

    /**
     * @param  int  $maxLength
     * @return $this
     */
    public function setMaxLength($maxLength)
    {
        if ($maxLength !== null) {
            $maxLength = (int) $maxLength;
        }
        $this->options['maxLength'] = $maxLength;

        return $this;
    }

    /**
     * @param $character
     * @return $this
     */
    public function setEllipsis($character)
    {
        $this->options['ellipsis'] = $character;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMaxLength()
    {
        return $this->options['maxLength'];
    }

    /**
     * @return mixed
     */
    public function getEllipsis()
    {
        return $this->options['ellipsis'];
    }
}
