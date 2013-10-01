<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace Mybase\Service;

use Doctrine\ORM\EntityManager;

interface EntityManagerAwareInterface
{
    /**
     * Set the entity manager
     *
     * @param EntityManager $objectManager
     */
    public function setEntityManager(EntityManager $objectManager);

    /**
     * Get the entity manager
     *
     * @return EntityManager
     */
    public function getEntityManager();
}
