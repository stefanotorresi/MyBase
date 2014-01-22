<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase\View\Helper;

use MyBase\DataMapper;
use Zend\View\Helper\AbstractHelper as ZendAbstractHelper;

abstract class AbstractEntityHelper extends ZendAbstractHelper
    implements DataMapper\MapperAwareInterface
{
    use DataMapper\MapperAwareTrait;

    /**
     * @var string
     */
    protected $partial;

    /**
     * @param DataMapper\MapperInterface $mapper
     */
    public function __construct(DataMapper\MapperInterface $mapper)
    {
        $this->setMapper($mapper);
    }

    /**
     * @param  string|null $partial
     * @return $this
     */
    public function __invoke($partial = null)
    {
        if ($partial === null) {
            return $this;
        }

        $this->partial = $partial;

        return $this->render();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        try {
            $output = $this->render();
        } catch (\Exception $e) {
            $output = sprintf('An error occurred during %s rendering.', get_called_class());
        }

        return $output;
    }

    /**
     * @return string
     */
    abstract public function render();
}
