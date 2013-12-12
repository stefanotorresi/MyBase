<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase\Mail\Transport\Exception;

use Zend\Mail\Exception;
use Zend\Mail\Transport\Exception\ExceptionInterface;

/**
 * Exception for Zend\Mail\Transport component.
 */
class DomainException extends Exception\DomainException implements ExceptionInterface
{

}
