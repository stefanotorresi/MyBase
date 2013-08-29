<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace Mybase\Service;

use Doctrine\Common\Persistence\ObjectManager;
use MyBase\Entity\Entity as BaseEntity;

abstract class AbstractEntityService
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Set the object manager
     *
     * @param ObjectManager $objectManager
     * @return $this
     */
    public function setObjectManager(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;

        return $this;
    }

    /**
     * Get the object manager
     *
     * @return ObjectManager
     */
    public function getObjectManager()
    {
        return $this->objectManager;
    }

    /**
     * @param BaseEntity $entity
     * @return BaseEntity
     */
    public function save(BaseEntity $entity)
    {
        $this->getObjectManager()->persist($entity);
        $this->getObjectManager()->flush();

        return $entity;
    }

    /**
     * @param BaseEntity $entity
     * @return $this
     */
    public function remove(BaseEntity $entity)
    {
        $this->getObjectManager()->remove($entity);
        $this->getObjectManager()->flush();

        return $this;
    }
}
