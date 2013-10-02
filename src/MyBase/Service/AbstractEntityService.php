<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrinePaginatorAdapter;
use MyBase\Entity\Entity as BaseEntity;
use Zend\Paginator\Paginator;

abstract class AbstractEntityService implements EntityManagerAwareInterface
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Set the object manager
     *
     * @param EntityManager $entityManager
     * @return $this
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;

        return $this;
    }

    /**
     * Get the object manager
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param  BaseEntity $entity
     * @return BaseEntity
     */
    public function save(BaseEntity $entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

        return $entity;
    }

    /**
     * @param BaseEntity $entity
     * @return $this
     */
    public function remove(BaseEntity $entity)
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();

        return $this;
    }

    /**
     * @param  Query|QueryBuilder $query
     * @param  int                $page
     * @param  int                $itemCountPerPage
     * @return Paginator
     */
    public function getPagedQuery($query, $page, $itemCountPerPage = 20)
    {
        $paginator = new Paginator(new DoctrinePaginatorAdapter(new DoctrinePaginator($query)));
        $paginator->setDefaultItemCountPerPage($itemCountPerPage);
        $paginator->setCurrentPageNumber($page);

        return $paginator;
    }
}
