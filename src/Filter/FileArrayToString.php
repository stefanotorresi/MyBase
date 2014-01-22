<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase\Filter;

use Zend\Filter\FilterInterface;
use Zend\Stdlib\ArrayUtils;

class FileArrayToString implements FilterInterface
{
    protected $options = [
        'basename' => false,
        'use_uploaded_name' => false,
    ];

    public function __construct($options = array())
    {
        $this->setOptions($options);
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function filter($value)
    {
        if (is_object($value) && ! method_exists($value, '__toString')) {
            return '';
        }

        if (is_array($value)) {
            $dummy = [
                'name' => '',
                'tmp_name' => '',
                'error' => UPLOAD_ERR_NO_FILE,
                'size' => 0,
                'type' => '',
            ];
            $value = ArrayUtils::merge($dummy, $value);
        } else {
            $value = (string) $value;
        }

        if (isset($value['error']) && $value['error'] !== UPLOAD_ERR_OK) {
            return '';
        }

        if ($this->options['use_uploaded_name'] && isset($value['name'])) {
            $value = $value['name'];
        } elseif (isset($value['tmp_name'])) {
            $value = $value['tmp_name'];
        }

        if ($this->options['basename']) {
            $value = basename($value);
        }

        return $value;
    }

    private function setOptions($options)
    {
        $this->options = ArrayUtils::merge($this->options, $options);
    }
}
