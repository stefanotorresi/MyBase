<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase\View\Helper;

use Mybase\Service\AbstractEntityService;
use Zend\View\Helper\AbstractHelper as ZendAbstractHelper;

abstract class AbstractEntityHelper extends ZendAbstractHelper
{
    /**
     * @var string
     */
    protected $partial;

    /**
     * @var AbstractEntityService
     */
    protected $entityService;

    /**
     * @param AbstractEntityService $entityService
     */
    public function __construct(AbstractEntityService $entityService)
    {
        $this->entityService = $entityService;
    }

    /**
     * @param string|null $partial
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

    public function __toString()
    {
        return $this->render();
    }

    /**
     * @return string
     */
    abstract public function render();
}
