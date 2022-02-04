<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class ProductRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function index(): QueryBuilder
    {
        $builder = $this->createQueryBuilder('p');

        $builder->orderBy('p.id', 'DESC');

        return $builder;
    }
}