<?php

/**
 * Copyright (c) 2013 Stefano Torresi (http://stefanotorresi.it)
 * See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase\Controller\Plugin;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrinePaginatorAdapter;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Paginator\Paginator;

class DoctrinePaginator extends AbstractPlugin
{
    /**
     *
     * @param  string        $entityClass
     * @param  mixed         $query       Entity FQCN or Doctrine query or Doctrine query builder.
     * @return ZendPaginator
     */
    public function __invoke($data, $itemCountPerPage = 10, $page = null)
    {
        if ($data instanceof EntityRepository) {
            $query = $data->createQueryBuilder('query');
        } else {
            $query = $data;
        }

        $ormPaginator = new ORMPaginator($query);
        $adapter = new DoctrinePaginatorAdapter($ormPaginator);
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage($itemCountPerPage);

        if (!$page) {
            $page = $this->getController()->params()->fromQuery('page');
        }

        $paginator->setCurrentPageNumber((int) $page);

        return $paginator;
    }

}
