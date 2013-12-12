<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase\DataMapper;

use Zend\EventManager\Event;

class MapperEvent extends Event
{
    const SAVE_PRE      = 'save.pre';
    const SAVE_POST     = 'save.post';
    const REMOVE_PRE    = 'remove.pre';
    const REMOVE_POST   = 'remove.post';
    const FLUSH_PRE     = 'flush.pre';
    const FLUSH_POST    = 'flush.post';


    /**
     * @var mixed
     */
    protected $entity;

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param mixed $entity
     */
    public function setEntity($entity)
    {
        $this->setParam('entity', $entity);
        $this->entity = $entity;
    }
}
