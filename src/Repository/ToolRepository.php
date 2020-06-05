<?php

namespace App\Repository;

use App\Entity\Tool;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Tool|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tool|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tool[]    findAll()
 * @method Tool[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ToolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tool::class);
    }

    public function findCustom($name, $idDepartment, $idCategory)
    {
        $qb = $this->createQueryBuilder('t')
            ->leftJoin('t.department', 'd')
            ->leftJoin('t.category', 'c')
            ->orderBy('t.id', 'ASC');
        if ($name) {
            $qb->andWhere('t.name LIKE :name')
                ->setParameter('name', '%' . $name . '%');
        }
        if ($idDepartment) {
            $qb->andWhere('d.id = :department')
                ->setParameter('department', $idDepartment);
        }
        if ($idCategory) {
            $qb->andWhere('c.id = :category')
                ->setParameter('category', $idCategory);
        }
        return $qb->getQuery()
            ->getResult();
    }


    /*
    public function findOneBySomeField($value): ?Tool
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
