<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase\Form\Element;

use Zend\Form\Element\Select;
use Zend\Form\Exception;
use Zend\Validator\Explode;
use Zend\Validator\InArray;

abstract class AbstractPrefilledSelect extends Select implements PrefilledSelectInterface
{
    /**
     * @var string
     */
    protected $inArrayValidatorMessage;

    /**
     * {@inheritdoc}
     */
    public function __construct($name = null, $options = array())
    {
        $options['value_options'] = static::getDefaultOptions();

        parent::__construct($name, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions($options)
    {
        parent::setOptions($options);

        if (isset($this->options['inarray_validator_message'])) {
            $this->setInArrayValidatorMessage($this->options['inarray_validator_message']);
        }

        return $this;
    }

    protected function getValidator()
    {
        $validator = parent::getValidator();

        if (! isset($this->inArrayValidatorMessage)) {
            return $validator;
        }

        if ($validator instanceof InArray) {
            $inArrayValidator = $validator;
        }

        if ($validator instanceof Explode && $validator->getValidator() instanceof InArray) {
            $inArrayValidator = $validator->getValidator();
        }

        if (isset($inArrayValidator)) {
            $inArrayValidator->setMessage($this->inArrayValidatorMessage, InArray::NOT_IN_ARRAY);
        }

        return $validator;
    }

    /**
     * @return string
     */
    public function getInArrayValidatorMessage()
    {
        return $this->inArrayValidatorMessage;
    }

    /**
     * @param  string $inArrayValidatorMessage
     * @return self
     */
    public function setInArrayValidatorMessage($inArrayValidatorMessage)
    {
        $this->inArrayValidatorMessage = (string) $inArrayValidatorMessage;

        return $this;
    }
}
