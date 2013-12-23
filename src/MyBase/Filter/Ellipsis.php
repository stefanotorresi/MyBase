<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase\Filter;

use Traversable;
use Zend\Filter\AbstractFilter;
use Zend\Stdlib\StringUtils;
use Zend\Stdlib\StringWrapper\StringWrapperInterface;

class Ellipsis extends AbstractFilter
{
    /**
     * @var StringWrapperInterface
     */
    protected $stringWrapper;

    /**
     * @var int
     */
    protected $options = array(
        'maxLength' => null,
        'ellipsis' => ' […]',
        'encoding' => 'UTF-8',
    );

    /**
     * @param mixed $maxLengthOrOptions
     */
    public function __construct($maxLengthOrOptions = null)
    {
        if ($maxLengthOrOptions !== null) {
            if (! is_array($maxLengthOrOptions) && ! $maxLengthOrOptions instanceof Traversable) {
                $this->setMaxLength($maxLengthOrOptions);
            } else {
                $this->setOptions($maxLengthOrOptions);
            }
        }
    }

    /**
     * Truncates a string to the next white space after the maximum size, and append an elision suffix
     * Notes:
     * - truncation is done by excess, so the limit is a soft one
     * - elision length is taken into account with the max length
     *
     * @param  string $value
     * @return string
     */
    public function filter($value)
    {
        if (!is_string($value)) {
            return $value;
        }

        $sw = $this->getStringWrapper();

        if ($sw->strlen($value) <= $this->getMaxLength()) {
            return $value;
        }

        $maxLength = $this->getMaxLength() - $sw->strlen($this->getEllipsis());

        // find the first space in the elided part
        $boundary = $sw->strpos($value, ' ', $maxLength);

        if ($boundary === false) {
            $boundary = $maxLength;
        }

        return rtrim($sw->substr($value, 0, $boundary)) . $this->getEllipsis();
    }

    /**
     * @return mixed
     */
    public function getMaxLength()
    {
        return $this->options['maxLength'];
    }

    /**
     * @param int $maxLength
     *                       @return $this
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
     * @return mixed
     */
    public function getEllipsis()
    {
        return $this->options['ellipsis'];
    }

    /**
     * @param string $character
     *                          @return $this
     */
    public function setEllipsis($character)
    {
        $this->options['ellipsis'] = $character;

        return $this;
    }

    /**
     * @return string
     */
    public function getEncoding()
    {
        return $this->options['encoding'];
    }

    /**
     * @param string $encoding
     *                         @return $this
     */
    public function setEncoding($encoding)
    {
        $this->options['encoding'] = $encoding;
        $this->stringWrapper = StringUtils::getWrapper($encoding);

        return $this;
    }

    /**
     * @return StringWrapperInterface
     */
    public function getStringWrapper()
    {
        if (!$this->stringWrapper) {
            $this->stringWrapper = StringUtils::getWrapper($this->getEncoding());
        }

        return $this->stringWrapper;
    }

    /**
     * @param StringWrapperInterface $stringWrapper
     */
    public function setStringWrapper(StringWrapperInterface $stringWrapper)
    {
        $stringWrapper->setEncoding($this->getEncoding());
        $this->stringWrapper = $stringWrapper;
    }
}
