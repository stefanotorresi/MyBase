<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase\Mail\Transport;

use Traversable;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\File;
use Zend\Mail\Transport\FileOptions;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mail\Transport\TransportInterface;
use Zend\Mail\Transport\Exception\InvalidArgumentException;
use Zend\Stdlib\ArrayUtils;

abstract class Factory
{
    /**
     * @var array Known transport types
     */
    protected static $classMap = [
        'file'      => 'Zend\Mail\Transport\File',
        'null'      => 'Zend\Mail\Transport\Null',
        'sendmail'  => 'Zend\Mail\Transport\Sendmail',
        'smtp'      => 'Zend\Mail\Transport\Smtp',
    ];

    /**
     * @param  array                     $spec
     * @return TransportInterface
     * @throws InvalidArgumentException
     * @throws Exception\DomainException
     */
    public static function create($spec = [])
    {
        if ($spec instanceof Traversable) {
            $spec = ArrayUtils::iteratorToArray($spec);
        }

        if (! is_array($spec)) {
            throw new InvalidArgumentException(sprintf(
                '%s expects an array or Traversable argument; received "%s"',
                __METHOD__,
                (is_object($spec) ? get_class($spec) : gettype($spec))
            ));
        }

        $type = isset($spec['type']) ? $spec['type'] : 'sendmail';

        $normalizedType = strtolower($type);

        if (isset(static::$classMap[$normalizedType])) {
            $type = static::$classMap[$normalizedType];
        }

        if (! class_exists($type)) {
            throw new Exception\DomainException(sprintf(
                '%s expects the "type" attribute to resolve to an existing class; received "%s"',
                __METHOD__,
                $type
            ));
        }

        $transport = new $type;

        if (! $transport instanceof TransportInterface) {
            throw new Exception\DomainException(sprintf(
                '%s expects the "type" attribute to resolve to a valid Zend\Mail\Transport\TransportInterface instance; received "%s"',
                __METHOD__,
                $type
            ));
        }

        if ($transport instanceof Smtp && isset($spec['options'])) {
            $transport->setOptions(new SmtpOptions($spec['options']));
        }

        if ($transport instanceof File && isset($spec['options'])) {
            $transport->setOptions(new FileOptions($spec['options']));
        }

        return $transport;
    }
}
